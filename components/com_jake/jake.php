<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.php');
require_once(JAKE_PATH_FRONT . DIRECTORY_SEPARATOR . 'jake_front_component.class.php');

$jakeComponent = new JakeFrontComponent();

$mainframe = JFactory::getApplication();
$jakeComponent->setJoomlaMainframe($mainframe);
$jakeComponent->setJoomlaUrl($GLOBALS['mosConfig_live_site']);

$jakeComponent->execute();

?>