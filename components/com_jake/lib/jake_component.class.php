<?php

/**
 * Jake Component class file.
 *
 * Class that encapsulates the Jake Joomla component.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla.lib
 * @since			1.0
 */
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'constants.php');

/**
 * Jake Component.
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla.lib
 */
class JakeComponent {
    /*     * #@+
     * @access private
     */

    /**
     * Jake task.
     * 
     * @since 1.0
     * @var string
     */
    var $jakeTask;

    /**
     * Joomla's option.
     * 
     * @since 1.0
     * @var string
     */
    var $jakeOption;

    /**
     * Joomla's root path.
     * 
     * @since 1.0
     * @var string
     */
    var $joomlaPath;

    /**
     * Joomla's root URL.
     * 
     * @since 1.0
     * @var string
     */
    var $joomlaUrl;

    /**
     * Joomla's version.
     * 
     * @since 1.0
     * @var string
     */
    var $joomlaVersion;

    /**
     * Joomla's mainframe.
     * 
     * @since 1.0
     * @var mixed
     */
    var $joomlaMainframe;

    /**
     * CakePHP application identifier.
     * 
     * @since 1.0
     * @var string
     */
    var $currentApplication;

    /**
     * Available CakePHP applications.
     * 
     * @since 1.0
     * @var array
     */
    var $applications;

    /*     * #@- */

    function JakeComponent() {
        $this->_initialize();
    }

    /**
     * Set Joomla's URL.
     * 
     * @param string $joomlaUrl	Joomla's URL
     * 
     * @access public
     * @since 1.0
     */
    function setJoomlaUrl($joomlaUrl) {
        $this->joomlaUrl = $joomlaUrl;
    }

    /**
     * Set Joomla's mainframe.
     * 
     * @param mixed $joomlaMainframe	Joomla's mainframe
     * 
     * @access public
     * @since 1.0
     */
    function setJoomlaMainframe(&$joomlaMainframe) {
        $this->joomlaMainframe = & $joomlaMainframe;
    }

    /**
     * Get Joomla's parameter.
     * 
     * @param string $parameter	Parameter name
     * @param string $default	Default value if parameter not set
     * 
     * @return string	Parameter value
     * 
     * @access public
     * @since 1.0
     */
    function getParameter($parameter, $default = null) {

        $result = JRequest::getVar($parameter);

        if (!isset($result)) {
            $result = $default;
        }

        return strval($result);
    }

    /**
     * End application with message
     * 
     * @param string $message	Message
     * 
     * @access protected
     * @since 1.0
     */
    function raiseError($message) {
        die('<strong>Jake Error</strong> :: ' . $message);
    }

    /**
     * Initializes the component (also parsing configuration)
     * 
     * @access private
     * @since 1.0
     */
    function _initialize() {
        if (!isset($GLOBALS['_VERSION']) && function_exists('jimport')) {
            jimport('joomla.version');

            $GLOBALS['_VERSION'] = new JVersion();
        }

        $this->joomlaPath = $GLOBALS['mosConfig_absolute_path'];
        $this->joomlaVersion = $GLOBALS['_VERSION']->getShortVersion();
        $this->jakeTask = $this->getParameter(JAKE_PARAMETER_TASK);
        $this->jakeOption = $this->getParameter(JAKE_PARAMETER_OPTION, JAKE_CAKEPHP_JOOMLA_COMPONENT);

        $this->_parseConfiguration();
    }

