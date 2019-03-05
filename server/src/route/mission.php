<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 20:19
 */

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/mission', function (Request $request, Response $response) {
    $mongo_id = $request->getQueryParams()['_id'];
    $mongo_id = new MongoDB\BSON\ObjectId($mongo_id);

    $collection = $this->get('mongodb')->selectCollection('mission');
    $select_result = $collection->findOne(['_id' => $mongo_id]);
    $select_result = (array)$select_result->getArrayCopy();
    $select_result['_id'] = ((array)$select_result['_id'])['oid'];

    // 将字典数据写入请求响应
    return $response->withJson($select_result);
});

$app->post('/mission', function (Request $request, Response $response) {
    $json_data = json_decode($request->getBody(), true);
    $new_mission = [
        'method' => strtoupper($json_data['method']),
        'host' => $json_data['host'],
        'path' => $json_data['path'],
        'header' => $json_data['header'],
        'param' => $json_data['param'],
        'data' => $json_data['data'],
        'download' => 0,
        'upload' => 0,
        'target' => $json_data['target'],
        'success' => 0,
        'error' => 0
    ];

    $collection = $this->get('mongodb')->selectCollection('mission');
    $insert_result = $collection->insertOne($new_mission);
    $insert_result = (array)$insert_result->getInsertedId();
    $insert_result['_id'] = $insert_result['oid'];
    unset($insert_result['oid']);

    // 将字典数据写入请求响应
    return $response->withJson($insert_result);
});

$app->put('/mission', function (Request $request, Response $response) {
    $json_data = json_decode($request->getBody(), true);
    $new_mission = [
        'method' => $json_data['method'],
        'host' => $json_data['host'],
        'path' => $json_data['path'],
        'header' => $json_data['header'],
        'param' => $json_data['param'],
        'data' => $json_data['data'],
        'download' => $json_data['download'],
        'upload' => $json_data['upload'],
        'target' => $json_data['target'],
        'success' => $json_data['success'],
        'error' => $json_data['error']
    ];

    $collection = $this->get('mongodb')->selectCollection('mission');
    $update_result = $collection->updateOne(
        ['_id' => $request->getQueryParams()['_id']],
        ['$set' => $new_mission]
    );
    $update_result = [
        "_id" => $request->getQueryParams()['_id'],
        "modified_count" => $update_result->getModifiedCount()
    ];

    // 将字典数据写入请求响应
    return $response->withJson($update_result);
});






