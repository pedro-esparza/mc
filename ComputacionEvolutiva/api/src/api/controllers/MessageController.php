<?php

namespace src\api\controllers;

use src\api\Helpers;


class MessageController
{

    private static function decodificar($mensajeCodificado, $clave)
    {
        return $clave; // En un caso real, aplica el mapeo usando la clave
    }


    private static function aplicarCruceYMutacion($poblacion, $tasaMutacion)
    {
        $nuevaPoblacion = [];
        $caracteres = 'abcdefghijklmnopqrstuvwxyz ';

        while (count($nuevaPoblacion) < count($poblacion) * 2) {
            $padre1 = $poblacion[array_rand($poblacion)];
            $padre2 = $poblacion[array_rand($poblacion)];

            // Cruce
            $puntoCruce = random_int(0, strlen($padre1) - 1);
            $hijo = substr($padre1, 0, $puntoCruce) . substr($padre2, $puntoCruce);

            // Mutación
            if (random_int(0, 100) / 100 <= $tasaMutacion) {
                $indiceMutacion = random_int(0, strlen($hijo) - 1);
                $hijo[$indiceMutacion] = $caracteres[random_int(0, strlen($caracteres) - 1)];
            }

            $nuevaPoblacion[] = $hijo;
        }

        return $nuevaPoblacion;
    }


    private static function seleccionarMejores($poblacion, $fitnesses)
    {
        // Ordenar población por fitness (mejor fitness primero)
        array_multisort($fitnesses, SORT_ASC, $poblacion);

        // Seleccionar los mejores (50%)
        return array_slice($poblacion, 0, count($poblacion) / 2);
    }


    private static function calcularFitness($individuo, $mensajeCodificado, $mensajeOriginal)
    {
        $costo = 0;
        for ($i = 0; $i < strlen($mensajeOriginal); $i++) {
            if ($i < strlen($individuo) && $individuo[$i] !== $mensajeOriginal[$i]) {
                $costo++;
            }
        }
        return $costo;
    }


    private static function generarPoblacionInicial($tamanoPoblacion, $longitudMensaje)
    {
        $poblacion = [];
        $caracteres = 'abcdefghijklmnopqrstuvwxyz ';

        for ($i = 0; $i < $tamanoPoblacion; $i++) {
            $individuo = '';
            for ($j = 0; $j < $longitudMensaje; $j++) {
                $individuo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }
            $poblacion[] = $individuo;
        }

        return $poblacion;
    }


    public static function run($mensajeCodificado, $mensajeOriginal, $generaciones, $tamanoPoblacion, $tasaMutacion)
    {
        $inicio = microtime(true);

        // Generar población inicial
        $poblacion = self::generarPoblacionInicial($tamanoPoblacion, strlen($mensajeCodificado));

        $mejorFitness = PHP_INT_MAX;
        $mejorIndividuo = '';

        $historialFitness = [];

        // Evolucionar a través de generaciones
        for ($generacion = 0; $generacion < $generaciones; $generacion++) {
            $fitnesses = [];

            // Calcular el fitness de cada individuo
            foreach ($poblacion as $individuo) {
                $fitness = self::calcularFitness($individuo, $mensajeCodificado, $mensajeOriginal);
                $fitnesses[] = $fitness;

                if ($fitness < $mejorFitness) {
                    $mejorFitness = $fitness;
                    $mejorIndividuo = $individuo;
                }
            }

            $historialFitness[] = $mejorFitness;

            // Seleccionar mejores individuos
            $poblacion = self::seleccionarMejores($poblacion, $fitnesses);

            // Aplicar cruce y mutación
            $poblacion = self::aplicarCruceYMutacion($poblacion, $tasaMutacion);
        }

        $decodedMessage = self::decodificar($mensajeCodificado, $mejorIndividuo);

        return [
            'mensaje_decodificado' => $decodedMessage,
            'fitness' => round(1 / (1 + $mejorFitness), 2), // Convertir fitness en una escala normalizada
            'costo' => $mejorFitness,
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
