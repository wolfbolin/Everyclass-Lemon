<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 20:22
 */

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/solution', function (Request $request, Response $response) {
    // 获取请求数据
    $num = $request->getQueryParams()['num'];
    if (isset($num) && $num > 10) {
        $num = 10;
    } else {
        $num = 1;
    }

    // 更新MongoDB数据库
    $collection = $this->get('mongodb')->selectCollection('solution');
    $select_result = $collection->find(
        ['$expr' => ['$gt' => ['$target', '$success']]],
        [
            'sort' => ['success' => 1, 'target' => -1, 'download' => 1],
            'limit' => $num
        ]
    );
    $select_result = (array)$select_result->toArray();

    // 将字典数据写入请求响应
    return $response->withJson($select_result);
});

$app->post('/solution', function (Request $request, Response $response) {
    // 获取请求数据
    $json_data = json_decode($request->getBody(), true);
    $new_solution = [
        "cid" => $json_data['cid'],
        "mid" => $json_data['mid'],
        "code" => $json_data['code'],
        "data" => $json_data['data'],
        "time" => $json_data['time'],
        "user_ip" => $json_data['user_ip'],
        "user_ua" => $json_data['user_ua']
    ];

    // 更新MongoDB数据库
    $collection = $this->get('mongodb')->selectCollection('mission');
    $insert_result = $collection->insertOne($new_solution);
    $insert_result = (array)$insert_result->getInsertedId();
    $insert_result['_id'] = $insert_result['oid'];
    unset($insert_result['oid']);

    // 将字典数据写入请求响应
    return $response->withJson($insert_result);
});

