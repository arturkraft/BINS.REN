<?php

namespace App\Controllers;

/**
 * Generator
 *
 * PHP version 8.3
 */
class Generator
{

    /**
     * Generate dates
     *
     * @param array $frequency_and_start_date  Associative array with bins' start dates and frequency
     * @param int $number_of_rows  The bin colour blue, brown, green, grey
     * @param string $end_date  The end date we want the bin dates up to
     *
     * @return array $bin_dates  Associative array with all bin dates per bin
     */
    public function generateDates($frequency_and_start_date, $number_of_rows, $end_date)
    {
        if (\App\Config::ALLOW_GENERATING) {
            $bin_dates=array(
                'brown'=>array($frequency_and_start_date['brown'][0]), 
                'blue'=>array($frequency_and_start_date['blue'][0]), 
                'green'=>array($frequency_and_start_date['green'][0]), 
                'grey'=>array($frequency_and_start_date['grey'][0])
                );

            foreach ($bin_dates as $bin => $bin_start_date) {
                $incremented_date = $bin_start_date[0];

                for ($i = 0; $i < $number_of_rows; $i++){ 
                    $incremented_date = strtotime($incremented_date . ' + ' . $frequency_and_start_date[$bin][1] . ' weeks');
                    $incremented_date = date("Y-m-d", $incremented_date);
                    //$end_date = $incremented_date; //prioritising number of rows above end date
                    if (strtotime($end_date) >= strtotime($incremented_date)) {
                        array_push($bin_dates[$bin], $incremented_date);
                    } else {
                        if ($bin == "brown") {
                            $number_of_rows = $i;
                            break;
                        } else {
                            array_push($bin_dates[$bin], null);
                        }
                        
                    }      

                }
            }
            
            return $bin_dates; 
        }
    }

}
