<?php
/**
 * Joomla! MageBridge Preloader - System plugin
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2012
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
 * MageBridge Preloader System Plugin
 */
class plgSystemMageBridgePre extends JPlugin
{
    /**
     * Event onAfterInitialise
     *
     * @access public
     * @param null
     * @return null
     */
    public function onAfterInitialise()
    {
        // Don't do anything if MageBridge is not enabled 
        if($this->isEnabled() == false) return false;

        // Perform actions on the frontend
        $application = JFactory::getApplication();
        if($application->isSite()) {

            // Import the custom module helper - this is needed to make it possible to flush certain positions 
            if($this->getParams()->get('override_modulehelper', 1) == 1) {
                if(MageBridgeHelper::isJoomla15()) {
                    JLoader::import('joomla.application.module.helper', JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'rewrite');
                } elseif(MageBridgeHelper::isJoomla16()) {
                    JLoader::import('joomla.application.module.helper', JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'rewrite-16');
                } else {
                    JLoader::import('joomla.application.module.helper', JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'rewrite-17');
                }
            }
        }
    }

    /*
     * Event onPrepareModuleList (used by Advanced Module Manager)
     */
    public function onPrepareModuleList(&$modules)
    {
        foreach($modules as $id => $module) {
            if(MageBridgeTemplateHelper::allowPosition($module->position) == false) {
                unset($modules[$id]);
                continue;
            } 
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
            jimport('joomla.html.parameter');
            $plugin = JPluginHelper::getPlugin('system', 'magebridgepre');
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
