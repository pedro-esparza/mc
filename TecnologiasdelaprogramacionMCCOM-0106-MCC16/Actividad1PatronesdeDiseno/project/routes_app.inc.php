<?php

use src\app\controllers\enteController;

$router->get('/ente', enteController::class . '::getAll');
$router->get('/ente/filtered', enteController::class . '::getAllFiltered');
$router->get('/ente/listByColumn', enteController::class . '::getAllDataByColumn');
$router->get('/ente/getone', enteController::class . '::getOneById');
$router->post('/ente', enteController::class . '::store');
$router->put('/ente', enteController::class . '::update');
$router->patch('/ente', enteController::class . '::modify');
$router->delete('/ente', enteController::class . '::hardDelete');