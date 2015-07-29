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
 * Name:     nobr<br>
 * Purpose:  cut brs from a string
 * @param string
 * @return string
 */
function smarty_modifier_nobr($string)
{
	$str=trim($string);
	$str=str_replace("\n\r"," ",$str);
	$str=str_replace("\r\n"," ",$str);
	$str=str_replace("\n"," ",$str);
	$str=str_replace("\r"," ",$str);
    return $str;
}

?>
