<?php

/**
 * Jake class file.
 *
 * Class that offers Jake callables.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla.lib
 * @since			1.0
 */

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'constants.php');

/**
 * Jake Callables.
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla.lib
 */
class Jake
{
	/**#@+
	 * @access private
	 */
	/**
	 * Joomla's root URL.
	 * 
	 * @since 1.0
	 * @var string
	 */
	var $joomlaUrl;		
	/**
	 * Current CakePHP application identifier.
	 * 
	 * @since 1.0
	 * @var string
	 */
	var $application;
	/**#@-*/
	
	/**
	 * Singleton implementation (PHP4/PHP5)
	 *
	 * @return mixed	A shared instance
	 * 
	 * @access public
	 * @static
	 * @since 1.0
	 */
	function &getInstance()
	{
		static $instances = array();
		
		$className = __CLASS__;
		
		if (!isset($instances[$className]))
		{
			$instances[$className] = new $className();
		}
		
		return $instances[$className];
	}
	
	function getCurrentUser()
	{		
		return JFactory::getUser();
	}

	function Object()
	{
		$args = func_get_args();
		
		if (method_exists($this, '__destruct'))
		{
			register_shutdown_function(array(&$this, '__destruct'));
		}

		call_user_func_array(array(&$this, '__construct'), $args);
	}

	function __construct()
	{
	}
	
	/**
	 * Set Joomla's URL.
	 * 
	 * @param string $joomlaUrl	Joomla's URL
	 * 
	 * @access public
	 * @since 1.0
	 */
	function setJoomlaUrl($joomlaUrl)
	{
		$this->joomlaUrl = $joomlaUrl;
	}
	
	/**
	 * Set current CakePHP application.
	 * 
	 * @param string $application	Current application
	 * 
	 * @access public
	 * @since 1.0
	 */
	function setApplication($application)
	{
		$this->application = $application;
	}
	
	/**
	 * Returns if CakePHP application is being run on Jake.
	 *
	 * @return bool	true if it is running on Jake, false otherwise
	 * 
	 * @access public
	 * @since 1.0
	 */
	function isJake()
	{
		return defined('JAKE');
	}
	
	/**
	 * Gets the URL to execute the specified CakePHP action with Jake
	 *
	 * @param string $action	CakePHP URL. (leave empty for default cake action)
	 * @param string $application	The identifier of the application in the configuration file (leave empty for default)
	 *
	 * @return string	A valid Jake URL
	 *
	 * @access public
	 * @since 1.0
	 */
	function getUrl($action = null, $application = null)
	{
		$url = $this->joomlaUrl . '/index.php';
		$url .= '?' . JAKE_PARAMETER_OPTION . '=' . JAKE_CAKEPHP_JOOMLA_COMPONENT;
		
		if ($this->isJake() && isset($this->cakeApplicationId))
		{
			$application = $this->cakeApplicationId;
		}
		
		if (isset($application))
		{
			$url .= '&' . JAKE_PARAMETER_APPLICATION . '=' . urlencode($application);
		}
		
		if (isset($action))
		{
			$url .= '&' . JAKE_PARAMETER_EXECUTE . '=' . urlencode($action);
		}
		return $url;
	}
	
	/**
	 * Gets the SEF URL to execute the specified CakePHP action with Jake
	 *
	 * @param string $action	CakePHP URL. (leave empty for default cake action)
	 *
	 * @return string	A valid Jake URL
	 *
	 * @access public
	 * @since 1.1
	 */
	function getSefUrl($action = '', $sefCakeApplicationBase = '')
	{
		return $this->joomlaUrl . $sefCakeApplicationBase . $action;
	}
}

?>