<?php

namespace src\core;

class helpers
{
    public static function getDomainName($endpoint)
    {
        return 'http://mccsystem' . $endpoint;
    }    

    public static function indexAction(): array
    {
        return self::formatResponse(200, '¡Desarrollemos algo increible!', []);
    }

    public static function noActionFound(): array
    {
        return self::formatResponse(404, '¡Acción No encontrada!', []);
    }

    public static function dye($value): void
    {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
        exit(1);
    }

    public static function formatResponse($status, $message, $data = []): array
    {
        return [
            'status' => intval($status),
            'message' => $message,
            'data' => $data
        ];
    }

    public static function returnToAction(array $response): void
    {
        echo json_encode($response, http_response_code($response['status']));
    }

}