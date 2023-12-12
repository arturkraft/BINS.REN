<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base Model
 *
 * PHP version 8.3
 */
abstract class Model
{

    /**
     * Get PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . 
            ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            //Throw an exception when error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
