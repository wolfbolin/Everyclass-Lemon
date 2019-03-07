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
        return $response->withJson($insert_result);
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
        return $response->withJson($select_result);
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
        return $response->withJson($update_result);
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
        return $response->withJson($delete_result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_modified:
        return WolfBolin\Slim\HTTP\Not_modified($response);
    });
})->add(WolfBolin\Slim\Middleware\x_auth_token());





