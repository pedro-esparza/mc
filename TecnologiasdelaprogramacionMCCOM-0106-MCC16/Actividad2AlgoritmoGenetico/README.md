# Algoritmo Genético en PHP con Optimización de Funciones Matemáticas

## Alumno
- **I.S.C. Pedro Esparza**
  - Matrícula: 9041301
  - Alumno: 23230221

## Universidad
- **TEcNM** - Instituto Tecnológico de Tijuana

## Materias del Posgrado en Ciencias de la Computación
- **Computación Evolutiva** (Ago-Dic 2024)
- **Tecnologías de la Programación** (MCCOM-0106 - MCC16)

---

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
path /Actividad2AlgoritmoGenetico
cd AlgoritmoGenetico
Paso 2: Instalar Dependencias
Asegúrate de que PHP y la extensión pthreads están instalados:

bash
Copiar código
sudo apt update
sudo apt install php php-pthreads
Paso 3: Ejecutar el Algoritmo
Para ejecutar el proyecto, simplemente usa el siguiente comando en el terminal:

bash
Copiar código
php index.php
Configuración en index.php
En el archivo index.php, puedes cambiar la función matemática que deseas optimizar. Por ejemplo, para cambiar de la función Ackley a Rosenbrock, modifica la línea donde se instancia la clase de la función:

Estructura del Proyecto
bash
Copiar código
AlgoritmoGenetico/
│
├── AlgoritmoGenetico.php     
├── Poblacion.php             
├── Individuo.php             
├── Funcion.php               
├── Funciones/                
│   ├── Ackley.php
│   ├── Rosenbrock.php
│   ├── Rastrigin.php
│   ├── Sphere.php
│   └── Griewank.php
└── index.php                 
Parámetros del Algoritmo
Población: 100 individuos
Generaciones: 1000
Tasa de Cruzamiento: 0.7
Tasa de Mutación: 0.01
Número de Hilos: Se ajusta según el número de núcleos de la CPU disponible (4 hilos por defecto).
Concurrencia con Hilos
El algoritmo utiliza hilos para ejecutar en paralelo la evaluación de la aptitud de los individuos. Esto mejora el rendimiento general, especialmente al trabajar con grandes poblaciones y múltiples generaciones.

Contribuciones
Las contribuciones al proyecto son bienvenidas. Si encuentras algún problema o deseas agregar nuevas características, no dudes en enviar un pull request o abrir un issue.

Licencia
Este proyecto está bajo la Licencia MIT. Para más detalles, revisa el archivo LICENSE.