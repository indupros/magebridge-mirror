<?php
/**
 * Joomla! MageBridge - RocketTheme System plugin
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Import the parent class
jimport( 'joomla.plugin.plugin' );

// Import the MageBridge autoloader
include_once JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'helpers'.DS.'loader.php';

/**
 * MageBridge System Plugin
 */
class plgSystemMageBridgeRt extends JPlugin
{
    /**
     * Event onAfterDispatch
     *
     * @access public
     * @param null
     * @return null
     */
    public function onAfterDispatch()
    {
        // Don't do anything if MageBridge is not enabled 
        if($this->isEnabled() == false) return false;

        // Don't do anything in other applications than the frontend
        if(JFactory::getApplication()->isSite() == false) return false;

        // Read template-parameters and load specific stylesheet
        $application = JFactory::getApplication();

        // Read the template-related files
        $ini = JPATH_THEMES .DS.$application->getTemplate().DS.'params.ini';
        $ini_content = @file_get_contents($ini);
        $xml = JPATH_THEMES.DS.$application->getTemplate().DS.'templateDetails.xml';

        // WARP-usage of "config" file
        if(!empty($ini_content)) {

            // Create the parameters object
            jimport('joomla.html.parameter');
            $params = new JParameter($ini_content, $xml);

            // Load a specific stylesheet per color
            $color = $params->get('colorStyle');
            if(!empty($color)) {
                MageBridgeTemplateHelper::load('css', 'color-'.$color.'.css');
            }
        }

        // Check whether ProtoType is loaded, and add some fixes
        if(MageBridgeTemplateHelper::hasPrototypeJs()) {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('div.fusion-submenu-wrapper { margin-top: -12px !important; }');
        }
    }

    /**
     * Load the parameters
     *
     * @access private
     * @param null
     * @return JParameter
     */
    private function getParams()
    {
        if(!MageBridgeHelper::isJoomla15()) {
            return $this->params;
        } else {
            $plugin = JPluginHelper::getPlugin('system', 'magebridgert');
            $params = new JParameter($plugin->params);
            return $params;
        }
    }

    /**
     * Simple check to see if MageBridge exists
     * 
     * @access private
     * @param null
     * @return bool
     */
    private function isEnabled()
    {
        if(is_file(JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'models'.DS.'config.php')) {
            return true;
        }
        return false;
    }
}
