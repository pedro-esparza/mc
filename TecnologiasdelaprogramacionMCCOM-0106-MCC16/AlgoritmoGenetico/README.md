## Alumno
- **I.S.C. Pedro Esparza**
  - Matrícula: 9041301
  - Alumno: 23230221

## Universidad
- **TEcNM** - Instituto Tecnológico de Tijuana

## Materias del Posgrado en Ciencias de la Computación
- **Computación Evolutiva** (Ago-Dic 2024)
- **Tecnologías de la Programación** (MCCOM-0106 - MCC16)

# Algoritmo Genético en PHP con Optimización de Funciones Matemáticas

Este proyecto implementa un **algoritmo genético** en PHP para optimizar cinco funciones matemáticas de benchmark utilizando **concurrencia** con hilos mediante la extensión `pthreads`. El algoritmo puede ejecutarse en paralelo para mejorar la eficiencia y reducir el tiempo de procesamiento en la búsqueda de soluciones óptimas.

## Funciones Matemáticas Optimizadas

El algoritmo optimiza las siguientes funciones de benchmark:
- **Ackley**
- **Rosenbrock**
- **Rastrigin**
- **Sphere**
- **Griewank**

## Características

- **Concurrencia**: Uso de la extensión `pthreads` para distribuir la evaluación de la población entre múltiples hilos, mejorando el rendimiento.
- **Programación Orientada a Objetos (OOP)**: El código está organizado usando buenas prácticas de OOP con tipado estricto.
- **Configuración Personalizable**: Ajuste de parámetros como el tamaño de la población, el número de generaciones, tasas de mutación y cruzamiento.

## Requisitos

- **PHP** >= 7.4
- Extensión **pthreads**
- **Ubuntu** o cualquier sistema basado en Linux compatible con `pthreads`.
- **Visual Studio Code** (Opcional, para desarrollo)
- **Nginx** o **PHP CLI** para ejecutar el código.

## Instalación

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/pedro-esparza/MCC/tree/main/TecnologiasdelaprogramacionMCCOM-0106-MCC16
cd AlgoritmoGenetico
