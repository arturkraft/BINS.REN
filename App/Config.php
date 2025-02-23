<?php

namespace App;


/**
 * Application configuration
 *
 * PHP version 8.3
 */
class Config
{
    /**
     * Database host
     *
     * @var string
     */
    const DB_HOST = "";

    /**
     * Database name
     *
     * @var string
     */
    const DB_NAME = "";

    /**
     * Database user
     *
     * @var string
     */
    const DB_USER = "";

    /**
     * Database user's password
     *
     * @var string
     */
    const DB_PASSWORD = "";
    
    /**
     * Show or hide error messages on screen
     * 
     * @var boolean
     */
    const SHOW_ERRORS = false;

    /**
     * Enable or disable dates generator (should be disabled most of the time)
     * 
     * @var boolean
     */
    const ALLOW_GENERATING = true;

    /**
     * Tomorrow.io API key
     * 
     * @var string
     */
    const TOMORROW_IO_API_KEY = "";

    /**
     * All locations
     * 
     * @var array
     */
    const LOCATIONS = [
        "bridgeofweir",
        "brookfield",
        "brooklands",
        "coatsbrae",
        "crosslee",
        "houston",
        "howwood",
        "kilbarchan",
        "langbank",
        "napiergrove",
        "weirswynd"
    ];

     /**
     * All locations long name
     * 
     * @var array
     */
    const LOCATIONS_LONG = [
        "bridgeofweir" => "Bridge of Weir",
        "brookfield" => "Brookfield",
        "brooklands" => "Brooklands",
        "coatsbrae" => "Coatsbrae",
        "crosslee" => "Crosslee",
        "houston" => "Houston",
        "howwood" => "Howwood",
        "kilbarchan" => "Kilbarchan",
        "langbank" => "Langbank",
        "linwood" => "Linwood",
        "napiergrove" => "Napier Grove",
        "weirswynd" => "Weirs Wynd"
    ];

    /**
     * Root URL
     * 
     * @var string
     */
    const BASE_URL = "https://bins.ren";
}
