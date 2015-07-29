<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty t modifier plugin
 *
 * Type:     modifier<br>
 * Name:     t<br>
 * Purpose:  translate a string
 * @param string
 * @return string
 */
function smarty_modifier_t($string)
{
	$string=gettext($string);
    return $string;
}

?>
