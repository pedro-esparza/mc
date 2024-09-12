<?php

namespace src\core;

use src\core\helpers;

class router
{
    private static $_instance = null; // Instancia estática para el Singleton
    private $noAction;
    private array $controllers = []; // Asegurar que se inicializa como un array vacío

    // Constructor privado para evitar la creación directa de instancias
    private function __construct()
    {
    }

    // Evitar clonación de la instancia
    private function __clone()
    {
    }

    // Evitar deserialización de la instancia
    private function __wakeup()
    {
    }

    // Método para obtener la instancia Singleton de la clase router
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function any(callable $controller): void
    {
        $this->noAction = $controller;
    }

    public function get(string $path, callable $controller): void
    {
        $this->add('GET', $path, $controller);
    }

    public function post(string $path, callable $controller): void
    {
        $this->add('POST', $path, $controller);
    }

    public function put(string $path, callable $controller): void
    {
        $this->add('PUT', $path, $controller);
    }

    public function patch(string $path, callable $controller): void
    {
        $this->add('PATCH', $path, $controller);
    }

    public function delete(string $path, callable $controller): void
    {
        $this->add('DELETE', $path, $controller);
    }

    private function add(string $method, string $path, $controller): void
    {
        $this->controllers[$method . $path] = ['path' => $path, 'method' => $method, 'controller' => $controller];
    }

    public function run(array $request, string $requestMethod)
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $callback = null;

        foreach ($this->controllers as $controller) {
            if ($controller['path'] === $requestPath && $requestMethod === $controller['method']) {
                $callback = $controller['controller'];
                break;
            }
        }

        if (is_string($callback)) {
            $parts = explode('::', $callback);
            if (is_array($parts)) {
                $className = array_shift($parts);
                $controller = new $className;
                $callback = [$controller, array_shift($parts)];
            }
        } else {
            $callback = $this->noAction;
        }

        helpers::returnToAction(call_user_func_array($callback, [$request]));
    }
}
