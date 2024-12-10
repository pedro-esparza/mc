# API PARA DECODIFICACIÓN DE MENSAJES CON ALGORITMOS GENÉTICOS

**Autor:** Pedro Alberto Esparza Garza  
**Matrícula:** 9041301  
**Curso:** Computación Evolutiva  
**Profesor:** Dr. Fevrier Adolfo Valdez Acosta  
**Institución:** TecNM, Instituto Tecnológico de Tijuana  
**Fecha:** Diciembre 2024  


## Descripción General

La **API para Decodificación de Mensajes con Algoritmos Genéticos** es una herramienta diseñada para resolver problemas de decodificación utilizando técnicas de optimización basadas en algoritmos genéticos. Este proyecto aprovecha la capacidad de los algoritmos evolutivos para encontrar claves de decodificación eficientes, explorando múltiples soluciones simultáneamente.

La API es capaz de manejar solicitudes concurrentes, calcular métricas de rendimiento y brindar un historial detallado del proceso evolutivo.


## Características Principales

- **Desarrollo con PHP 8.4**: Implementación nativa para control total y personalización.
- **Algoritmo Genético**: Decodificación eficiente a través de selección, cruce y mutación.
- **Endpoints RESTful**: Interacción clara y estándar con los clientes.
- **Métricas Detalladas**: Fitness, costo, tiempo de ejecución y uso de recursos.
- **Escalabilidad**: Diseño modular basado en MVC para fácil mantenimiento y extensibilidad.


## Requisitos del Sistema

1. **Servidor Web**: Apache o Nginx.
2. **PHP**: Versión 8.4 o superior.
3. **Base de Datos**: MySQL (opcional, dependiendo de las métricas a almacenar).
4. **Herramientas de Prueba**: Postman, PHPUnit.


## Estructura del Proyecto

/api
    /controllers       # Controladores para manejar lógica de negocio.
        MessageController.php
    /models            # Modelos para implementar la lógica del algoritmo genético.
        GeneticAlgorithm.php
    /routes            # Definición de rutas.
        routes.php
    /views             # Gestión de respuestas en formato JSON.
        JsonResponse.php
    /utils             # Validadores y utilidades.
        Validator.php
    index.php          # Punto de entrada principal.
    config.php         # Configuración general del sistema.


## Instalación

Clonar el repositorio:
git clone <[url-del-repositorio](https://github.com/pedro-esparza/MCC/tree/main/ComputacionEvolutiva/api)>

cd <api>

## Configurar el entorno

Asegúrate de que config.php contenga las configuraciones correctas para tu entorno (base de datos, parámetros predeterminados, etc.).
Configurar el servidor web:

En Apache, agrega una entrada en tu archivo httpd.conf o crea un archivo de configuración en /etc/apache2/sites-available/:

<VirtualHost *:80>
    DocumentRoot "/ruta/a/tu/proyecto"
    ServerName localhost
</VirtualHost>

Reinicia el servidor:

sudo systemctl restart apache2


## Usa Postman para probar el endpoint /status:
arduino
Copiar código
GET http://localhost/status
Uso de la API
Endpoints Disponibles
POST /decode

Descripción: Decodifica un mensaje utilizando un algoritmo genético.
Entrada:

{
  "mensaje_codificado": "qnnzo yqtdn",
  "mensaje_original": "hello world",
  "generaciones": 50,
  "tamano_poblacion": 20,
  "tasa_mutacion": 0.05
}

Salida:

{
  "mensaje_decodificado": "hello world",
  "fitness": 0.95,
  "costo": 2,
  "historial_fitness": [50, 30, 10, 2],
  "tiempo_ejecucion": "0.75s",
  "uso_recursos": {
    "cpu": "65%",
    "memoria": "100MB"
  }
}

GET /status

Descripción: Verifica el estado del servidor.
Salida:

{
  "status": "OK",
  "uptime": "2 days, 5 hours",
  "requests_handled": 1200
}
GET /metrics

Descripción: Devuelve métricas de las ejecuciones recientes.
Salida:

[
  {
    "mensaje_codificado": "qnnzo yqtdn",
    "mensaje_decodificado": "hello world",
    "fitness": 0.95,
    "tiempo_ejecucion": "0.75s"
  }
]

## Licencia
Este proyecto se encuentra bajo la licencia MIT.