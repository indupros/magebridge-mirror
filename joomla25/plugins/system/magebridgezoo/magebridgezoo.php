<?php
/**
 * Joomla! MageBridge - ZOO System plugin
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
include_once JPATH_SITE.'/components/com_magebridge/helpers/loader.php';

/**
 * MageBridge ZOO System Plugin
 */
class plgSystemMageBridgeZoo extends JPlugin
{
    /**
     * Event onAfterRender
     *
     * @access public
     * @param null
     * @return null
     */
    public function onAfterRender()
    {
        // Don't do anything if MageBridge is not enabled 
        if ($this->isEnabled() == false) return false;

        if (JRequest::getCmd('option') == 'com_zoo') {

            $body = JResponse::getBody();

            // Check for Magento CMS-tags
            if (preg_match('/\{\{([^}]+)\}\}/', $body)) {

                // Get system variables
                $bridge = MageBridgeModelBridge::getInstance();

                // Include the MageBridge register
                $key = md5(var_export($body, true)).':'.JRequest::getCmd('option').':'.$row->id;
                $text = MageBridgeEncryptionHelper::base64_encode($body);

                // Conditionally load CSS
                if ($this->getParams()->get('load_css') == 1 || $this->getParams()->get('load_js') == 1) {
                    $bridge->register('headers');
                }

                // Build the bridge
                $segment_id = $bridge->register('filter', $key, $text);
                $bridge->build();
            
                // Load CSS if needed
                if ($this->getParams()->get('load_css') == 1) {
                    $bridge->setHeaders('css');
                }

                // Load JavaScript if needed
                if ($this->getParams()->get('load_js') == 1) {
                    $bridge->setHeaders('js');
                }

                // Get the result from the bridge
                $result = $bridge->getSegmentData($segment_id);
                $result = MageBridgeEncryptionHelper::base64_decode($result);
                
                // Only replace the original if the new content exists
                if (!empty($result)) {
                    $body = $result;
                }
            }

            if (!empty($body)) {
                JResponse::setBody($body);
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
        if (MageBridgeHelper::isJoomla16()) {
            return $this->params;
        } else {
            $plugin = JPluginHelper::getPlugin('system', 'magebridgeyoo');
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
        if (is_file(JPATH_SITE.'/components/com_magebridge/models/config.php')) {
            return true;
        }
        return false;
    }
}
