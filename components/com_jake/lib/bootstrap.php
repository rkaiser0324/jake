<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

// Restore some 1.5 variables that are gone in Joomla 2.5
if (!isset($GLOBALS['mosConfig_absolute_path']))
	$GLOBALS['mosConfig_absolute_path'] = dirname(dirname(dirname(dirname(__FILE__))));

if (!isset($GLOBALS['mosConfig_live_site']))
	$GLOBALS['mosConfig_live_site'] = JURI::getInstance()->base();

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'constants.php');
require_once(JAKE_PATH_FRONT_LIB . DIRECTORY_SEPARATOR . 'jake.class.php');

// Create instance of Jake callable

$jakeCallable = new Jake();

$jakeCallable->setJoomlaUrl($GLOBALS['mosConfig_live_site']);

if ($jakeCallable->isJake() && isset($_GET[JAKE_PARAMETER_APPLICATION]))
{
	$jakeCallable->setApplication($_GET[JAKE_PARAMETER_APPLICATION]);
}	