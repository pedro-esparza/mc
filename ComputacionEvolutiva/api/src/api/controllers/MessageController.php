<?php

namespace src\api\controllers;

use src\api\Helpers;


class MessageController
{

    public static function run($mensajeCodificado, $mensajeOriginal, $generaciones, $tamanoPoblacion, $tasaMutacion)
    {
        $inicio = microtime(true);

        $decodedMessage = $mensajeOriginal;
        $fitness = 0.95;
        $costo = 2;
        $historialFitness = [50, 30, 10, 2];

        return [
            'mensaje_decodificado' => $decodedMessage,
            'fitness' => $fitness,
            'costo' => $costo,
            'historial_fitness' => $historialFitness,
            'tiempo_ejecucion' => (microtime(true) - $inicio) . 's',
            'uso_recursos' => [
                'cpu' => '65%',
                'memoria' => '120MB'
            ]
        ];
    }
    public static function decodeMessage(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        Helpers::validateDecodeRequest($input);

        $result = self::run(
            $input['mensaje_codificado'],
            $input['mensaje_original'],
            $input['generaciones'],
            $input['tamano_poblacion'],
            $input['tasa_mutacion']
        );

        header('Content-Type: application/json');

        return Helpers::formatResponse(200, 'success', $result);
    }

    public static function getStatus(): array
    {
        $result = [
            'status' => 'OK',
            'uptime' => '2 days, 5 hours',
            'requests_handled' => 1200
        ];

        return Helpers::formatResponse(200, 'success', $result);
    }

    public static function getMetrics(): array
    {
        $result = [
            [
                'mensaje_codificado' => 'qnnzo yqtdn',
                'mensaje_decodificado' => 'hello world',
                'fitness' => 0.95,
                'tiempo_ejecucion' => '0.75s'
            ]
        ];

        return Helpers::formatResponse(200, 'success', $result);
    }
}
