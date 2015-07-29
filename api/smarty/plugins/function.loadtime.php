<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.loadtime.php
 * Type:     function
 * Name:     icon
 * Purpose:  show page loading time
 * -------------------------------------------------------------
 */
function smarty_function_loadtime($params, &$smarty)
{
	global $startTime;
	
	$end=microtime(true);
	$run=$end-$startTime;
	
	return round($run,2);
}
?>