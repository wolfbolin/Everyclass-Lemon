<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 20:19
 */

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/mission', function (App $app) {
    $app->get('/init', function (Request $request, Response $response) {
        $result = ['status' => 'success'];
        // 清空历史数据并重置统计数据
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        // 删除任务信息
        $collection = $db->selectCollection('mission');
        $delete_result = $collection->deleteMany([]);
        $result = array_merge($result, ["mission_deleted_count" => $delete_result->getDeletedCount()]);

        // 删除任务信息
        $collection = $db->selectCollection('cookie');
        $delete_result = $collection->deleteMany([]);
        $result = array_merge($result, ["cookie_deleted_count" => $delete_result->getDeletedCount()]);

        // 删除回执信息
        $collection = $db->selectCollection('receipt');
        $delete_result = $collection->deleteMany([]);
        $result = array_merge($result, ["receipt_deleted_count" => $delete_result->getDeletedCount()]);

        // 更新统计信息
        $collection = $db->selectCollection('receipt');
        $stage_list = $this->get('Statistic')['stage_list'];
        $update_result = $collection->updateMany(['key' => ['$in' => $stage_list]], ['$set' => ['value' => 0]]);
        $result = array_merge($result, ["statistic_update_count" => $update_result->getModifiedCount()]);

        // 清空用户信息
        $collection = $db->selectCollection('user');
        $delete_result = $collection->deleteMany([]);
        $result = array_merge($result, ["user_deleted_count" => $delete_result->getDeletedCount()]);

        // 将字典数据写入请求响应
        return $response->withJson($result);
    });

    $app->post('/bulk', function (Request $request, Response $response) {
        // 获取请求数据
        $json_data = json_decode($request->getBody(), true);
        $mission_list = [];
        foreach ($json_data as $mission) {
            $mission_form = $this->get('Data')['mission'];
            foreach ($mission_form as $key => &$value) {
                if ($key == 'download' || $key == 'upload' || $key == 'success' || $key == 'error') {
                    continue;
                }
                if (!isset($mission[$key]) || gettype($mission[$key]) != gettype($value)) {
                    goto Bad_request;
                }
                $value = $mission[$key];
            }
            $mission_form['header'] = (object)$mission_form['header'];
            $mission_form['param'] = (object)$mission_form['param'];
            $mission_list [] = $mission_form;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('mission');
        $insert_result = $collection->insertMany($mission_list);
        $insert_result = (array)$insert_result->getInsertedIds();
        $result = ['info' => []];
        foreach ($insert_result as $each_result) {
            $result['info'] [] = ((array)$each_result)['oid'];
        }

        // 将字典数据写入请求响应
        $result = array_merge($result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });

    $app->post('', function (Request $request, Response $response) {
        // 获取请求数据
        $json_data = json_decode($request->getBody(), true);
        $mission = $this->get('Data')['mission'];
        foreach ($mission as $key => &$value) {
            if ($key == 'download' || $key == 'upload' || $key == 'success' || $key == 'error') {
                continue;
            }
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $value = $json_data[$key];
        }
        $mission['header'] = (object)$mission['header'];
        $mission['param'] = (object)$mission['param'];


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('mission');
        $insert_result = $collection->insertOne($mission);
        $insert_result = (array)$insert_result->getInsertedId();
        $insert_result['mid'] = $insert_result['oid'];
        unset($insert_result['oid']);

        // 更新统计数据
        $collection = $db->selectCollection('statistic');
        $collection->updateOne(
            ['key' => 'total_target'],
            ['$inc' => ['value' => 1]],
            ['upsert' => true]
        );
        $collection->updateOne(
            ['key' => 'stage_target'],
            ['$inc' => ['value' => 1]],
            ['upsert' => true]
        );

        // 将字典数据写入请求响应
        $result = array_merge($insert_result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });


    $app->get('', function (Request $request, Response $response) {
        // 获取请求数据
        if (isset($request->getQueryParams()['mid']) && !empty($request->getQueryParam('mid'))) {
            $mission_id = $request->getQueryParam('mid');
        } else {
            goto Bad_request;
        }
        try {
            $mission_id = new MongoDB\BSON\ObjectId($mission_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('mission');
        $select_result = $collection->findOne(['_id' => $mission_id]);
        if (empty($select_result)) {
            goto Not_acceptable;
        }
        $select_result = (array)$select_result->getArrayCopy();
        $select_result['mid'] = ((array)$select_result['_id'])['oid'];
        unset($select_result['_id']);


        // 将字典数据写入请求响应
        $result = array_merge($select_result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_acceptable:
        return WolfBolin\Slim\HTTP\Not_acceptable($response);
    });


    $app->put('', function (Request $request, Response $response) {
        // 获取请求数据
        if (isset($request->getQueryParams()['mid']) && !empty($request->getQueryParam('mid'))) {
            $mission_id = $request->getQueryParam('mid');
        } else {
            goto Bad_request;
        }
        try {
            $mission_id = new MongoDB\BSON\ObjectId($mission_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }
        $json_data = json_decode($request->getBody(), true);
        $mission = $this->get('Data')['mission'];
        foreach ($mission as $key => &$value) {
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $value = $json_data[$key];
        }
        $mission['header'] = (object)$mission['header'];
        $mission['param'] = (object)$mission['param'];


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('mission');
        $update_result = $collection->updateOne(
            ['_id' => $mission_id],
            ['$set' => $mission]
        );
        if ($update_result->getModifiedCount() == 0) {
            goto Not_modified;
        } else {
            $update_result = [
                "mid" => $request->getQueryParam('mid'),
                "mission_modified_count" => $update_result->getModifiedCount()
            ];
        }


        // 遗留BUG，统计数据未更新


        // 将字典数据写入请求响应
        $result = array_merge($update_result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_modified:
        return WolfBolin\Slim\HTTP\Not_modified($response);
    });


    $app->delete('', function (Request $request, Response $response) {
        // 获取请求数据
        if (isset($request->getQueryParams()['mid']) && !empty($request->getQueryParam('mid'))) {
            $mission_id = $request->getQueryParam('mid');
        } else {
            goto Bad_request;
        }
        try {
            $mission_id = new MongoDB\BSON\ObjectId($mission_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('mission');
        $delete_result = $collection->deleteOne(['_id' => $mission_id]);
        if ($delete_result->getDeletedCount() == 0) {
            goto Not_modified;
        } else {
            $delete_result = [
                "mid" => $request->getQueryParam('mid'),
                "mission_deleted_count" => $delete_result->getDeletedCount()
            ];
        }


        // 遗留BUG，统计数据未更新


        // 将字典数据写入请求响应
        $result = array_merge($delete_result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_modified:
        return WolfBolin\Slim\HTTP\Not_modified($response);
    });
})->add(WolfBolin\Slim\Middleware\x_auth_token());





