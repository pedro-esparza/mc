<?php

namespace DesignPatternsAPI;

use src\core\helpers;
use src\core\router;

/*
|--------------------------------------------------------------------------|
| Turn On The Lights                                                       |
|--------------------------------------------------------------------------|
*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header("HTTP/1.1 200 OK");
  exit();
}

date_default_timezone_set('America/Tijuana');

spl_autoload_register(function ($class) {
  $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
  if (is_readable($file))
    require __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});

$jsondata = file_get_contents('php://input');
$request = isset($jsondata) && !empty($jsondata) ? @json_decode($jsondata, TRUE) : array();

if (!empty($jsondata) && json_last_error() !== JSON_ERROR_NONE)
  return helpers::returnToAction(helpers::formatResponse(404, 'Incorrect JSON Format', []));

/*
|--------------------------------------------------------------------------|
| Routes                                                                   |
|--------------------------------------------------------------------------|
*/

// Aquí usamos Singleton
$router = router::getInstance();

require 'src/core/routes.php';


/*
|--------------------------------------------------------------------------|
| Launch                                                                   |
|--------------------------------------------------------------------------|
*/

$router->run($request, $_SERVER['REQUEST_METHOD']);
$router = null;
