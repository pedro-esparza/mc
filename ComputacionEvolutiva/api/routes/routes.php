<?php

require_once '../controllers/MessageController.php';

function route($uri, $method)
{
    $controller = new MessageController();

    switch ($uri) {
        case '/decode':
            if ($method === 'POST') {
                $controller->decodeMessage();
            } else {
                throw new Exception('Método no permitido');
            }
            break;
        case '/status':
            if ($method === 'GET') {
                $controller->getStatus();
            } else {
                throw new Exception('Método no permitido');
            }
            break;
        case '/metrics':
            if ($method === 'GET') {
                $controller->getMetrics();
            } else {
                throw new Exception('Método no permitido');
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no encontrado']);
    }
}