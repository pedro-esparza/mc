<?php

declare(strict_types=1);

abstract class Funcion
{
    abstract public function calcular(array $genes): float;
}
