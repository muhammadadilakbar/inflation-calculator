<?php

namespace BaseSpeedDigital\InflationCalculator;

class InflationCalculator
{
    private $options = array();

	public function __construct() {
        $this->getOptions();
        \add_action( 'admin_menu', array( $this, 'addSettingsMenu' ) );
        \add_action( 'admin_init', array( $this, 'registration' ) );
        \add_action( 'wp_enqueue_scripts', array( $this, 'loadPublicJSScripts') );
        \add_shortcode( "bspdi_inflation_calculator", array( $this, "renderShortCode" ) );
	}

    public function loadPublicJSScripts() {
        \wp_enqueue_script( "bspdi-inflation-calculator", BSPDI_INFLATION_URL . "assets/js/bspdi_inflation_calculator.js", array( "jquery" ), "20230817" );
        $plugin_options = $this->getOptions();
        $script = "var bspdi_inflation_rate = ["; //returns an array of arrays
        foreach( $plugin_options as $key => $value ) {
            $script = $script . "[\"$key\",\"$value\"],";
        }
        $script = $script . "];";
        \wp_add_inline_script( "bspdi-inflation-calculator", $script, "before" );
        \wp_enqueue_style( "bspdi-inflation-styles", BSPDI_INFLATION_URL . "assets/css/styles.css" );
    }

    public function addSettingsMenu() {
        \add_menu_page( 'Inflation Calculator', 'Inflation Calculator', 'manage_options', 'bspdi_inflation_settings_page', array( $this, 'renderSettingsPage' ) );
    }

    private function getOptions() {
        $this->options = \get_option( "bspdi_inflation_options" );
        return $this->options;
    }

    public function renderShortCode() {
        $calculator_html = '<div id="bspdi_inflation_calculator">
            <h6 class="bspdi_inflation_heading">Inflation Calculator</h6>
            <div id="bspdi_inflation_error"></div>
            <div class="bspdi_fields">
                <label for="bspdi_inflation_rate">Inflation rate (%): </label>
                <input type="number" id="bspdi_inflation_rate" name="bspdi_inflation_rate" step="0.01" min="0" max="100" />
            </div>
            <div class="bspdi_fields">
                <label for="bspdi_inflation_salary">Salary: </label>
                <input type="number" id="bspdi_inflation_salary" name="bspdi_inflation_salary" step="1" min="0" />
            </div>
            <div id="bspdi_inflation_op">Your salary: </div>
        </div>';
        return $calculator_html;
    }

    public function registration() {
        $args = array(
            'type' => 'array',
            'sanitize_callback' => array( $this, 'validate_options' )
        );

        register_setting( "bspdi_inflation_options", "bspdi_inflation_options", $args );
        add_settings_section( 'bspdi_inflation_main_section', '', array($this, 'render_main_section' ), 'bspdi_inflation_settings_page' );
        add_settings_field( 'bspdi_inflation_rate', 'Inflation rate: ', array( $this, 'bspdi_inflation_render_irate' ), 'bspdi_inflation_settings_page', 'bspdi_inflation_main_section' );
    }

    public function validate_options( $input ) {
        $valid = array();
        //Keep only numbers and dots
        $valid['inflation_rate'] = preg_replace(
            '/[^\d\.]/',
            '',
            $input['inflation_rate']
        );
        //Now there are only numbers and dots. What if there are multiple dots like 2.23.22?
        $is_float = false;
        if( str_contains( $valid['inflation_rate'], '.' ) ) //check if there is a dot in string
            $is_float = true;
        if( $is_float ) {
            //not a valid float like the123 || has more than 1 dot
            if( floatval( $valid['inflation_rate'] ) == 0 || 
            substr_count( $valid['inflation_rate'], "." )  > 1 ) {
                add_settings_error( 'bspdi_inflation_options', 'bspdi_inflation_illegal_value', "Please enter a valid value. There can't be more than one decimal." );
                $valid['inflation_rate'] = '0.0';
            }
            else {
                $valid['inflation_rate'] = (string) floatval( $valid['inflation_rate'] );
            }
            if( floatval( $valid['inflation_rate'] ) < 0.0 || $valid['inflation_rate'] > 100.0 ) {
                add_settings_error( 'bspdi_inflation_options', 'bspdi_inflation_outofrange', 'Please enter a valid value. It should be between 0.0 and 100.0' );
                $valid['inflation_rate'] = '0.0';
            }
        }
        else // No dot means value is integer
        {
            if( intval( $valid['inflation_rate'] ) < 0 || intval( $valid['inflation_rate'] > 100 ) ) {
                add_settings_error( 'bspdi_inflation_options', 'bspdi_inflation_outofrange', 'Please enter a valid value. It should be between 0 and 100.' );
                $valid['inflation_rate'] = '0';
            }
            else {
                $valid['inflation_rate'] = (string) intval( $valid['inflation_rate'] );
            }
        }
        return $valid;
    }

    public function render_main_section() {
        echo '<p>Enter inflation rate in percentage.</p>';
    }

    public function bspdi_inflation_render_irate() {
        if( isset( $this->options["inflation_rate"] ) )
            $inflation_rate = $this->options["inflation_rate"];
        else
            $inflation_rate = 0;
        echo "<input id='irate' name='bspdi_inflation_options[inflation_rate]' type='number' step='0.01' min='0' max='100' value='" . esc_attr( $inflation_rate ) . "'/>";
    }

    public function renderSettingsPage() {
        if( ! current_user_can( "manage_options" ) ) {
            wp_die("Sorry, you don't have necessary permissions to perform this action");
            exit();
        }
        ?>
        <div class="wrap">
            <?php
            settings_errors('bspdi_inflation_options');
            ?>
            <h2>Inflation Calculator</h2>
            <form action="options.php" method="post">
                <?php
                \settings_fields( 'bspdi_inflation_options' );
                \do_settings_sections( 'bspdi_inflation_settings_page' );
                \submit_button( 'Save Changes', 'primary' );
                ?>
            </form>
        </div>
        <?php
    }
}
