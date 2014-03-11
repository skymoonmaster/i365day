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

function smarty_modifier_my_days($timeStamp) {
    $firstDateTS = strtotime(date('Y', $timeStamp) . '-01-01');

    return intval(($timeStamp - $firstDateTS) / 86400 + 1);
}

?>
