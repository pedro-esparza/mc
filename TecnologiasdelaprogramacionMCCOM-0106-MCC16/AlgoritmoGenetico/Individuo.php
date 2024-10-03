<?php

declare(strict_types=1);

class Individuo
{
    private array $genes;
    private float $aptitud;

    public function __construct(array $genes)
    {
        $this->genes = $genes;
        $this->aptitud = 0;
    }

    public function calcularAptitud(Funcion $funcion): void
    {
        $this->aptitud = $funcion->calcular($this->genes);
    }

    public function getAptitud(): float
    {
        return $this->aptitud;
    }

    public function getGenes(): array
    {
        return $this->genes;
    }

    public function setGenes(array $genes): void
    {
        $this->genes = $genes;
    }
}
