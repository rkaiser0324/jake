<?php

define ('JAKE_ADMIN_COMPONENT_FRONT_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jake' );

require_once(JAKE_ADMIN_COMPONENT_FRONT_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'jake_admin_component.class.php');

$jakeComponent = new JakeAdminComponent();

$jakeComponent->setJoomlaMainframe($GLOBALS['mainframe']);
$jakeComponent->setJoomlaUrl($GLOBALS['mosConfig_live_site']);

$jakeComponent->execute();

?>