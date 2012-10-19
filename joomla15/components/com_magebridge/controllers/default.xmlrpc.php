<?php
/**
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Include the parent controller
jimport( 'joomla.application.component.controller' );

/**
 * MageBridge XML-RPC Controller for Joomla! 1.6
 * Example: index.php?option=com_magebridge&task=default&protocol=xmlrpc
 *
 * @package MageBridge
 */
class MageBridgeControllerDefault extends JController
{
    /* 
     * Event method
     */
    public function event($authentication = array(), $event = '', $arguments = array())
    {
        // Reset the debug-namespace to joomla
        MageBridgeModelDebug::getDebugOrigin(MageBridgeModelDebug::MAGEBRIDGE_DEBUG_ORIGIN_JOOMLA_XMLRPC);

        if($this->authenticate($authentication) == false) {
            MageBridgeModelDebug::getInstance()->warning( 'XML-RPC plugin: Failed to start event '.$event);
		    return false;
        }
            
        if(!empty($event)) {
            MageBridgeModelDebug::getInstance()->trace( 'XML-RPC: firing mageEvent ', $event);
            //MageBridgeModelDebug::getInstance()->trace( 'XML-RPC: plugin arguments', $arguments );

            JPluginHelper::importPlugin('magento');
            $application = JFactory::getApplication();

            $result = $application->triggerEvent($event, array($arguments));
            // @todo: Translate result automatically into an array

		    return true;

        } else {
            MageBridgeModelDebug::getInstance()->error( 'XML-RPC plugin: empty mageEvent' );
		    return false;
        }
	}

	/**
     * Logs a MageBridge message on the Joomla! side
	 */
	public function log($authentication = array(), $type = MAGEBRIDGE_DEBUG_NOTICE, $message = null, $section = null, $time = null)
	{
        MageBridgeModelDebug::getInstance()->add( $type, $message, $section, MAGEBRIDGE_DEBUG_ORIGIN_MAGENTO, $time );
		return true;
	}

    /*
     * Helper-method to authenticate this call
     */
    private function authenticate($auth)
    {
        if(!empty($auth) && !empty($auth['api_user']) && !empty($auth['api_key'])) {

            $api_user = MageBridgeEncryptionHelper::decrypt($auth['api_user']);
            $api_key = MageBridgeEncryptionHelper::decrypt($auth['api_key']);

            if($api_user != MagebridgeModelConfig::load('api_user')) { 
                MageBridgeModelDebug::getInstance()->error( 'XML-RPC plugin: API-authentication failed: Username did not match');
            } elseif($api_key != MagebridgeModelConfig::load('api_key')) {
                MageBridgeModelDebug::getInstance()->error( 'XML-RPC plugin: API-authentication failed: Key did not match');
            } else {
                MageBridgeModelDebug::getInstance()->notice( 'XML-RPC plugin: API-authentication succeeded' );
                return true;
            }
        } 
        return false;
    }
}
