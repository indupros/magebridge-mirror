<?php
/**
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die( 'Restricted access' );

// Require all the neccessary libraries
require_once JPATH_COMPONENT.'/helpers/loader.php';

// Handle the SSO redirect
if (JRequest::getInt('sso') == 1) {
    JRequest::setVar('task', 'ssoCheck');
}

// Handle direct proxy requests
if (JRequest::getVar('url')) {
    JRequest::setVar('task', 'proxy');
}

// Initialize debugging
MagebridgeModelDebug::init();

// Require the controller
$view = JRequest::getCmd('controller');
if ($view == 'jsonrpc') {
    JRequest::setVar('task', JRequest::getCmd('task', '', 'get'));
    require_once JPATH_COMPONENT.'/controllers/default.jsonrpc.php';
    $controller = new MageBridgeControllerJsonrpc( );
} else {
    require_once JPATH_COMPONENT.'/controller.php';
    $controller = new MageBridgeController( );
}

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
