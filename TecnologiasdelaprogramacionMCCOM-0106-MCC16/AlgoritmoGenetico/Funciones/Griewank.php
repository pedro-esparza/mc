<?php

declare(strict_types=1);

class Griewank extends Funcion
{
    public function calcular(array $genes): float
    {
        $sum = 0;
        $prod = 1;

        for ($i = 0; $i < count($genes); $i++) {
            $sum += $genes[$i] ** 2 / 4000;
            $prod *= cos($genes[$i] / sqrt($i + 1));
        }

        return $sum - $prod + 1;
    }
}
