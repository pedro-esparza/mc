<?php

declare(strict_types=1);

class AlgoritmoGenetico
{
    private Poblacion $poblacion;
    private int $generaciones;
    private float $crossoverRate;
    private float $mutationRate;
    private int $numThreads;

    public function __construct(Poblacion $poblacion, int $generaciones, float $crossoverRate, float $mutationRate, int $numThreads)
    {
        $this->poblacion = $poblacion;
        $this->generaciones = $generaciones;
        $this->crossoverRate = $crossoverRate;
        $this->mutationRate = $mutationRate;
        $this->numThreads = $numThreads;
    }

    public function run(Funcion $funcion): Individuo
    {
        for ($i = 0; $i < $this->generaciones; $i++) {
            $this->evaluarFitness($funcion);
            $this->poblacion->evolucionar($this->crossoverRate, $this->mutationRate);
        }
        return $this->poblacion->getFittest();
    }

    private function evaluarFitness(Funcion $funcion): void
    {
        $evaluator = new ThreadedFitnessEvaluator($this->poblacion, $funcion, $this->numThreads);
        $evaluator->evaluate();
    }
}
