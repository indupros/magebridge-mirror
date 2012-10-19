<?php
/**
 * Joomla! module MageBridge: Newsletter block
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Read the parameters
$layout = $params->get('layout', 'default');

// Call the helper
require_once (dirname(__FILE__).'/helper.php');
$block = modMageBridgeNewsletterHelper::build($params);

// Get the current user
$user = JFactory::getUser();

// Set the form URL
$form_url = MageBridgeUrlHelper::route('newsletter/subscriber/new');

// Include the layout-file
require(JModuleHelper::getLayoutPath('mod_magebridge_newsletter', $layout));
