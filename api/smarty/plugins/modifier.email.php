<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty email modifier plugin
 *
 * Type:     email<br>
 * Name:     email<br>
 * @return string
 */
function smarty_modifier_email($string)
{
	return str_replace('@',' [kukac] ',$string);
}

?>
