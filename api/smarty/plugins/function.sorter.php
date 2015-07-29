<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.sorter.php
 * Type:     function
 * Name:     icon
 * Purpose:  show a sorter header
 * -------------------------------------------------------------
 */
function smarty_function_sorter($params, &$smarty)
{
	$string=$params['text'];
	$id=$params['id'];
	$sort=$params['sort'];

	$sorter = new Sorter($id);
    return $sorter->header($string,$sort);
}
?>