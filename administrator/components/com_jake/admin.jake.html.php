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
class HTML_jake {
    /*     * #@+
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

    /*     * #@- */

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
     * Set available CakePHP applications
     *
     * @param array $applications	CakePHP available applications
     * 
     * @access public
     * @since 1.0
     */
    function setApplications($applications) {
        $this->applications = $applications;
    }

    /**
     * Shows the documentation and form for generation of Jake URLs
     *
     * @param string $option	Current Joomla option
     * @param string $taskName	Name of task parameter
     * @param string $task	Current Jake task
     * @param string $generatedUrl	URL that was generated (if any)
     * 
     * @access public
     * @since 1.0
     */
    function form($option, $taskName, $task, $generatedUrl = null) {
        ?>
        <div align="left">

            <p>
                Jake is a Joomla component that allows <a href="http://www.cakephp.org">CakePHP</a> applications to be hosted transparently within a 
                <a href="http://www.joomla.org/">Joomla</a> website. With Jake you have a bridge to your application that requires only minimal modifications 
                to your CakePHP code. Your application can run with or without Jake.  For more details see the <a href="https://github.com/rkaiser0324/jake">github project page</a>.
            </p>

            <p>

                <?php
                $url = str_replace('administrator/', '', $this->joomlaUrl) . 'app';
                $jake_url = str_replace('administrator/', '', $this->joomlaUrl) . 'index.php?' . JAKE_PARAMETER_OPTION . '=' . JAKE_CAKEPHP_JOOMLA_COMPONENT;

                $form_url = $this->joomlaUrl . 'index.php?' . JAKE_PARAMETER_OPTION . '=' . JAKE_CAKEPHP_JOOMLA_COMPONENT . '&' . $taskName . '=' . JAKE_ADMIN_PARAMETER_TASK_VALUE_FORM;
                ?>
                Once you have configured Jake as per the above link, you can load your CakePHP application at <a href="<?php echo $url ?>"><?php echo $url ?></a> which corresponds to the Jake URL <a href="<?php echo $jake_url ?>"><?php echo $jake_url ?></a>
            </p>

            <h2>Generate Jake URL</h2>

            <p>This form allows you to generate the Jake URL for a given CakePHP action.  It is not needed if you have followed the setup steps described <a href="/administrator/index.php?option=com_jake">here</a>.</p>

            <form action="index.php" method="post" name="adminForm">
                <input type="hidden" name="option" value="<?php echo $option; ?>" />
                <input type="hidden" name="<?php echo $taskName; ?>" value="<?php echo $task; ?>" />

                <table border="0"><tbody>
                        <tr>
                            <td align="left">CakePHP Application:</td>
                            <td align="left">
                                <select name="app">
                                    <?php foreach ($this->applications as $application) { ?>
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
            if (isset($generatedUrl)) {
                ?>
                <h4 style="border: 1px solid black; background: #efefef; padding:10px; margin-bottom: 20px;"><a href="<?php echo $generatedUrl; ?>"><?php echo $generatedUrl; ?></a></h4>
                    <?php
                }
                ?>
        </div>
        <?php
    }

}
