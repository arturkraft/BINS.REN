<?php

namespace App\Controllers;

use App\Models\Weather;

/**
 * Weather
 *
 * PHP version 8.3
 */
class Weathers
{

    /**
     * Connect to to Tomorrow.io API and get the JSON data back
     *
     * @param string $api_location  Location for the API
     * @param string $api_key  API key
     *
     * @return array $data  decoded JSON data with weather information
     */
    private static function weatherGetJSON() {

        $api_key = \App\Config::TOMORROW_IO_API_KEY;
        $api_location = "40.75872069597532,-73.98529171943665";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.tomorrow.io/v4/timelines?location='.$api_location.'&fields=temperature,temperatureApparent,precipitationProbability,precipitationIntensity,precipitationType,weatherCodeDay,windSpeed&timesteps=1d&units=metric&apikey='.$api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));


        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response);

        if (isset($data->data->timelines[0]->intervals[0]->values->temperature)) {
            return $data;
        } else {
            return 0;
        }

    }

    /**
     * Generate weather
     *
     * @return void
     */
    public function updateWeatherTable()
    {
        $weather_codes_descriptions = array(
            "0"=>"Weather Unknown",
            "10000"=>"Clear, Sunny",
            "11000"=>"Mostly Clear",
            "11010"=>"Partly Cloudy",
            "11020"=>"Mostly Cloudy",
            "10010"=>"Cloudy",
            "11030"=>"Partly Cloudy and Mostly Clear",
            "21000"=>"Light Fog",
            "21010"=>"Mostly Clear and Light Fog",
            "21020"=>"Partly Cloudy and Light Fog",
            "21030"=>"Mostly Cloudy and Light Fog",
            "21060"=>"Mostly Clear and Fog",
            "21070"=>"Partly Cloudy and Fog",
            "21080"=>"Mostly Cloudy and Fog",
            "20000"=>"Fog",
            "42040"=>"Partly Cloudy and Drizzle",
            "42030"=>"Mostly Clear and Drizzle",
            "42050"=>"Mostly Cloudy and Drizzle",
            "40000"=>"Drizzle",
            "42000"=>"Light Rain",
            "42130"=>"Mostly Clear and Light Rain",
            "42140"=>"Partly Cloudy and Light Rain",
            "42150"=>"Mostly Cloudy and Light Rain",
            "42090"=>"Mostly Clear and Rain",
            "42080"=>"Partly Cloudy and Rain",
            "42100"=>"Mostly Cloudy and Rain",
            "40010"=>"Rain",
            "42110"=>"Mostly Clear and Heavy Rain",
            "42020"=>"Partly Cloudy and Heavy Rain",
            "42120"=>"Mostly Cloudy and Heavy Rain",
            "42010"=>"Heavy Rain",
            "51150"=>"Mostly Clear and Flurries",
            "51160"=>"Partly Cloudy and Flurries",
            "51170"=>"Mostly Cloudy and Flurries",
            "50010"=>"Flurries",
            "51000"=>"Light Snow",
            "51020"=>"Mostly Clear and Light Snow",
            "51030"=>"Partly Cloudy and Light Snow",
            "51040"=>"Mostly Cloudy and Light Snow",
            "51220"=>"Drizzle and Light Snow",
            "51050"=>"Mostly Clear and Snow",
            "51060"=>"Partly Cloudy and Snow",
            "51070"=>"Mostly Cloudy and Snow",
            "50000"=>"Snow",
            "51010"=>"Heavy Snow",
            "51190"=>"Mostly Clear and Heavy Snow",
            "51200"=>"Partly Cloudy and Heavy Snow",
            "51210"=>"Mostly Cloudy and Heavy Snow",
            "51100"=>"Drizzle and Snow",
            "51080"=>"Rain and Snow",
            "51140"=>"Snow and Freezing Rain",
            "51120"=>"Snow and Ice Pellets",
            "60000"=>"Freezing Drizzle",
            "60030"=>"Mostly Clear and Freezing drizzle",
            "60020"=>"Partly Cloudy and Freezing drizzle",
            "60040"=>"Mostly Cloudy and Freezing drizzle",
            "62040"=>"Drizzle and Freezing Drizzle",
            "62060"=>"Light Rain and Freezing Drizzle",
            "62050"=>"Mostly Clear and Light Freezing Rain",
            "62030"=>"Partly Cloudy and Light Freezing Rain",
            "62090"=>"Mostly Cloudy and Light Freezing Rain",
            "62000"=>"Light Freezing Rain",
            "62130"=>"Mostly Clear and Freezing Rain",
            "62140"=>"Partly Cloudy and Freezing Rain",
            "62150"=>"Mostly Cloudy and Freezing Rain",
            "60010"=>"Freezing Rain",
            "62120"=>"Drizzle and Freezing Rain",
            "62200"=>"Light Rain and Freezing Rain",
            "62220"=>"Rain and Freezing Rain",
            "62070"=>"Mostly Clear and Heavy Freezing Rain",
            "62020"=>"Partly Cloudy and Heavy Freezing Rain",
            "62080"=>"Mostly Cloudy and Heavy Freezing Rain",
            "62010"=>"Heavy Freezing Rain",
            "71100"=>"Mostly Clear and Light Ice Pellets",
            "71110"=>"Partly Cloudy and Light Ice Pellets",
            "71120"=>"Mostly Cloudy and Light Ice Pellets",
            "71020"=>"Light Ice Pellets",
            "71080"=>"Mostly Clear and Ice Pellets",
            "71070"=>"Partly Cloudy and Ice Pellets",
            "71090"=>"Mostly Cloudy and Ice Pellets",
            "70000"=>"Ice Pellets",
            "71050"=>"Drizzle and Ice Pellets",
            "71060"=>"Freezing Rain and Ice Pellets",
            "71150"=>"Light Rain and Ice Pellets",
            "71170"=>"Rain and Ice Pellets",
            "71030"=>"Freezing Rain and Heavy Ice Pellets",
            "71130"=>"Mostly Clear and Heavy Ice Pellets",
            "71140"=>"Partly Cloudy and Heavy Ice Pellets",
            "71160"=>"Mostly Cloudy and Heavy Ice Pellets",
            "71010"=>"Heavy Ice Pellets",
            "80010"=>"Mostly Clear and Thunderstorm",
            "80030"=>"Partly Cloudy and Thunderstorm",
            "80020"=>"Mostly Cloudy and Thunderstorm",
            "80000"=>"Thunderstorm"
        );

        $precipitation_type_descriptions = ['rain', 'rain', 'snow', 'freezing rain', 'sleet'];

        $data = $this->weatherGetJSON();


        $weather = new Weather();

        $weather -> updateWeather($data, $weather_codes_descriptions, $precipitation_type_descriptions);

      
    }




}
