<?php

namespace src\api;

class Helpers
{
    public static array $modules = ['category', 'customer', 'supplier', 'item'];

    public static function validModule($module): bool
    {
        return in_array($module, self::$modules);
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
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function returnToAction(array $response): void
    {
        echo 'okas';
        echo json_encode($response, http_response_code($response['status']));
    }

    public static function getFirstKeyName(array $array)
    {
        $keys = array_keys($array);
        return $keys[0];
    }

    public static function getFileAsArray($filename)
    {
        $fd = fopen($filename, 'r');
        $jsonData = fread($fd, filesize($filename));
        fclose($fd);

        return json_decode($jsonData, TRUE);
        ;
    }

    public static function validateDecodeRequest($input)
    {
        if (!isset($input['mensaje_codificado'], $input['mensaje_original'], $input['generaciones'], $input['tamano_poblacion'], $input['tasa_mutacion'])) {
            throw new \Exception('Parámetros de entrada incompletos');
        }

        if (!is_string($input['mensaje_codificado']) || !is_string($input['mensaje_original'])) {
            throw new \Exception('Los mensajes deben ser cadenas de texto');
        }

        if (!is_int($input['generaciones']) || $input['generaciones'] <= 0) {
            throw new \Exception('El número de generaciones debe ser un entero positivo');
        }

        if (!is_int($input['tamano_poblacion']) || $input['tamano_poblacion'] <= 0) {
            throw new \Exception('El tamaño de la población debe ser un entero positivo');
        }

        if (!is_float($input['tasa_mutacion']) || $input['tasa_mutacion'] < 0 || $input['tasa_mutacion'] > 1) {
            throw new \Exception('La tasa de mutación debe ser un valor entre 0 y 1');
        }
    }
}
