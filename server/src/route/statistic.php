<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:24
 */

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/statistic/hello_world', function () {
    return 'Hello, world!';
});

$app->get('/statistic/healthy', function (Request $request, Response $response) {
    $check_list = [
        'mongodb' => false
    ];

    $mongodb = $this->get('mongodb');
    $collection = $mongodb->selectCollection('statistic');
    $select_result = $collection->findOne(
        ['key' => 'check_code'],
        ['projection' => ['_id' => 0]]
    );
    if ($select_result['value'] == '4pg^EFxv}mWKE-is') {
        $check_list['mongodb'] = true;
    }

    $collection->updateOne(
        ['key' => 'check_time'],
        ['$set' => ['value' => time()]]
    );

    return $response->withJson($check_list);
});

$app->get('/statistic', function (Request $request, Response $response) {

});

