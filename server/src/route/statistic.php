<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:24
 */

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/statistic/hello-world', function () {
    return 'Hello, world!';
});

$app->get('/statistic/mongodb', function () {
    $collection = $this->get('mongodb')->selectCollection('statistic');

    $insertOneResult = $collection->findOne([
        'key' => 'health_code'
    ]);
    var_dump($insertOneResult);
});




