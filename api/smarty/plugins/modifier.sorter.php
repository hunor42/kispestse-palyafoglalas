<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty sorter header modifier plugin
 *
 * Type:     modifier<br>
 * Name:     price<br>
 * Purpose:  convert string to sorter header
 * @param string
 * @return string
 */
function smarty_modifier_sorter($string,$id)
{
	$sorter = new Sorter($id);
    return $sorter->header($string);
}

?>
