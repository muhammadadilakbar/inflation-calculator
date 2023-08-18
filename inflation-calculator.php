<?php
/**
* Plugin Name: Inflation Calculator
* Plugin URI: https://www.github.com/muhammadadilakbar/inflation-calculator
* Description: A plugin which displays an inflation calculator on front-end.
* Version: 1.0.0
* Requires at least: 4.5
* Requires PHP: 7.4
* Author: Muhammad-Adil Akbar
* Author URI: https://www.github.com/muhammadadilakbar/
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: bspdi_inflation
*/

namespace BaseSpeedDigital\InflationCalculator;

if ( ! defined( 'BSPDI_INFLATION_DIR' ) ) {
	define( 'BSPDI_INFLATION_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'BSPDI_INFLATION_URL' ) ) {
	define( 'BSPDI_INFLATION_URL', plugin_dir_url( __FILE__ ) );
}

\register_activation_hook( __FILE__, function() {
	require_once( BSPDI_INFLATION_DIR . 'src/Activation.php' );
	Activation::activate();
});

\register_deactivation_hook( __FILE__, function() {
	require_once( BSPDI_INFLATION_DIR . 'src/Deactivation.php' );
	Deactivation::deactivate();
});

require_once( BSPDI_INFLATION_DIR . 'src/InflationCalculator.php' );

new InflationCalculator();