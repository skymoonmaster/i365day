<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File: modifier.capitalize.php
 * Type: modifier
 * Name: capitalize
 * Purpose: capitalize words in the string
 * -------------------------------------------------------------
 */
function smarty_modifier_my_number_format($num)
{
    return number_format(substr(sprintf("%.5f", $num), 0, -3),2);
}
?>
