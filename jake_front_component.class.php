<?php
/**
 * Jake Front Component class file.
 *
 * Class that encapsulates the Jake Front component.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla
 * @since			1.0
 */

require_once(JAKE_PATH_FRONT_LIB . DIRECTORY_SEPARATOR . 'jake_component.class.php');
require_once(JAKE_PATH_FRONT_LIB . DIRECTORY_SEPARATOR . 'cake_embedded_dispatcher.class.php');

/**
 * Jake Front Component.
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla
 */
class JakeFrontComponent extends JakeComponent
{
	/**#@+
	 * @access private
	 */
	/**
	 * CakePHP url (e.g: controller/action).
	 * 
	 * @since 1.0
	 * @var string
	 */
	var $cakePath;
	
	/**
	 * Backup session data (variables stored in session, session module, and session id)
	 *
	 * @since 1.0
	 * @var array
	 */
	var $backSession;
	/**#@-*/
	
	function JakeFrontComponent()
	{
		parent::JakeComponent();
		
		$this->cakePath = $this->getParameter(JAKE_PARAMETER_EXECUTE, JAKE_CAKEPHP_DEFAULT_ACTION);
	}
	
	/**
	 * Execute the appropiate action (based on Jake task).
	 *
	 * @access public
	 * @since 1.0
	 */
	function execute()
	{
		switch($this->jakeTask)
		{
			case JAKE_PARAMETER_TASK_VALUE_RUN:
			case JAKE_PARAMETER_TASK_VALUE_CLEAN:
			default:
				$this->_do($this->cakePath, ($this->jakeTask == JAKE_PARAMETER_TASK_VALUE_CLEAN));
				break;		
		}
	}
	
	/**
	 * Execute a Jake-CakePHP bridge
	 *
	 * @param string $cakePath	CakePHP url
	 * @param bool $isClean	true if content should not pass through Joomla template, false otherwise
	 * 
	 * @access private
	 * @since 1.0
	 */
	function _do($cakePath, $isClean = false)
	{
		// Let CakePHP know about Jake
		
		define('JAKE', true);
		
		$cakeDispatcher = new CakeEmbeddedDispatcher();
		
		$cakeDispatcher->setCakePath($this->application['path']);
		$cakeDispatcher->setCakeUrlBase($this->application['url']);
		
		// Set the SEF URL to match the rewrite rule in /.htaccess
		$cakeDispatcher->setSefCakeApplicationBase('/app');
		
		$cakeDispatcher->setComponent($this->joomlaUrl . '/index.php?' . JAKE_PARAMETER_OPTION . '=' . JAKE_CAKEPHP_JOOMLA_COMPONENT . '&amp;' . JAKE_PARAMETER_EXECUTE . '=$CAKE_ACTION');
		$cakeDispatcher->setCleanOutput($isClean);
		$cakeDispatcher->setCleanOutputParameter(JAKE_PARAMETER_TASK, 'clean');
		$cakeDispatcher->setIgnoreParameters(array(JAKE_PARAMETER_OPTION, JAKE_PARAMETER_EXECUTE, JAKE_PARAMETER_TASK));
		
		if (isset($this->application['send']))
		{
			$cakeDispatcher->setCakeUrlAddParameters($this->application['send']);
		}
		
		// Backup session for Joomla 1.5 (1.0 is taken care in embedded component)
		
		if ($this->compareJoomlaVersion('1.5.0'))
		{
			$this->backSession = array (
				'id' => session_id(),
				'module' => session_module_name(),
				'data' => array()
			);
			
			foreach($_SESSION as $parameter => $value)
			{
				$this->backSession['data'][$parameter] = $value;
			}
			
			$session =& JFactory::getSession();
			//$session->destroy();
			
			//session_module_name('files');
			
			// Don't need it, we just did that
			
			$cakeDispatcher->setRestoreSession(false);
		}
		// Execute the CakePHP action
		
		$contents = $cakeDispatcher->get($cakePath);
		
		// Restore session for Joomla 1.5 (1.0 is taken care in embedded component)
		
		if ($this->compareJoomlaVersion('1.5.0'))
		{
			/*if (isset($_SESSION))
			{
				session_destroy();
			}
			
			session_module_name($this->backSession['module']);
			
			session_id($this->backSession['id']);
			
			$session =& JFactory::getSession();
			$ret = $session->restart();*/
			foreach($this->backSession['data'] as $parameter => $value)
			{
				$_SESSION[$parameter] = $value;
			}
		}
				
		if ($this->restoreJoomlaDb)
		{
			$this->restoreJoomlaDb();
		}
		
		if ($isClean)
		{
			echo $contents['body'];
			exit;
		}
		
		$this->_show($contents);
	}
	
