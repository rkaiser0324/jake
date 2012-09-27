<?php

defined( '_VALID_MOS' ) or defined( '_JEXEC' ) or die( 'Restricted access' );

// Restore some variables that are deprecated in Joomla 1.5

if (!isset($GLOBALS['mosConfig_absolute_path']))
{
	$GLOBALS['mosConfig_absolute_path'] = dirname(dirname(dirname(dirname(__FILE__))));
}

if (!isset($GLOBALS['mosConfig_live_site']))
{
	$GLOBALS['mosConfig_live_site'] = $GLOBALS['mainframe']->getCfg('live_site');
}

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'constants.php');
require_once(JAKE_PATH_FRONT_LIB . DIRECTORY_SEPARATOR . 'jake.class.php');

// Create instance of Jake callable

$jakeCallable =& Jake::getInstance();

$jakeCallable->setJoomlaUrl($GLOBALS['mosConfig_live_site']);

if ($jakeCallable->isJake() && isset($_GET[JAKE_PARAMETER_APPLICATION]))
{
	$jakeCallable->setApplication($_GET[JAKE_PARAMETER_APPLICATION]);
}	

?>