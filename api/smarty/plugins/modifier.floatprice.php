<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     price<br>
 * Purpose:  convert string to price
 * @param string
 * @return string
 */
function smarty_modifier_floatprice($string)
{
    return STRING::floatMoneyString($string);
}

?>
