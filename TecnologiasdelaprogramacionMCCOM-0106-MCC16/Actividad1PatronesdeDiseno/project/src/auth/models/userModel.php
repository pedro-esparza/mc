<?php

namespace src\auth\models;

class userModel extends baseModel
{
    private static $table_name = 'user';
    protected $id;
    protected $email;
    protected $password;
    protected $fullname;
    protected $lastlogin;
    protected $status;
    protected $created_by;
    protected $modified_by;

    public static function getTableName()
    {
        return self::$table_name;
    }
}
