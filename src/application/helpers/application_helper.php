<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//--------------------------------------------------------------------
// Misc Helper Functions
//--------------------------------------------------------------------

if (!function_exists('e'))
{
    /*
        Function: e()

        A convenience function to make sure your output is safe to display.
        Helps to defeat XSS attacks by running the text through htmlspecialchars().

        Should be used anywhere you are displaying user-submitted text.
    */
    function e($str)
    {
        echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

//--------------------------------------------------------------------