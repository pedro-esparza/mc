<?php

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
header('content-type: application/json; charset=utf-8');

const ROOT_API_PATH = __DIR__ . '/';


use src\api\controllers\RepositoryController;
use src\api\controllers\MessageController;

use src\api\Helpers;

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file))
    require __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});


$jsondata = file_get_contents('php://input');
$request = isset($jsondata) && !empty($jsondata) ? @json_decode($jsondata, TRUE) : array();

if (isset($jsondata) && !empty($jsondata) && json_last_error() !== JSON_ERROR_NONE) {
    Helpers::returnToAction(Helpers::formatResponse(404, 'Incorrect JSON Format', []));
    return;
}


/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/

$router = new src\core\Router();

$router->any(RepositoryController::class . '::noActionFound');
$router->get('/', RepositoryController::class . '::indexAction');

$router->post('/decode', MessageController::class . '::decodeMessage');
$router->get('/status', MessageController::class . '::getStatus');
$router->get('/metrics', MessageController::class . '::getMetrics');


$router->run($request, $_SERVER['REQUEST_METHOD']);
