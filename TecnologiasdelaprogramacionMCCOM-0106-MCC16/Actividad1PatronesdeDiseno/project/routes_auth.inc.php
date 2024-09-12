<?php

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
use src\auth\controllers\authController;

$router->post('/auth/login', authController::class . '::login');
$router->post('/auth/register', authController::class . '::register');
$router->patch('/auth/refresh', authController::class . '::refresh');
$router->post('/auth/forgot', authController::class . '::forgot');
$router->delete('/auth/logout', authController::class . '::logout');


/*
|--------------------------------------------------------------------------
| Wizard
|--------------------------------------------------------------------------
*/
use src\wizard\wizardController;

$router->post('/wizard', wizardController::class . '::index');