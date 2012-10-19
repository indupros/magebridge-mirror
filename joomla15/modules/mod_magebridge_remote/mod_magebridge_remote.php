<?php
/**
 * Joomla! module MageBridge: Remote Block
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 */
        
// No direct access
defined('_JEXEC') or die('Restricted access');

// Import the MageBridge autoloader
require_once JPATH_SITE.DS.'components'.DS.'com_magebridge'.DS.'helpers'.DS.'loader.php';

// Read the parameters
$layout = $params->get('layout', 'ajax');

// Call the helper
require_once (dirname(__FILE__).DS.'helper.php');
$blockName = modMageBridgeRemoteHelper::blockName($params);

// Build the block
modMageBridgeRemoteHelper::ajaxbuild($params);

// Include the layout-file
require(JModuleHelper::getLayoutPath('mod_magebridge_remote', $layout));
