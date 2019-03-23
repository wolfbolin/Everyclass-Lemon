<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/22
 * Time: 18:00
 */

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/info/universal', function (App $app) {
    // Hello_world
    $app->get('/hello_world', function (Request $request, Response $response) {
        $result = ['status' => 'success', 'info' => 'Hello, world!'];
        return $response->withJson($result);
    });

    // 线上服务信息
    $app->get('/server', function (Request $request, Response $response) {
        // 获取PHP版本
        $php_version = phpversion();
        // 获取协议版本
        $version = $this->get('Version');
        // 获取服务状态与服务描述
        $db = new \MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('info');
        $service_state = $collection->findOne([
            'key' => 'service_state'
        ]);
        $service_notice = $collection->findOne([
            'key' => 'service_notice'
        ]);
        $result = [
            'status' => 'success',
            'php' => $php_version,
            'version' => $version,
            'service_state' => $service_state['value'],
            'service_notice' => $service_notice['value']
        ];
        return $response->withJson($result);
    });
})->add(\WolfBolin\Slim\Middleware\access_record());


$app->group('/info/special', function (App $app) {
    // 健康状态检查
    $app->get('/healthy', function (Request $request, Response $response) {
        // 初始化健康检查列表
        $check_list = $this->get('Statistic')['check'];

        // 检查MongoDB连接状态
        try {
            $db = new MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
            $collection = $db->selectCollection('statistic');
            $select_result = $collection->findOne(
                ['key' => 'check_code'],
                ['projection' => ['_id' => 0]]
            );
            if ($select_result['value'] == $version = $this->get('Mongo_Token')) {
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
    });

    // 服务统计信息
    $app->get('/status', function (Request $request, Response $response) {
        //  连接数据库
        $db = new MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
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
})->add(\WolfBolin\Slim\Middleware\x_auth_token())
    ->add(\WolfBolin\Slim\Middleware\maintenance_mode())
    ->add(\WolfBolin\Slim\Middleware\access_record());

