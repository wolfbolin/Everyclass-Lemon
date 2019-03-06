<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/6
 * Time: 17:42
 */

use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/cookie', function (Request $request, Response $response) {
    // 获取请求数据
    try {
        $cookie_id = $request->getQueryParams()['_id'];
        if (empty($cookie_id)) {
            goto Not_found;
        }
        $cookie_id = new MongoDB\BSON\ObjectId($cookie_id);
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        goto Bad_request;
    }


    // 更新MongoDB数据库
    $mongodb = $this->get('mongodb');
    $collection = $mongodb->selectCollection('cookie');
    $select_result = $collection->findOne(['_id' => $cookie_id]);
    if (empty($select_result)) {
        goto Not_found;
    }
    $select_result = (array)$select_result->getArrayCopy();
    $select_result['_id'] = ((array)$select_result['_id'])['oid'];


    // 将字典数据写入请求响应
    return $response->withJson($select_result);
    // 异常访问出口
    Bad_request:
    return \WolfBolin\Slim\HTTP\Bad_request($response);
    Not_found:
    return \WolfBolin\Slim\HTTP\Not_found($response);
});


$app->post('/cookie', function (Request $request, Response $response) {
    // 获取请求数据
    $json_data = json_decode($request->getBody(), true);
    $cookie = $this->get('Data')['cookie'];
    $new_cookie = [];
    foreach ($cookie as $key => $value) {
        if ($key == 'download' || $key == 'upload' || $key == 'success' || $key == 'error') {
            $new_cookie[$key] = 0;
            continue;
        }
        if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
            goto Bad_request;
        }
        $new_cookie[$key] = $json_data[$key];
    }
    $cookie = $new_cookie;
    unset($new_cookie);


    // 更新MongoDB数据库
    $mongodb = $this->get('mongodb');
    $collection = $mongodb->selectCollection('cookie');
    $insert_result = $collection->insertOne($cookie);
    $insert_result = (array)$insert_result->getInsertedId();
    $insert_result['_id'] = $insert_result['oid'];
    unset($insert_result['oid']);


    // 将字典数据写入请求响应
    return $response->withJson($insert_result);
    // 异常访问出口
    Bad_request:
    return \WolfBolin\Slim\HTTP\Bad_request($response);
});


$app->put('/cookie', function (Request $request, Response $response) {
    // 获取请求数据
    try {
        $cookie_id = $request->getQueryParams()['_id'];
        if (empty($cookie_id)) {
            goto Not_found;
        }
        $cookie_id = new MongoDB\BSON\ObjectId($cookie_id);
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        goto Bad_request;
    }
    $json_data = json_decode($request->getBody(), true);
    $cookie = $this->get('Data')['cookie'];
    $new_cookie = [];
    foreach ($cookie as $key => $value) {
        if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
            goto Bad_request;
        }
        $new_cookie[$key] = $json_data[$key];
    }
    $cookie = $new_cookie;
    unset($new_cookie);


    // 更新MongoDB数据库
    $mongodb = $this->get('mongodb');
    $collection = $mongodb->selectCollection('cookie');
    $update_result = $collection->updateOne(
        ['_id' => $cookie_id],
        ['$set' => $cookie]
    );
    if ($update_result->getModifiedCount() == 0) {
        goto Not_modified;
    } else {
        $update_result = [
            "_id" => $request->getQueryParams()['_id'],
            "cookie_modified_count" => $update_result->getModifiedCount()
        ];
    }


    // 将字典数据写入请求响应
    return $response->withJson($update_result);
    // 异常访问出口
    Bad_request:
    return \WolfBolin\Slim\HTTP\Bad_request($response);
    Not_found:
    return \WolfBolin\Slim\HTTP\Not_found($response);
    Not_modified:
    return \WolfBolin\Slim\HTTP\Not_modified($response);
});


$app->delete('/cookie', function (Request $request, Response $response) {
    // 获取请求数据
    try {
        $cookie_id = $request->getQueryParams()['_id'];
        if (empty($cookie_id)) {
            goto Not_found;
        }
        $cookie_id = new MongoDB\BSON\ObjectId($cookie_id);
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        goto Bad_request;
    }


    // 更新MongoDB数据库
    $mongodb = $this->get('mongodb');
    $collection = $mongodb->selectCollection('cookie');
    $select_result = $collection->deleteOne(['_id' => $cookie_id]);
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
    return \WolfBolin\Slim\HTTP\Bad_request($response);
    Not_found:
    return \WolfBolin\Slim\HTTP\Not_found($response);
    Not_modified:
    return \WolfBolin\Slim\HTTP\Not_modified($response);
});