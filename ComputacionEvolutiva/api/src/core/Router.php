<?php

namespace src\core;

use src\api\Helpers;

class Router
{
    private $noAction;
    private array $controllers;

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
        $this->controllers[$method . $path] = [
            'path' => $path,
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function run(array $request, string $requestMethod)
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $callback = null;

        foreach ($this->controllers as $controller)
            if ($controller['path'] === $requestPath && $requestMethod === $controller['method'])
                $callback = $controller['controller'];

        if (is_string($callback)) {
            $parts = explode('::', $callback);
            if (is_array(($parts))) {
                $className = array_shift($parts);
                $controller = new $className;
                $callback = [$controller, array_shift($parts)];
            }
        } else
            $callback = $this->noAction;

        Helpers::returnToAction(call_user_func_array($callback, [$request]));
    }
}
