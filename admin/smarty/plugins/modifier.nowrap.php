<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty nowrap modifier plugin
 *
 * Type:     modifier<br>
 * Name:     wordwrap<br>
 * Purpose:  wrap a string of text at a given length
 * @link http://smarty.php.net/manual/en/language.modifier.wordwrap.php
 *          wordwrap (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @return string
 */
function smarty_modifier_nowrap($string)
{
    return str_replace(" ","&nbsp;",$string);
}

?>
