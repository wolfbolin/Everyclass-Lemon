<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 20:22
 */

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/receipt', function (Request $request, Response $response) {
    // 获取请求数据
    $num = intval($request->getQueryParams()['num']);
    if (empty($num)) {
        $num = 1;
    } elseif ($num < 2 || $num > 10) {
        goto Bad_request;
    }


    // 更新MongoDB数据库
    $db = $this->get('mongodb');
    // 获取任务数据
    $collection = $db->selectCollection('mission');
    $select_result = $collection->find(
        ['$expr' => ['$gt' => ['$target', '$success']]],
        [
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
    $select_result = (array)$select_result->getArrayCopy();
    foreach ($mission_list as &$mission) {
        $mission['cid'] = ((array)$select_result['_id'])['oid'];
        $mission['cookie'] = $select_result['cookie'];
        $collection->updateOne(
            ['_id' => (new MongoDB\BSON\ObjectId($mission['cid']))],
            ['$inc' => ['download' => 1]]
        );
    }


    // 更新统计数据
    $collection = $db->selectCollection('statistic');
    $collection->updateOne(
        ['key' => 'total_download'],
        ['$inc' => ['value' => 1]]
    );
    $collection->updateOne(
        ['key' => 'stage_download'],
        ['$inc' => ['value' => 1]]
    );


    // 将字典数据写入请求响应
    return $response->withJson($mission_list);
    // 异常访问出口
    Bad_request:
    return \WolfBolin\Slim\HTTP\Bad_request($response);
});

$app->post('/receipt', function (Request $request, Response $response) {
    // 获取请求数据
    $json_data = json_decode($request->getBody(), true);
    $receipt = $this->get('Data')['receipt'];
    $new_receipt = [];
    foreach ($receipt as $key => $value) {
        if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
            goto Bad_request;
        }
        $new_receipt[$key] = $json_data[$key];
    }
    $receipt = $new_receipt;
    unset($new_receipt);


    // 更新MongoDB数据库
    $db = $this->get('mongodb');
    // 写入回执信息
    $collection = $db->selectCollection('receipt');
    $insert_result = $collection->insertOne($receipt);
    $insert_result = (array)$insert_result->getInsertedId();
    $insert_result['_id'] = $insert_result['oid'];
    $result = $insert_result;
    unset($result['oid'], $insert_result);


    // 更新任务信息
    try {
        $collection = $db->selectCollection('mission');
        $status = $receipt['status'];
        if ($status != 'success' && $status != 'error') {
            goto Bad_request;
        }
        $update_result = $collection->updateOne(
            ['_id' => (new MongoDB\BSON\ObjectId($receipt['mid']))],
            ['$inc' => ['upload' => 1, "$status" => 1]]
        );
        $update_result = [
            "mid" => $receipt['mid'],
            "mission_modified_count" => $update_result->getModifiedCount()
        ];
        $result [] = $update_result;
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        goto Bad_request;
    }


    // 更新cookie信息
    try {
        $collection = $db->selectCollection('cookie');
        $update_result = $collection->updateOne(
            ['_id' => (new MongoDB\BSON\ObjectId($receipt['cid']))],
            ['$inc' => ["$status" => 1]]
        );
        $update_result = [
            "cid" => $receipt['cid'],
            "cookie_modified_count" => $update_result->getModifiedCount()
        ];
        $result [] = $update_result;
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        goto Bad_request;
    }


    // 更新统计数据
    $collection = $db->selectCollection('statistic');
    $collection->updateOne(
        ['key' => 'total_upload'],
        ['$inc' => ['value' => 1]],
        ['upsert' => true]
    );
    $collection->updateOne(
        ['key' => 'stage_upload'],
        ['$inc' => ['value' => 1]],
        ['upsert' => true]
    );
    $collection->updateOne(
        ['key' => "total_$status"],
        ['$inc' => ['value' => 1]],
        ['upsert' => true]
    );
    $collection->updateOne(
        ['key' => "stage_$status"],
        ['$inc' => ['value' => 1]],
        ['upsert' => true]
    );


    // 更新用户UA信息
//    $select_result = $collection->findOne(
//        ['key' => 'user_info', 'value' => ['$in' => [$receipt['user']]]],
//        ['projection' => ['_id' => 1]]
//    );
//    if(empty($select_result)){
//        // 用户UA未被记录
//
//    }else{
//
//    }




    // 将字典数据写入请求响应
//    return $response->withJson($select_result);
    // 异常访问出口
    Bad_request:
//    return \WolfBolin\Slim\HTTP\Bad_request($response);
});

