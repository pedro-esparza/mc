<?php

declare(strict_types=1);

class Ackley extends Funcion
{
    public function calcular(array $genes): float
    {
        $a = 20;
        $b = 0.2;
        $c = 2 * M_PI;

        $n = count($genes);
        $sum1 = array_sum(array_map(fn($x) => $x ** 2, $genes));
        $sum2 = array_sum(array_map(fn($x) => cos($c * $x), $genes));

        $term1 = -$a * exp(-$b * sqrt($sum1 / $n));
        $term2 = -exp($sum2 / $n);

        return $term1 + $term2 + $a + exp(1);
    }
}
