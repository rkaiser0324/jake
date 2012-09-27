<?php

/**
 * Jake Admin View class file (Joomla 1.5).
 *
 * Class that encapsulates the Jake Admin view.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla.administrator.views.send
 * @since			1.0
 */

jimport('joomla.application.component.view');

/**
 * Jake Front View (Joomla 1.5).
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla.administrator.views.send
 */
class JakeViewAdmin_Send extends JView
{
	/**
	 * Sends the contents back to Joomla
	 *
	 * @param string	HTML contents to send
	 * 
	 * @access public
	 * @since 1.0
	 */
	function admin_send($contents)
	{
		$this->assign('contents', $contents);
		
		parent::display();
	}
}

?>