	/**
	 * Shows the specified contents on Joomla's template
	 *
	 * @param array $contents	Indexed array (head, body) with contents to show 
	 * 
	 * @access private
	 * @since 1.0
	 */
	function _show($contents)
	{
		if (isset($contents['head']) && count($contents['head']) > 0)
		{
			if ($this->compareJoomlaVersion('1.5.0'))
			{
				$joomlaDocument =& JFactory::getDocument();
			}
		
			// Meta tags (not http-equiv)
			
			if (isset($contents['head']['meta']))
			{
				foreach($contents['head']['meta'] as $element)
				{
					if (isset($joomlaDocument))
					{
						$joomlaDocument->setMetaData($element['name'], $element['content']);
					}
					else
					{
						$this->joomlaMainframe->addMetaTag($element['name'], $element['content']);
					}
				}
			}
			
			// Script links (references to JS files) and blocks (code)
			
			if (isset($contents['head']['script']))
			{
				foreach($contents['head']['script'] as $element)
				{
					if (isset($joomlaDocument))
					{
						if (isset($element['body']))
						{
							$joomlaDocument->addScriptDeclaration($element['body'], $element['type']);
						}
						else
						{
							$joomlaDocument->addScript($element['src'], $element['type']);
						}
					}
					else
					{
						$contents['head']['custom'][] = $element['tag'];
					}
				}
			}
			
			// Stylesheet links (references to CSS files)
			
			if (isset($contents['head']['stylesheets']))
			{
				foreach($contents['head']['stylesheets'] as $element)
				{
					if (isset($joomlaDocument))
					{
						$joomlaDocument->addStyleSheet($element['href'], $element['type']);
					}
					else
					{
						$contents['head']['custom'][] = $element['tag'];
					}
				}
			}
			
			// Meta http-equiv tags
			
			if (isset($contents['head']['http-equiv']))
			{
				foreach($contents['head']['http-equiv'] as $element)
				{
					if (isset($joomlaDocument))
					{
						$joomlaDocument->setMetaData($element['http-equiv'], $element['content'], true);
					}
					else
					{
						$contents['head']['custom'][] = $element['tag'];
					}
				}
			}
			
			// Document title
			
			if (isset($contents['head']['title']))
			{
				if (isset($joomlaDocument))
				{
					$joomlaDocument->setTitle($contents['head']['title']);
				}
				else
				{
					$this->joomlaMainframe->setPageTitle($contents['head']['title']);
				}
			}
			
			// Remaining head tags
			
			if (isset($contents['head']['custom']))
			{
				foreach($contents['head']['custom'] as $custom)
				{
					$this->joomlaMainframe->addCustomHeadTag($custom);
				}
			}
		}
		
		// Send contents to joomla

		if ($this->compareJoomlaVersion('1.5.0'))
		{
			require_once(JPATH_COMPONENT . '/jake_front_controller.class.php');
			
			$controller	= new JakeFrontController();
			
			$controller->setContents($contents['body']);
			$controller->execute('send');
		}
		else
		{
			require_once($this->joomlaMainframe->getPath( 'front_html' )); // HTML renderer
			
			$jakeHtmlRenderer = new HTML_jake();
			
			$jakeHtmlRenderer->send($contents['body']);
		}
	}
}
?>