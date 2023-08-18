function do_inflation_calculation() {
  'use strict';
  let salary_obj = window.document.getElementById( "bspdi_inflation_salary" );
  let inflation_rate_obj = window.document.getElementById( "bspdi_inflation_rate" );
  let salary = parseInt( salary_obj.value );
  let inflation_rate = parseFloat( inflation_rate_obj.value );
  if( isNaN( salary ) || isNaN( inflation_rate ) ) { //What if user enters alphabets instead of numbers
    if( isNaN( salary ) )
      window.document.getElementById( "bspdi_inflation_error" ).textContent = "Please enter your salary.";
    if( isNaN( inflation_rate ) )
      window.document.getElementById( "bspdi_inflation_error" ).textContent = "Please enter your inflation rate.";
  }
  else {
    if( salary < 0 || inflation_rate < 0 ) { //What if user enters a negative number
      window.document.getElementById( "bspdi_inflation_error" ).textContent = "Please enter a positive number.";
    }
    else { //It is a number and also positive
      window.document.getElementById( "bspdi_inflation_error" ).textContent = "";
      let text_output = "";
      for( let i = 1; i < 6; i++ )
      {
        salary = ( salary * (1 + ( inflation_rate / 100 ) ) );
        text_output = text_output + '<div class="bspdi_inflation_this_year">Your salary after year ' + i + ": " + salary.toFixed(2) + "</div>";
      }
      window.document.getElementById( "bspdi_inflation_op" ).innerHTML = text_output;
    }
  }
}

jQuery( document ).ready( function($)
{
  'use strict';
  let inflation_calculator = window.document.getElementById( "bspdi_inflation_calculator" );
  inflation_calculator.addEventListener( 'keyup', do_inflation_calculation );
  let inflation_rate_db = parseFloat( bspdi_inflation_rate[0][1] );
  $("#bspdi_inflation_rate").attr( "value", inflation_rate_db );
} );