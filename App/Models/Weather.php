<?php

namespace App\Models;

use PDO;

/**
 * Location Model
 *
 * PHP version 8.3
 */
class Weather extends \Core\Model
{

    /**
     * Update the location table with bin dates
     *
     * @param string $table  Location table
     * @param array $data  Associative array of data to insert
     *
     * @return void
     */
    public static function updateWeather($data, $weather_codes_descriptions, $precipitation_type_descriptions)
    {
        try {
            $db = static::getDB();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }

        $stmt = "TRUNCATE TABLE weather";
        $db->exec($stmt);
        
        
        for($i = 0; $i<count($data->data->timelines[0]->intervals); $i++) {

            $weather_date = substr($data->data->timelines[0]->intervals[$i]->startTime, 0, 10);
            $weather_code = $data->data->timelines[0]->intervals[$i]->values->weatherCodeDay;
            $weather_info = $weather_codes_descriptions[$weather_code];
            $temperature = trim(floor($data->data->timelines[0]->intervals[$i]->values->temperature)); //TINYINT in db
            $precipitation_type = $precipitation_type_descriptions[$data->data->timelines[0]->intervals[$i]->values->precipitationType];
            $precipitation_probability = $data->data->timelines[0]->intervals[$i]->values->precipitationProbability;
            $wind_speed = $data->data->timelines[0]->intervals[$i]->values->windSpeed;
            $modified_date_time = date("Y-m-d H:i:s"); 

            $stmt = "INSERT INTO weather (wdate, code, info, temperature, precipitation, probability, wind, modified)
            VALUES (";
            $stmt .= "'$weather_date', '$weather_code', '$weather_info', '$temperature', '$precipitation_type', 
                        '$precipitation_probability', '$wind_speed', '$modified_date_time'";
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
            $db->query("CREATE TABLE IF NOT EXISTS `$db_name`.`$table` (
                `id` TINYINT NOT NULL AUTO_INCREMENT , 
                `wdate` TINYTEXT NOT NULL , 
                `code` MEDIUMINT(4) NOT NULL , 
                `info` TINYTEXT NOT NULL , 
                `temperature` TINYINT NOT NULL , 
                `precipitation` TINYTEXT NOT NULL , 
                `probability` DECIMAL NOT NULL , 
                `wind` DECIMAL NOT NULL , 
                `modified` DATETIME NOT NULL , 
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
     * Get all posts as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        try {
            $db = static::getDB();

            $stmt = $db->query("SELECT wdate, code, info, temperature, precipitation, probability, wind, modified FROM weather");

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}
