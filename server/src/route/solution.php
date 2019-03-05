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
    $num = $request->getQueryParams()['num'];
    if (isset($num) && $num > 10) {
        $num = 10;
    } else {
        $num = 1;
    }

    $collection = $this->get('mongodb')->selectCollection('solution');
    $select_result = $collection->find(
        ['$expr' => ['$gt' => ['$target', '$success']]],
        [
            'sort' => ['success' => 1, 'target' => -1, 'download' => 1],
            'limit' => $num
        ]
    );
    $select_result = (array)$select_result->toArray();

    return $response->withJson($select_result);
});

$app->post('/solution', function (Request $request, Response $response) {
    $new_solution = [
        "cid" => "5c7d74159eb5ef1e100011b4",
        "mid" => "5c7d5e749eb5ef1e100011b2",
        "code" => "200",
        "data" => "HTML doc",
        "time" => "2019-3-5 12:00",
        "user_ip" => "114.114.114.114",
        "user_ua" => "Go client(123456789)"
    ];

    $collection = $this->get('mongodb')->selectCollection('mission');
    $insert_result = $collection->insertOne($new_solution);
    $insert_result = (array)$insert_result->getInsertedId();
    $insert_result['_id'] = $insert_result['oid'];
    unset($insert_result['oid']);

    // 将字典数据写入请求响应
    return $response->withJson($insert_result);
});

