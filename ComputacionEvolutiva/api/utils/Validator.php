<?php

class Validator
{
    public static function validateDecodeRequest($input)
    {
        if (!isset($input['mensaje_codificado'], $input['mensaje_original'], $input['generaciones'], $input['tamano_poblacion'], $input['tasa_mutacion'])) {
            throw new Exception('Parámetros de entrada incompletos');
        }

        if (!is_string($input['mensaje_codificado']) || !is_string($input['mensaje_original'])) {
            throw new Exception('Los mensajes deben ser cadenas de texto');
        }

        if (!is_int($input['generaciones']) || $input['generaciones'] <= 0) {
            throw new Exception('El número de generaciones debe ser un entero positivo');
        }

        if (!is_int($input['tamano_poblacion']) || $input['tamano_poblacion'] <= 0) {
            throw new Exception('El tamaño de la población debe ser un entero positivo');
        }

        if (!is_float($input['tasa_mutacion']) || $input['tasa_mutacion'] < 0 || $input['tasa_mutacion'] > 1) {
            throw new Exception('La tasa de mutación debe ser un valor entre 0 y 1');
        }
    }
}