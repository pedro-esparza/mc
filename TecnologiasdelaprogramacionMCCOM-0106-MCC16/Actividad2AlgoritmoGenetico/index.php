<?php

/*********************************************************************************************

    I.S.C. Pedro Esparza 9041301, Tecnologias de la programación MCCOM-0106 - MCC16, 02 Sept 2024
    
    1. Abre la terminal integrada en VSCode.
    2. Navega al directorio donde se encuentra este archivo utilizando el comando 'cd'.
    3. Ejecuta el comando php index.php para ver la salida.
    4. Disfruta la magia

    Requisitos de instalación
    sudo apt install php php-cli php-pthreads

    Verificar la instalación de PHP y pthreads:
    php -m | grep pthreads

    Descripción
        AlgoritmoGenetico.php: Controlará el flujo general del algoritmo.
        Poblacion.php: Maneja la población de individuos.
        Individuo.php: Define los individuos y su aptitud.
        Funcion.php: Interfaz para las funciones matemáticas de benchmark.
        Funciones/: Directorio donde implementaremos las funciones matemáticas específicas.

    Scaffolding
        AlgoritmoGenetico/
        │
        ├── AlgoritmoGenetico.php
        ├── Poblacion.php
        ├── Individuo.php
        ├── Funcion.php
        ├── Funciones/ (directorio)
        │   ├── Ackley.php
        │   ├── Rosenbrock.php
        │   ├── Rastrigin.php
        │   ├── Sphere.php
        │   └── Griewank.php
        └── index.php

    Notas de uso:
    Ajustar los parámetros para ver cómo afectan los resultados, ej:
        el tamaño de la población
        el número de generaciones        
        o las tasas de cruzamiento y mutación
*********************************************************************************************/

require_once 'AlgoritmoGenetico.php';
require_once 'Poblacion.php';
require_once 'Individuo.php';
require_once 'Funcion.php';
require_once 'Funciones/Ackley.php';
require_once 'Funciones/Rosenbrock.php';
require_once 'Funciones/Rastrigin.php';
require_once 'Funciones/Sphere.php';
require_once 'Funciones/Griewank.php';

// Generar población inicial con 100 individuos, cada uno con 10 genes
$poblacion = new Poblacion(array_map(fn() => new Individuo(array_fill(0, 10, mt_rand(-5, 5))), range(0, 99)));

$algoritmo = new AlgoritmoGenetico($poblacion, 1000, 0.7, 0.01, 4);  // 4 hilos

// Cambia la función aquí para probar otras
$mejorIndividuo = $algoritmo->run(new Ackley());  // Puedes cambiar por Rosenbrock(), Rastrigin(), Sphere(), Griewank()

echo "La mejor solución encontrada tiene una aptitud de: " . $mejorIndividuo->getAptitud();
echo "\nGenes: " . implode(", ", $mejorIndividuo->getGenes()) . "\n";