    /**
     * Parse Jake configuration
     * 
     * @access private
     * @since 1.0
     */
    function _parseConfiguration() {
        $configFile = JAKE_PATH_FRONT . DIRECTORY_SEPARATOR . 'jake.ini';

        if (!@file_exists($configFile)) {
            return $this->raiseError('Configuration file not present! Looked for ' . $configFile);
        }

        $elements = parse_ini_file($configFile, true);

        if (!isset($elements['settings']) || !is_array($elements['settings'])) {
            return $this->raiseError('Configuration file has no settings section');
        }

        $settings = $elements['settings'];

        unset($elements['settings']);

        if (count($elements) == 0) {
            return $this->raiseError('There are no applications defined on the configuration file');
        }

        if (!isset($_GET[JAKE_PARAMETER_APPLICATION]) && (!isset($settings['default']) || !is_string($settings['default']))) {
            return $this->raiseError('No default application is set on the configuration file');
        }

        // Build available applications

        $this->applications = array();

        foreach ($elements as $application => $applicationSettings) {
            $this->applications[$application] = $applicationSettings;

            $this->applications[$application]['id'] = $application;

            $this->applications[$application]['default'] = ($settings['default'] == $this->applications[$application]['id']);

            if (!isset($this->applications[$application]['name'])) {
                $this->applications[$application]['name'] = $application;
            }

            foreach ($this->applications[$application] as $element => $value) {
                if (is_string($value) && in_array($element, array('path', 'url'))) {
                    $this->applications[$application][$element] = trim($value);
                }
            }

            if (isset($this->applications[$application]['path'])) {
                if ($this->applications[$application]['path'][0] !== '/' && $this->applications[$application]['path'][0] !== '\\') {
                    $this->applications[$application]['path'] = $this->_canonicalizePath($this->joomlaPath . DIRECTORY_SEPARATOR . $this->applications[$application]['path']);
                }

                if (DIRECTORY_SEPARATOR == '/') {
                    $this->applications[$application]['path'] = str_replace('\\', DIRECTORY_SEPARATOR, $this->applications[$application]['path']);
                } else {
                    $this->applications[$application]['path'] = str_replace('/', DIRECTORY_SEPARATOR, $this->applications[$application]['path']);
                }
            }
        }

        if (isset($_GET[JAKE_PARAMETER_APPLICATION])) {
            $currentApplication = $_GET[JAKE_PARAMETER_APPLICATION];
        } else {
            $currentApplication = $settings['default'];
        }

        if (!isset($this->applications[$currentApplication])) {
            return $this->raiseError('The application named <code>' . $currentApplication . '</code> is not set on the configuration file');
        }

        // Set current application

        $this->application = $this->applications[$currentApplication];

        if (!isset($this->application['path']) || empty($this->application['path'])) {
            return $this->raiseError('There\'s no <code>path</code> specified in application <code>' . $this->application['id'] . '</code>');
        }

        // Check current application path

        if (@!is_readable($this->application['path']) || @!is_dir($this->application['path'])) {
            return $this->raiseError('The path <code>' . $this->application['path'] . '</code> specified in application <code>' . $this->application['id'] . '</code> is not accessible');
        } else if (@!file_exists($this->application['path'] . DIRECTORY_SEPARATOR . 'index.php')) {
            return $this->raiseError('The path <code>' . $this->application['path'] . '</code> specified in application <code>' . $this->application['id'] . '</code> doesn\'t seem to host a CakePHP application');
        }

        if (!isset($this->application['url']) || empty($this->application['url'])) {
            return $this->raiseError('There\'s no <code>url</code> specified in application <code>' . $this->application['id'] . '</code>');
        }
    }

    /**
     * Get the canonicalized version of a path
     * 
     * @param string $path	Path
     * 
     * @return string	Canonicalized path
     * 
     * @access private
     * @since 1.0
     */
    function _canonicalizePath($path) {
        // Converts all "\" to "/", and erases blank spaces at the beginning and the ending of the string

        $path = trim(preg_replace("/\\\\/", "/", (string) $path));

        // Checks if last parameter is a directory with no slashs ("/") in the end. To be considered a dir, 
        // it can't end on "dot something", or can't have a querystring ("dot something ? querystring") 

        if (!preg_match("/(\.\w{1,4})$/", $path) && !preg_match("/\?[^\\/]+$/", $path) && !preg_match("/\\/$/", $path)) {
            $path .= '/';
        }

        // Breaks the original string in to parts: "root" and "dir". 
        // "root" can be "C:/" (Windows), "/" (Linux) or "http://www.something.com/" (URLs). This will be the start of output string. 
        // "dir" can be "Windows/System", "root/html/examples/", "includes/classes/class.validator.php", etc. 

        preg_match_all("/^(\\/|\w:\\/|(http|ftp)s?:\\/\\/[^\\/]+\\/)?(.*)$/i", $path, $matches, PREG_SET_ORDER);

        $path_root = $matches[0][1];
        $path_dir = $matches[0][3];

        // If "dir" part has one or more slashes at the beginning, erases all. 
        // Then if it has one or more slashes in sequence, replaces for only 1. 

        $path_dir = preg_replace(array("/^\\/+/", "/\\/+/"), array("", "/"), $path_dir);

        // Breaks "dir" part on each slash 

        $path_parts = explode("/", $path_dir);

        // Creates a new array with the right path. Each element is a new dir (or file in the ending, if exists) in sequence. 

        for ($i = $j = 0, $real_path_parts = array(); $i < count($path_parts); $i++) {
            if ($path_parts[$i] == '.') {
                continue;
            } else if ($path_parts[$i] == '..') {
                if ((isset($real_path_parts[$j - 1]) && $real_path_parts[$j - 1] != '..') || ($path_root != "")) {
                    array_pop($real_path_parts);
                    $j--;
                    continue;
                }
            }

            array_push($real_path_parts, $path_parts[$i]);

            $j++;
        }

        // Remove tailing slash

        $path = $path_root . implode('/', $real_path_parts);

        if ($path[strlen($path) - 1] == '/') {
            $path = substr($path, 0, strlen($path) - 1);
        }

        return $path;
    }

}