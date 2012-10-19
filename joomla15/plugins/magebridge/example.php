<?php
/**
 * Joomla! MageBridge - Magento plugin
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
require_once JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'helpers'.DS.'loader.php';

/**
 * MageBridge Example Plugin
 */
class plgMagebridgeExample extends JPlugin
{
    /**
     * Event onBeforeBuildMageBridge
     *
     * @access public
     * @param null
     * @return null
     */
    public function onBeforeBuildMageBridge()
    {
        // Get the current Magento request
        $request = MageBridgeUrlHelper::getRequest();

        // Get the current MageBridge register
        $register = MageBridgeModelRegister::getInstance();

        // Check for the checkout-page
        if($request == 'checkout/onepage') {
            $register->add('mystuff', null, array('test1', 'test2', 'test3'));
        }
    }
}
