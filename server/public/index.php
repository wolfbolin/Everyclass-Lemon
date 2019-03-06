<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:23
 */

use \Slim\App as App;

require __DIR__ . '/../vendor/autoload.php';

// Use personal function
require __DIR__ . '/../src/util/http_response.php';

//session_start();

// Set up config
$config = require __DIR__ . '/../src/config.php';

// Instantiate the app
$app = new \Slim\App($config);

// Set up container
require __DIR__ . '/../src/container.php';

// Register middleware
//require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/route/statistic.php';
require __DIR__ . '/../src/route/mission.php';
require __DIR__ . '/../src/route/cookie.php';
require __DIR__ . '/../src/route/receipt.php';

// Run app
try {
    $app->run();
} catch (Exception $e) {
}
