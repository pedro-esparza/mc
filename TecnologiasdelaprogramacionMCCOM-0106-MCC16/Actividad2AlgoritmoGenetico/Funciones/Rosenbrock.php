<?php

declare(strict_types=1);

class Rosenbrock extends Funcion
{
    public function calcular(array $genes): float
    {
        $sum = 0;
        for ($i = 0; $i < count($genes) - 1; $i++) {
            $sum += 100 * pow(($genes[$i + 1] - $genes[$i] ** 2), 2) + pow(1 - $genes[$i], 2);
        }
        return $sum;
    }
}
