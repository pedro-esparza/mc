<?php

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

use src\core\helpers;

$router->any(helpers::class . '::noActionFound');
$router->get('/', helpers::class . '::indexAction');



/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

use src\auth\controllers\authController;

$router->post('/auth/login', authController::class . '::login');
$router->post('/auth/register', authController::class . '::register');
$router->patch('/auth/refresh', authController::class . '::refresh');
$router->delete('/auth/logout', authController::class . '::logout');



/*
|-------------------------------------------------------------------------- 
| Wizard
|-------------------------------------------------------------------------- 
*/

use src\wizard\factoryController;

$router->post('/wizard', function () {

    try {
        $controller = factoryController::create('wizardController');
        return $controller->index();

    } catch (\Exception $e) {
        return json_encode([
            'status' => 500,
            'message' => 'Error creating controller: ' . $e->getMessage()
        ]);
    }
});