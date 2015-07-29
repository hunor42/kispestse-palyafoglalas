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
 * Name:     nohtml<br>
 * Purpose:  cut htmls from a string
 * @param string
 * @return string
 */
function smarty_modifier_nohtml($string)
{
	$string=str_replace('<br />',' ',$string);
	$string=str_replace('<br/>',' ',$string);
	$string=str_replace('<br>',' ',$string);
	$str=trim(strip_tags($string));
    return $str;
}

?>
