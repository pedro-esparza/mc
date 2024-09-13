<?php

namespace src\wizard;

class factoryController
{
    public static function create($controllerName)
    {
        $controllerClass = "src\\wizard\\" . $controllerName;

        if (class_exists($controllerClass)) {
            return new $controllerClass();
        } else {
            throw new \Exception("Controller not found: " . $controllerName);
        }
    }
}
