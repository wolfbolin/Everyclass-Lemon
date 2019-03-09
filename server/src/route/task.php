<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 20:22
 */

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/task', function (App $app) {
    $app->get('', function (Request $request, Response $response) {
        // 获取请求数据
        $num = intval($request->getQueryParam('num', 1));
        if ($num < 1 || $num > 10) {
            goto Bad_request;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        // 获取任务数据
        $collection = $db->selectCollection('mission');
        $select_result = $collection->find(
            ['$expr' => ['$gt' => ['$target', '$success']]],
            [
                'projection' => [
                    'target' => 0,
                    'download' => 0,
                    'upload' => 0,
                    'success' => 0,
                    'error' => 0
                ],
                'sort' => ['success' => 1, 'target' => -1, 'download' => 1],
                'limit' => $num
            ]
        );
        $select_result = (array)$select_result->toArray();
        foreach ($select_result as &$mission) {
            $mission['mid'] = ((array)$mission['_id'])['oid'];
            unset($mission['_id']);
            $collection->updateOne(
                ['_id' => (new MongoDB\BSON\ObjectId($mission['mid']))],
                ['$inc' => ['download' => 1]]
            );
        }
        $mission_list = $select_result;


        // 获取cookie数据
        $collection = $db->selectCollection('cookie');
        $select_result = $collection->findOne([],
            [
                'projection' => [
                    '_id' => 1,
                    'cookie' => 1
                ],
                'sort' => ['download' => 1],
                'limit' => 1
            ]
        );
        if ($select_result) {
            // 有cookie可用
            $select_result = (array)$select_result->getArrayCopy();
            $select_result['cid'] = ((array)$select_result['_id'])['oid'];
        } else {
            // 无cookie可用
            $select_result = [
                'cid' => '',
                'cookie' => ''
            ];
        }
        foreach ($mission_list as &$mission) {
            $mission['cid'] = $select_result['cid'];
            $mission['cookie'] = $select_result['cookie'];
            if ($mission['cid'] != '') {
                $collection->updateOne(
                    ['_id' => (new MongoDB\BSON\ObjectId($mission['cid']))],
                    ['$inc' => ['download' => 1]]
                );
            }
        }


        // 更新统计数据
        $collection = $db->selectCollection('statistic');
        $collection->updateOne(
            ['key' => 'total_download'],
            ['$inc' => ['value' => 1]],
            ['upsert' => true]
        );
        $collection->updateOne(
            ['key' => 'stage_download'],
            ['$inc' => ['value' => 1]],
            ['upsert' => true]
        );


        // 将字典数据写入请求响应
        return $response->withJson($mission_list);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });

    $app->post('', function (Request $request, Response $response) {
        // 获取请求数据
        $json_data = json_decode($request->getBody(), true);
        $task = $this->get('Data')['task'];
        foreach ($task as $key => &$value) {
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $value = $json_data[$key];
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);


        // 更新任务信息
        try {
            $collection = $db->selectCollection('mission');
            $status = $task['status'];
            if ($status != 'success' && $status != 'error') {
                goto Bad_request;
            }
            $collection->updateOne(
                ['_id' => (new MongoDB\BSON\ObjectId($task['mid']))],
                ['$inc' => ['upload' => 1, "$status" => 1]]
            );
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }

        // 写入回执信息
        $collection = $db->selectCollection('task');
        $insert_result = $collection->insertOne($task);
        $insert_result = (array)$insert_result->getInsertedId();
        $insert_result['rid'] = $insert_result['oid'];
        $result = $insert_result;
        unset($result['oid'], $insert_result);
        $result = array_merge($result, ["mid" => $task['mid']]);


        // 更新cookie信息
        try {
            $collection = $db->selectCollection('cookie');
            $collection->updateOne(
                ['_id' => (new MongoDB\BSON\ObjectId($task['cid']))],
                ['$inc' => ["$status" => 1]]
            );
            $result = array_merge($result, ["cid" => $task['cid']]);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }


        // 收集需要更新的统计信息
        $task_list = $this->get('Statistic')['task_list'];


        // 更新用户UA信息
        $collection = $db->selectCollection('user');
        $select_result = $collection->findOne(
            ['user_agent' => $task['user']],
            ['projection' => ['_id' => 1]]
        );
        if (empty($select_result)) {
            // 用户UA未被记录
            $collection->insertOne([
                'user_agent' => $task['user'],
                'user_time' => 0,
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'last_modified' => time()
            ]);
            $task_list [] = 'total_user';
            $task_list [] = 'stage_user';

        } else {
            // 用户UA已被记录
            $collection->updateOne(
                ['user_agent' => $task['user']],
                [
                    '$inc' => ['user_time' => 1],
                    '$set' => [
                        'last_modified' => time(),
                        'user_ip' => $_SERVER['REMOTE_ADDR']
                    ]
                ]
            );
        }


        // 更新统计数据
        $collection = $db->selectCollection('statistic');
        $task_list [] = "total_$status";
        $task_list [] = "stage_$status";
        $collection->updateMany(
            ['key' => ['$in' => $task_list]],
            ['$inc' => ['value' => 1]],
            ['upsert' => true]
        );


        // 将字典数据写入请求响应
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });
});

