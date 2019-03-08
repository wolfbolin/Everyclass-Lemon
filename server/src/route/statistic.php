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
        // 初始化健康检查列表
        $check_list = $this->get('Statistic')['check'];

        // 检查MongoDB连接状态
        try {
            $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
            $collection = $db->selectCollection('statistic');
            $select_result = $collection->findOne(
                ['key' => 'check_code'],
                ['projection' => ['_id' => 0]]
            );
            if ($select_result['value'] == '4pg^EFxv}mWKE-is') {
                $check_list['mongodb'] = true;
            }
        } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            $check_list['mongodb'] = false;
        }


        // 反馈检查结果
        $result = [
            'status' => 'success',
            'time' => time()
        ];
        foreach ($check_list as $key => $value) {
            if ($value == false) {
                $result['status'] = 'error';
            }
        }
        $result = array_merge($result, $check_list);
        return $response->withJson($result);
    })->add(WolfBolin\Slim\Middleware\x_auth_token());


    $app->get('/status', function (Request $request, Response $response) {
        //  连接数据库
        $db = new MongoDB\Database($this->get('mongodb'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('statistic');
        // 初始化查询课表
        $result_list = $this->get('Statistic')['status_list'];

        // 逐项查询数据
        foreach ($result_list as $key => &$value) {
            $select_result = $collection->findOne(
                ['key' => $key],
                ['projection' => ['_id' => 0]]
            );
            $value = $select_result['value'];
        }

        // 写入反馈数据
        return $response->withJson($result_list);
    });
});

