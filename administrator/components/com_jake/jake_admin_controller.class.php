<?php

/**
 * Jake Admin Controller class file (Joomla 1.5).
 *
 * Class that encapsulates the Jake Front controller.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla.administrator
 * @since			1.0
 */

jimport('joomla.application.component.controller');

/**
 * Jake Admin Controller (Joomla 1.5).
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla.administrator
 */
class JakeAdminController extends JController
{
	/**#@+
	 * @access private
	 */
	/**
	 * HTML contents to send through Joomla
	 *
	 * @var string
	 * @since 1.0
	 */
	var $contents;
	/**#@-*/
	
	/**
	 * Sets HTML contents
	 * 
	 * @param string $contents	HTML contents
	 * 
	 * @access public
	 * @since 1.0
	 */
	function setContents($contents)
	{
		$this->contents = $contents;
	}
	
	/**
	 * Sends the contents back to Joomla
	 *
	 * @access public
	 * @since 1.0
	 */
	function admin_send()
	{
		$document = JFactory::getDocument();
		$viewType  = $document->getType();
		
		if (method_exists($this, 'setViewName'))
		{
			$this->setViewName( 'admin_send', 'JakeView', $viewType );
			$view =& $this->getView();
		}
		else
		{
			$view = $this->getView('admin_send', $viewType, 'JakeView');
		}
		
		$view->admin_send($this->contents);
	}
}

?>