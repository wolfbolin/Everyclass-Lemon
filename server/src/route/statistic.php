<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:24
 */

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


$app->get('/hello_world', function (Request $request, Response $response) {
    $result = ['status' => 'success', 'info' => 'Hello, world!'];
    return $response->withJson($result);
});


$app->group('/statistic', function (App $app) {
    $app->get('/healthy', function (Request $request, Response $response) {
        $check_list = [
            'mongodb' => false
        ];

        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('statistic');
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
    })->add(WolfBolin\Slim\Authority\x_auth_token());
});

