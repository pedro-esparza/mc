<?php

namespace src\api\controllers;

use \src\api\Helpers;

class RepositoryController
{
    public static function noActionFound(): array
    {
        return Helpers::formatResponse(404, 'No Action Found!', []);
    }

    public static function indexAction(): array
    {
        return Helpers::formatResponse(200, 'Let\'s start build something Incredible!', []);
    }
}
