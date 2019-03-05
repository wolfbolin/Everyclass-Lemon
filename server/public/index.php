<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:23
 */

use \Slim\App as App;

require __DIR__ . '/../vendor/autoload.php';

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
require __DIR__ . '/../src/route/solution.php';

// Run app
try {
    $app->run();
} catch (Exception $e) {
}
