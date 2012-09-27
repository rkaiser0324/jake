<?php

/**
 * Jake Admin HTML Renderer class file (Joomla 1.0).
 *
 * Class to render HTML back to Joomla.
 *
 * @filesource
 * @link			http://dev.sypad.com/projects/jake Jake
 * @package			jake
 * @subpackage		joomla.administrator
 * @since			1.0
 */

require_once(JAKE_ADMIN_COMPONENT_FRONT_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'constants.php');

/**
 * Jake Admin HTML renderer.
 * 
 * @author		Mariano Iglesias - mariano@cricava.com
 * @package		jake
 * @subpackage	joomla.administrator
 */
class HTML_jake
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
	 * Available CakePHP applications.
	 * 
	 * @since 1.0
	 * @var array
	 */
	var $applications;
	/**#@-*/
	
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
	 * Set available CakePHP applications
	 *
	 * @param array $applications	CakePHP available applications
	 * 
	 * @access public
	 * @since 1.0
	 */
	function setApplications($applications)
	{
		$this->applications = $applications;
	}
	
	/**
	 * Shows the form for generation of Jake URLs
	 *
	 * @param string $option	Current Joomla option
	 * @param string $taskName	Name of task parameter
	 * @param string $task	Current Jake task
	 * @param string $generatedUrl	URL that was generated (if any)
	 * 
	 * @access public
	 * @since 1.0
	 */
	function form($option, $taskName, $task, $generatedUrl = null)
	{
		?>
		<h1>Jake: Joomla-CakePHP Bridge</h1>
		
		<form action="index2.php" method="post" name="adminForm">
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="<?php echo $taskName; ?>" value="<?php echo $task; ?>" />
			
			<table border="0"><tbody>
				<tr>
					<td align="left">CakePHP Application:</td>
					<td align="left">
						<select name="app">
						<?php foreach($this->applications as $application) { ?>
							<option value="<?php echo $application['id']; ?>"<?php echo ((isset($_POST['app']) && strcmp($_POST['app'], $application['id']) == 0) || (!isset($_POST['app']) && $application['default']) ? ' selected' : ''); ?>><?php echo $application['name']; ?><?php echo ($application['default'] ? ' (Default)' : ''); ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="left">CakePHP URL:</td>
					<td align="left">
		<input type="text" name="url" value="<?php echo (isset($_POST['url']) ? $_POST['url'] : ''); ?>" size="60" /><br />
						Example: <code>/posts/view/4</code>, or leave empty for default
					</td>
				</tr>
			</tbody></table>
			
			<br /><br />
			
			<input type="submit" value="Get Jake URL" />
		</form>
		<?php
		
		if (isset($generatedUrl))
		{
		?>
		<h4 style="border: 1px solid black; background: #efefef; padding-top: 5px; padding-bottom: 5px; margin-bottom: 20px;"><a href="<?php echo $generatedUrl; ?>"><?php echo $generatedUrl; ?></a></h4>
		<?php
		}
	}
	
	/**
	 * Shows documentation
	 *
	 * @param string $taskName	Name of task parameter
	 * 
	 * @access public
	 * @since 1.0
	 */
	function documentation($taskName)
	{
		$configFile = JAKE_ADMIN_COMPONENT_FRONT_PATH . DIRECTORY_SEPARATOR . 'jake.ini';
		
		if (DIRECTORY_SEPARATOR == '\\')
		{
			$configFile = str_replace('/', '\\', $configFile);
		}
		
		?>
		<h1>Jake: Joomla-CakePHP Bridge</h1>
		
		<div align="left">
		
		<p>
			Jake is a Joomla component that allows <a href="http://www.cakephp.org">CakePHP</a> applications to be hosted within a Joomla website. With Jake you have a bridge to your application that requires no modifications on your CakePHP code. Your application can run with or without Jake.
		</p>
		
		<h2>Documentation</h2>
		
		<p>
			Jake's documentation is hosted at <a href="http://dev.sypad.com/projects/jake">http://dev.sypad.com/projects/jake</a>.
		</p>
		
		<h2>Quick Setup</h2>
		
		<p>
			Assuming you have your CakePHP application up and running, take the following steps to test your Jake install:
		</p>
		
		<ol>
			<li>Edit the file <code><?php echo $configFile; ?></code> and modify its settings as instructed.</li>
			<li>Go to <a href="<?php echo $this->joomlaUrl; ?>/index.php?<?php echo JAKE_PARAMETER_OPTION; ?>=<?php echo JAKE_CAKEPHP_JOOMLA_COMPONENT; ?>"><?php echo $this->joomlaUrl; ?>/index.php?<?php echo JAKE_PARAMETER_OPTION; ?>=<?php echo JAKE_CAKEPHP_JOOMLA_COMPONENT; ?></a> and you should see the default page of your CakePHP application within Joomla.</li>
			<li>Read the <a href="http://dev.sypad.com/projects/jake">documentation</a> for notes and tips on how to take better advantage of Jake.</li>
		</ol>
		
		<h2>Run Jake</h2>
		
		<p>
			Load your default CakePHP application (once Jake has been set up) default page with the following URL: <a href="<?php echo $this->joomlaUrl; ?>/index.php?<?php echo JAKE_PARAMETER_OPTION; ?>=<?php echo JAKE_CAKEPHP_JOOMLA_COMPONENT; ?>"><?php echo $this->joomlaUrl; ?>/index.php?<?php echo JAKE_PARAMETER_OPTION; ?>=<?php echo JAKE_CAKEPHP_JOOMLA_COMPONENT; ?></a>
		</p>
		
		<p>
			If you want to get the Joomla URL to load a specific application/action use the handy Jake form at <a href="<?php echo $this->joomlaUrl; ?>/administrator/index2.php?<?php echo JAKE_PARAMETER_OPTION; ?>=<?php echo JAKE_CAKEPHP_JOOMLA_COMPONENT; ?>&<?php echo $taskName; ?>=<?php echo JAKE_ADMIN_PARAMETER_TASK_VALUE_FORM; ?>"><?php echo $this->joomlaUrl; ?>/administrator/index2.php?<?php echo JAKE_PARAMETER_OPTION; ?>=<?php echo JAKE_CAKEPHP_JOOMLA_COMPONENT; ?>&<?php echo $taskName; ?>=<?php echo JAKE_ADMIN_PARAMETER_TASK_VALUE_FORM; ?></a>
		</p>
		
		<h2>Credits</h2>
		
		<p>
			This project is maintained and developed by <a href="http://www.marianoiglesias.com.ar">Mariano Iglesias</a> and <a href="http://www.gigapromoters.com/blog/">Max</a>. Further credits go to Dr. Tarique Sani for his insightful ideas.
		</div>
		<?php
	}
}
?>