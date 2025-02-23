<?php

namespace App\Models;

use PDO;

/**
 * Location Model
 *
 * PHP version 8.3
 */
class Location extends \Core\Model
{

    /**
     * Update the location table with bin dates
     *
     * @param string $table  Location table
     * @param array $data  Associative array of data to insert
     *
     * @return void
     */
    public static function updateBinsSchedule($table, $data)
    {

        try {
            $db = static::getDB();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }

        $stmt = "TRUNCATE TABLE $table";
        $db->exec($stmt);

        for ($i = 0; $i < count($data['brown']); $i++) {
            $stmt = "INSERT INTO $table (brown, blue, green, grey)
        VALUES (";
            $brown = $data['brown'][$i];
            $blue = $data['blue'][$i];
            $green = $data['green'][$i];
            $grey = $data['grey'][$i];
            $stmt .= "'$brown', '$blue', '$green', '$grey'";
            $stmt .= ")";
            $db->exec($stmt);
            
        }

    }

    /**
     * Check if $table exists and create it if it doesn't
     *
     * @param string $table  table name for checking
     *
     * @return string $message
     */
    public static function conditionallyCreateTable($table)
    {

        $table_exists = true;
        
        $db_name = \App\Config::DB_NAME;

        try {
            $db = static::getDB();
            $db->query("CREATE TABLE IF NOT EXISTS `$db_name`.`$table` 
            ( `id` SMALLINT NOT NULL AUTO_INCREMENT , 
            `brown` TINYTEXT NOT NULL , 
            `blue` TINYTEXT NOT NULL , 
            `green` TINYTEXT NOT NULL , 
            `grey` TINYTEXT NOT NULL , 
            PRIMARY KEY (`id`)) 
            ;");
            $message = "table $table created";
        } catch(PDOException $e) {
            echo $e->getMessage();
            $message = "table $table already exists, no table created";
        }


        return $message;
    }

    /**
     * Get all bin dates as an associative array
     *
     * @return array
     */
    public static function getAll($table)
    {
        try {
            $db = static::getDB();

            $stmt = $db->query("SELECT brown, blue, green, grey FROM $table");

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}
