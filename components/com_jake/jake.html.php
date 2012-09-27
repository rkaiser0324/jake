<?php

/**
 * Jake Front HTML Renderer class file (Joomla 1.0).
 *
 * Class to render HTML back to Joomla.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla
 * @since			1.0
 */

/**
 * Jake Front HTML renderer.
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla
 */
class HTML_jake
{
	/**
	 * Sends the specified HTML to Joomla
	 *
	 * @param string $html
	 * 
	 * @access public
	 * @since 1.0
	 */
	function send($html)
	{
		echo $html;
	}
}

?>