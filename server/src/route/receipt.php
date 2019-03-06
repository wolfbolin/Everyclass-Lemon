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
        $error_info = [
            "status" => "error",
            "info" => "访问参数异常"
        ];
        return $response->withStatus(403)->withJson($error_info);
    }

    // 更新MongoDB数据库
    $mission_col = $this->get('mongodb')->selectCollection('mission');
    $select_result = $mission_col->find(
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
        $mission_col->updateOne(
            ['_id' => (new MongoDB\BSON\ObjectId($mission['mid']))],
            ['$inc' => ['download' => 1]]
        );

    }

    // 将字典数据写入请求响应
    return $response->withJson($select_result);
});

$app->post('/receipt', function (Request $request, Response $response) {
    // 获取请求数据
    $json_data = json_decode($request->getBody(), true);
    $receipt = $this->get('Data')['receipt'];
    $new_receipt = [];
    foreach ($receipt as $key => $value) {
        if (!isset($json_data[$key]) || gettype($json_data[$key]) != gettype($value)) {
            $error_info = [
                "status" => "error",
                "info" => "访问参数异常"
            ];
            return $response->withStatus(403)->withJson($error_info);
        }
        $new_receipt[$key] = $value;
    }
    $receipt = $new_receipt;
    unset($new_receipt);

    // 更新MongoDB数据库
    $db = $this->get('mongodb');
    $receipt_co = $db->selectCollection('receipt');
    $insert_result = $receipt_co->insertOne($receipt);
    $insert_result = (array)$insert_result->getInsertedId();
    $insert_result['_id'] = $insert_result['oid'];

    $mission_co = $db->selectCollection('mission');
    $update_result = $mission_co->updateOne(
        ['_id' => (new MongoDB\BSON\ObjectId($receipt['mid']))],
        
    );


    unset($insert_result['oid']);

    // 将字典数据写入请求响应
    return $response->withJson($insert_result);
});

