<?php

declare(strict_types=1);

class Sphere extends Funcion
{
    public function calcular(array $genes): float
    {
        $sum = 0;
        foreach ($genes as $x) {
            $sum += $x ** 2;
        }
        return $sum;
    }
}
