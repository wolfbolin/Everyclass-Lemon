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
    $app->get('', function (Request $request, Response $response) {
        // 获取请求数据
        try {
            $mission_id = $request->getQueryParams()['_id'];
            if (empty($mission_id)) {
                goto Not_acceptable;
            }
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
        $select_result['_id'] = ((array)$select_result['_id'])['oid'];


        // 将字典数据写入请求响应
        return $response->withJson($select_result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_acceptable:
        return WolfBolin\Slim\HTTP\Not_acceptable($response);
    });


    $app->post('', function (Request $request, Response $response) {
        // 获取请求数据
        $json_data = json_decode($request->getBody(), true);
        $mission = $this->get('Data')['mission'];
        $new_mission = [];
        foreach ($mission as $key => $value) {
            if ($key == 'download' || $key == 'upload' || $key == 'success' || $key == 'error') {
                $new_mission[$key] = 0;
                continue;
            }
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $new_mission[$key] = $json_data[$key];
        }
        $new_mission['header'] = (object)$new_mission['header'];
        $new_mission['param'] = (object)$new_mission['param'];
        $mission = $new_mission;
        unset($new_mission);


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('mission');
        $insert_result = $collection->insertOne($mission);
        $insert_result = (array)$insert_result->getInsertedId();
        $insert_result['_id'] = $insert_result['oid'];
        unset($insert_result['oid']);


        // 将字典数据写入请求响应
        return $response->withJson($insert_result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });


    $app->put('', function (Request $request, Response $response) {
        // 获取请求数据
        try {
            $mission_id = $request->getQueryParams()['_id'];
            if (empty($mission_id)) {
                goto Not_acceptable;
            }
            $mission_id = new MongoDB\BSON\ObjectId($mission_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }
        $json_data = json_decode($request->getBody(), true);
        $mission = $this->get('Data')['mission'];
        $new_mission = [];
        foreach ($mission as $key => $value) {
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $new_mission[$key] = $json_data[$key];
        }
        $mission = $new_mission;
        unset($new_mission);


        // 更新MongoDB数据库
        $db = $this->get('mongodb');
        $collection = $db->selectCollection('mission');
        $update_result = $collection->updateOne(
            ['_id' => $mission_id],
            ['$set' => $mission]
        );
        if ($update_result->getModifiedCount() == 0) {
            goto Not_modified;
        } else {
            $update_result = [
                "_id" => $request->getQueryParams()['_id'],
                "mission_modified_count" => $update_result->getModifiedCount()
            ];
        }


        // 将字典数据写入请求响应
        return $response->withJson($update_result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_acceptable:
        return WolfBolin\Slim\HTTP\Not_acceptable($response);
        Not_modified:
        return WolfBolin\Slim\HTTP\Not_modified($response);
    });


    $app->delete('', function (Request $request, Response $response) {
        // 获取请求数据
        try {
            $mission_id = $request->getQueryParams()['_id'];
            if (empty($mission_id)) {
                goto Not_acceptable;
            }
            $mission_id = new MongoDB\BSON\ObjectId($mission_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }


        // 更新MongoDB数据库
        $db = $this->get('mongodb');
        $collection = $db->selectCollection('mission');
        $select_result = $collection->deleteOne(['_id' => $mission_id]);
        if ($select_result->getDeletedCount() == 0) {
            goto Not_modified;
        } else {
            $select_result = [
                "_id" => $request->getQueryParams()['_id'],
                "cookie_deleted_count" => $select_result->getDeletedCount()
            ];
        }


        // 将字典数据写入请求响应
        return $response->withJson($select_result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_acceptable:
        return WolfBolin\Slim\HTTP\Not_acceptable($response);
        Not_modified:
        return WolfBolin\Slim\HTTP\Not_modified($response);
    });
})->add(WolfBolin\Slim\Authority\x_auth_token());





