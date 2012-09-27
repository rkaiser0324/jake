<?php

/**
 * Jake Admin Component class file.
 *
 * Class that encapsulates the Jake Admin component.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla.administrator
 * @since			1.0
 */

require_once(JAKE_PATH_FRONT_LIB . DIRECTORY_SEPARATOR . 'jake_component.class.php');

/**
 * Jake Admin Component.
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla.administrator
 */
class JakeAdminComponent extends JakeComponent
{
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
			case JAKE_ADMIN_PARAMETER_TASK_VALUE_FORM:
				$this->_form($this->jakeOption, JAKE_PARAMETER_TASK, $this->jakeTask);
				break;
				
			case JAKE_ADMIN_PARAMETER_TASK_VALUE_DOCUMENTATION:
			default:
				$this->_documentation(JAKE_PARAMETER_TASK);
				break;
		}
	}
	
	/**
	 * Shows documentation
	 *
	 * @param string $taskName	Name of task parameter
	 * 
	 * @access private
	 * @since 1.0
	 */
	function _documentation($taskName)
	{
		$this->_show('documentation', array ('taskName'=>$taskName));
	}
	
	/**
	 * Shows the form for generation of Jake URLs
	 *
	 * @param string $option	Current Joomla option
	 * @param string $taskName	Name of task parameter
	 * @param string $task	Current Jake task
	 * 
	 * @access private
	 * @since 1.0
	 */
	function _form($option, $taskName, $task)
	{
		$generatedUrl = null;
		
		if (isset($_POST['url']))
		{
			$j = new Jake();
			$jakeCallable = $j->getInstance();
			
			$generatedUrl = $jakeCallable->getUrl((!empty($_POST['url']) ? $_POST['url'] : null), (isset($_POST['app']) && !$this->applications[$_POST['app']]['default'] ? $_POST['app'] : null));
		}
		
		$this->_show('form', array ('option'=>$option, 'taskName'=>$taskName, 'task'=>$task, 'generatedUrl'=>$generatedUrl));
	}	
	
	/**
	 * Shows the specified action on Joomla template
	 *
	 * @param string $action	Action to show
	 * @param array $params	Parameters (such as current task, current joomla option)
	 * 
	 * @access private
	 * @since 1.0
	 */
	function _show($action, $params = array())
	{
		ob_start();
		
		// Use the renderer for any version of Joomla to stay DRY
		
		require_once('admin.jake.html.php'); // HTML renderer
		
		$jakeHtmlRenderer = new HTML_jake();
		
		$jakeHtmlRenderer->setJoomlaUrl($this->joomlaUrl);
		$jakeHtmlRenderer->setApplications($this->applications);
		
		if ($action == 'documentation')
		{
			$jakeHtmlRenderer->documentation($params['taskName']);
		}
		else if ($action == 'form')
		{
			$jakeHtmlRenderer->form($params['option'], $params['taskName'], $params['task'], $params['generatedUrl']);
		}
		
		// Get the contents generated
		
		$contents = ob_get_clean();
		
		// Show contents depending on Joomla version
		
		if ($this->compareJoomlaVersion('1.5.0'))
		{
			require_once(JPATH_COMPONENT . '/jake_admin_controller.class.php');
			
			$controller	= new JakeAdminController();
			
			$controller->setContents($contents);
			$controller->admin_send();
		}
		else
		{
			echo $contents;
		}
	}
}

?>