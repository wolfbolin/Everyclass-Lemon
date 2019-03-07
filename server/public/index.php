<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/3
 * Time: 0:23
 */
error_reporting(E_ALL);
set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
});

use Slim\App;

// Include composer
require __DIR__ . '/../vendor/autoload.php';

// Use personal function
require __DIR__ . '/../src/util/http_response.php';
require __DIR__ . '/../src/util/middleware.php';

// Set up config
$config = require __DIR__ . '/../src/config.php';

// Instantiate the app
$app = new App($config);

// Set error handler
unset($app->getContainer()['errorHandler']);
unset($app->getContainer()['phpErrorHandler']);
$sentry = new Raven_Client($config['Sentry_DSN']);;
$error_handler = new Raven_ErrorHandler($sentry);
$error_handler->registerExceptionHandler();
$error_handler->registerErrorHandler();
$error_handler->registerShutdownFunction();
try {
    $sentry->install();
} catch (Raven_Exception $e) {
    $sentry->captureException($e);
}
unset($sentry, $error_handler);

// Set up container
require __DIR__ . '/../src/container.php';

// Register routes
require __DIR__ . '/../src/route/statistic.php';
require __DIR__ . '/../src/route/mission.php';
require __DIR__ . '/../src/route/cookie.php';
require __DIR__ . '/../src/route/receipt.php';

// Run app
try {
    $app->run();
} catch (Exception $e) {
    $container = $app->getContainer();
    $sentry = $container->get('sentry');
    $sentry->captureException($e);
}
