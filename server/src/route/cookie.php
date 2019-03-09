<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/6
 * Time: 17:42
 */

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/cookie', function (App $app) {
    $app->post('/bulk', function (Request $request, Response $response) {
        // 获取请求数据
        $json_data = json_decode($request->getBody(), true);
        $cookie_list = [];
        foreach ($json_data as $cookie) {
            $mission_form = $this->get('Data')['cookie'];
            foreach ($mission_form as $key => &$value) {
                if ($key == 'download' || $key == 'upload' || $key == 'success' || $key == 'error') {
                    continue;
                }
                if (!isset($cookie[$key]) || gettype($cookie[$key]) != gettype($value)) {
                    goto Bad_request;
                }
                $value = $cookie[$key];
            }
            $cookie_list [] = $mission_form;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $insert_result = $collection->insertMany($cookie_list);
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


    $app->get('/bulk', function (Request $request, Response $response) {
        // 获取请求数据
        $num = intval($request->getQueryParam('num', 20));
        if ($num < 1 || $num > 20) {
            goto Bad_request;
        }

        // 获取Cookie信息
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $select_result = $collection->find(
            [],
            [
                'sort' => [
                    'error' => -1,
                    'success' => 1,
                    'download' => -1
                ],
                'limit' => $num
            ]
        );
        $select_result = (array)$select_result->toArray();
        foreach ($select_result as &$cookie) {
            $cookie['mid'] = ((array)$cookie['_id'])['oid'];
            unset($cookie['_id']);
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'info' => $select_result
        ];
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });


    $app->get('/list', function (Request $request, Response $response) {
        // 获取Cookie信息
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $select_result = $collection->find(
            [],
            [
                'projection' => [
                    '_id' => 1,
                ]
            ]
        );
        $select_result = (array)$select_result->toArray();
        foreach ($select_result as &$cookie) {
            $cookie = ((array)$cookie['_id'])['oid'];
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'number' => count($select_result),
            'cid' => $select_result
        ];
        return $response->withJson($result);
    });


    $app->post('', function (Request $request, Response $response) {
        // 获取请求数据
        $json_data = json_decode($request->getBody(), true);
        $cookie = $this->get('Data')['cookie'];
        foreach ($cookie as $key => &$value) {
            if ($key == 'download' || $key == 'upload' || $key == 'success' || $key == 'error') {
                continue;
            }
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $value = $json_data[$key];
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $insert_result = $collection->insertOne($cookie);
        $insert_result = (array)$insert_result->getInsertedId();
        $insert_result['cid'] = $insert_result['oid'];
        unset($insert_result['oid']);


        // 将字典数据写入请求响应
        $result = array_merge($insert_result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
    });


    $app->get('', function (Request $request, Response $response) {
        // 获取请求数据
        if (isset($request->getQueryParams()['cid']) && !empty($request->getQueryParam('cid'))) {
            $cookie_id = $request->getQueryParam('cid');
        } else {
            goto Bad_request;
        }
        try {
            $cookie_id = new MongoDB\BSON\ObjectId($cookie_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $select_result = $collection->findOne(['_id' => $cookie_id]);
        if (empty($select_result)) {
            goto Not_acceptable;
        }
        $select_result = (array)$select_result->getArrayCopy();
        $select_result['cid'] = ((array)$select_result['_id'])['oid'];
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
        if (isset($request->getQueryParams()['cid']) && !empty($request->getQueryParam('cid'))) {
            $cookie_id = $request->getQueryParam('cid');
        } else {
            goto Bad_request;
        }
        try {
            $cookie_id = new MongoDB\BSON\ObjectId($cookie_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }
        $json_data = json_decode($request->getBody(), true);
        $cookie = $this->get('Data')['cookie'];
        foreach ($cookie as $key => &$value) {
            if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
                goto Bad_request;
            }
            $value = $json_data[$key];
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $update_result = $collection->updateOne(
            ['_id' => $cookie_id],
            ['$set' => $cookie]
        );
        if ($update_result->getModifiedCount() == 0) {
            goto Not_modified;
        } else {
            $update_result = [
                "cid" => $request->getQueryParam('cid'),
                "cookie_modified_count" => $update_result->getModifiedCount()
            ];
        }


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
        if (isset($request->getQueryParams()['cid']) && !empty($request->getQueryParam('cid'))) {
            $cookie_id = $request->getQueryParam('cid');
        } else {
            goto Bad_request;
        }
        try {
            $cookie_id = new MongoDB\BSON\ObjectId($cookie_id);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            goto Bad_request;
        }


        // 更新MongoDB数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('cookie');
        $select_result = $collection->deleteOne(['_id' => $cookie_id]);
        if ($select_result->getDeletedCount() == 0) {
            goto Not_modified;
        } else {
            $select_result = [
                "cid" => $request->getQueryParam('cid'),
                "cookie_deleted_count" => $select_result->getDeletedCount()
            ];
        }


        // 将字典数据写入请求响应
        $result = array_merge($select_result, ['status' => 'success']);
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return WolfBolin\Slim\HTTP\Bad_request($response);
        Not_modified:
        return WolfBolin\Slim\HTTP\Not_modified($response);
    });
})->add(WolfBolin\Slim\Middleware\x_auth_token());