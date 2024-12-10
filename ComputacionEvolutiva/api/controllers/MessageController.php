<?php

require_once '../models/GeneticAlgorithm.php';
require_once '../views/JsonResponse.php';
require_once '../utils/Validator.php';


class MessageController
{
    public function decodeMessage()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        Validator::validateDecodeRequest($input);

        $algorithm = new GeneticAlgorithm();
        $result = $algorithm->run(
            $input['mensaje_codificado'],
            $input['mensaje_original'],
            $input['generaciones'],
            $input['tamano_poblacion'],
            $input['tasa_mutacion']
        );

        JsonResponse::send($result);
    }

    public function getStatus()
    {
        $status = [
            'status' => 'OK',
            'uptime' => '2 days, 5 hours',
            'requests_handled' => 1200
        ];

        JsonResponse::send($status);
    }

    public function getMetrics()
    {
        // Simulación de datos de métricas
        $metrics = [
            [
                'mensaje_codificado' => 'qnnzo yqtdn',
                'mensaje_decodificado' => 'hello world',
                'fitness' => 0.95,
                'tiempo_ejecucion' => '0.75s'
            ]
        ];

        JsonResponse::send($metrics);
    }
}
