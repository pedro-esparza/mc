<?php

namespace src\core;

use src\core\helpers;

class router
{
    private static $_instance = null;
    private $noAction;
    private array $controllers = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(): object|null
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
        $requestMethod = strtoupper($requestMethod);
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
            if (count($parts) === 2) {
                $className = array_shift($parts);
                $methodName = array_shift($parts);

                if (class_exists($className) && method_exists($className, $methodName)) {
                    $controllerInstance = new $className;
                    $callback = [$controllerInstance, $methodName];
                } else {
                    $callback = null;
                }
            }
        }

        if ($callback === null) {
            if ($this->noAction !== null) {
                $callback = $this->noAction;
            } else {
                helpers::returnToAction($this->default404());
                return;
            }
        }

        helpers::returnToAction(call_user_func_array($callback, [$request]));
    }

    private function default404()
    {
        return [
            'status' => 404,
            'message' => 'Route not found'
        ];
    }
}
