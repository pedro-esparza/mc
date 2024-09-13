<?php

namespace src\wizard;

use src\core\helpers;

class wizardController
{
    private static $filename = __DIR__ . '/mccsystem.sql';

    public function index()
    {
        $conn = db::connect();

        if (!$conn) {
            return helpers::formatResponse(500, 'Database connection error', []);
        }

        $return = helpers::formatResponse(201, 'Not registered ' . db::get('WDB_NAME') . ' at: ' . db::get('WDB_HOST'), []);

        if (file_exists(self::$filename)) {
            $query = file_get_contents(self::$filename);

            if ($conn->multi_query($query)) {
                $return = helpers::formatResponse(200, 'Wizard complete!, DB: ' . db::get('WDB_NAME') . ', Host: ' . db::get('WDB_HOST'), []);
            } else {
                $return = helpers::formatResponse(500, 'Error executing query: ' . $conn->error, []);
            }

            $conn->close();
        } else {
            $return = helpers::formatResponse(404, 'SQL file not found: ' . self::$filename, []);
        }

        return $return;
    }
}
