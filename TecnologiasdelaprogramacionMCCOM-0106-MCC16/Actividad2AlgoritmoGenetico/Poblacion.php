<?php

declare(strict_types=1);

class Poblacion
{
    private array $individuos;

    public function __construct(array $individuos)
    {
        $this->individuos = $individuos;
    }

    public function evolucionar(float $crossoverRate, float $mutationRate): void
    {
        $nuevaGeneracion = [];

        // Realizar el cruzamiento para generar nuevos individuos
        for ($i = 0; $i < count($this->individuos); $i += 2) {
            $padre1 = $this->seleccionarIndividuo();
            $padre2 = $this->seleccionarIndividuo();

            if (mt_rand() / mt_getrandmax() < $crossoverRate) {
                $hijos = $this->realizarCruzamiento($padre1, $padre2);
            } else {
                // Si no cruzamos, los padres pasan a la nueva generación sin cambios
                $hijos = [$padre1, $padre2];
            }

            foreach ($hijos as $hijo) {
                // Mutar al nuevo individuo con una probabilidad
                if (mt_rand() / mt_getrandmax() < $mutationRate) {
                    $hijo = $this->realizarMutacion($hijo);
                }
                $nuevaGeneracion[] = $hijo;
            }
        }

        $this->individuos = $nuevaGeneracion;
    }

    private function seleccionarIndividuo(): Individuo
    {
        // Selección aleatoria para este ejemplo, pero podrías usar selección por torneo o ruleta
        return $this->individuos[array_rand($this->individuos)];
    }

    private function realizarCruzamiento(Individuo $padre1, Individuo $padre2): array
    {
        $genes1 = $padre1->getGenes();
        $genes2 = $padre2->getGenes();
        $puntoCruzamiento = mt_rand(0, count($genes1) - 1);

        $hijo1Genes = array_merge(array_slice($genes1, 0, $puntoCruzamiento), array_slice($genes2, $puntoCruzamiento));
        $hijo2Genes = array_merge(array_slice($genes2, 0, $puntoCruzamiento), array_slice($genes1, $puntoCruzamiento));

        return [new Individuo($hijo1Genes), new Individuo($hijo2Genes)];
    }

    private function realizarMutacion(Individuo $individuo): Individuo
    {
        $genes = $individuo->getGenes();
        $indice = mt_rand(0, count($genes) - 1);

        // Mutación simple, alteramos el gen en un pequeño valor aleatorio
        $genes[$indice] += (mt_rand() / mt_getrandmax()) * 2 - 1;  // Variación aleatoria entre -1 y 1

        $individuo->setGenes($genes);
        return $individuo;
    }

    public function getFittest(): Individuo
    {
        usort($this->individuos, fn($a, $b) => $b->getAptitud() <=> $a->getAptitud());
        return $this->individuos[0];
    }
}
