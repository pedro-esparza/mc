<?php

declare(strict_types=1);

class Rastrigin extends Funcion
{
    public function calcular(array $genes): float
    {
        $n = count($genes);
        $A = 10;
        $sum = 0;

        foreach ($genes as $x) {
            $sum += $x ** 2 - $A * cos(2 * M_PI * $x);
        }

        return $A * $n + $sum;
    }
}
