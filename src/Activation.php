<?php

namespace BaseSpeedDigital\InflationCalculator;

class Activation {
    public static function activate()
    {
        //check if this plugin is being installed for first time
        if( get_option( "bspdi_inflation_options" ) === FALSE ) {
            add_option( "bspdi_inflation_options", array( "inflation_rate" => "3" ) );
        }
    }
}