<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:33
 */

// Container
use Slim\Container;

$container = $app->getContainer();

// Error Handling
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return WolfBolin\Slim\HTTP\Not_found($response);
    };
};
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return WolfBolin\Slim\HTTP\Not_allowed($response);
    };
};
$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        return WolfBolin\Slim\HTTP\Server_error($response);
    };
};
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return WolfBolin\Slim\HTTP\Server_error($response);
    };
};

//MongoDB
$container['mongodb'] = function (Container $a) {
    $unix = "mongodb://" . ($a->get('MongoDB')['host']);
    if (!empty($a->get('MongoDB')['port'])) {
        $unix .= ":" . $a->get('MongoDB')['port'];
    }
    $uriOptions = [];
    foreach ($a->get('MongoDB') as $key => $value) {
        if ($key != "db" && $key != "host" && $key != "port" && !empty($value)) {
            $uriOptions[$key] = $value;
        }
    }

    $client = new MongoDB\Driver\Manager($unix, $uriOptions);

    return $client;
};