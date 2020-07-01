<?php

namespace Core;

use App\Config;
use PDO;
use PDOException;

abstract class Model
{
    private static $db = null;

    protected static function getDB()
    {
        if (is_null(self::$db))
        {
            try
            {
                $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
                self::$db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

                // Throw an Exception when error occurs
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        return self::$db;
    }
}
