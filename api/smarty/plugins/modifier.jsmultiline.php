<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty jsmultiline modifier plugin
 *
 * Type:     modifier<br>
 * Name:     nobr<br>
 * Purpose:  cut brs from a string
 * @param string
 * @return string
 */
function smarty_modifier_jsmultiline($string)
{
	$str=trim($string);
	$str=str_replace("\n\r","\\[%%SORTORES%%]",$str);
	$str=str_replace("\r\n","\\[%%SORTORES%%]",$str);
	$str=str_replace("\n","\\[%%SORTORES%%]",$str);
	$str=str_replace("\r","\\[%%SORTORES%%]",$str);
	$str=str_replace("\"","\\\"",$str);
	$str=str_replace("[%%SORTORES%%]","n",$str);
    return $str;
}

?>
