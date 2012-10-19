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
defined('_JEXEC') or die('Restricted access');
        
// Import the general module-helper
jimport('joomla.application.module.helper');

/*
 * Helper for usage in Joomla!/MageBridge modules and templates
 */
class MageBridgeModuleHelper extends JModuleHelper
{
    /*
     * Load all MageBridge-modules
     *
     * @param null
     * @return array
     */
    public static function load()
    {
        if (MagebridgeModelConfig::load('preload_all_modules') == 0 && JRequest::getInt('Itemid') != 0) {
            static $modules = null;
            if (is_array($modules) == false) {
                $modules = JModuleHelper::_load();
                foreach ($modules as $index => $module) {
                    if (strstr($module->module, 'mod_magebridge') == false) {
                        unset($modules[$index]);
                    }
                }
            }
            return $modules;
        }

        $application = JFactory::getApplication();
        $db = JFactory::getDBO();

        $where = array();
        $where[] = 'm.published = 1';
        $where[] = 'm.module LIKE "mod_magebridge%"';
		$where[] = 'm.client_id = '. (int)$application->getClientId();

		$query = 'SELECT m.*'
			. ' FROM #__modules AS m'
			. ' LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id'
			. ' WHERE '. implode(' AND ', $where)
			. ' ORDER BY m.position, m.ordering';

        $db->setQuery($query);
        $modules = $db->loadObjectList();
        return $modules;
    }

    /*
     * Fetch the content from the bridge
     *
     * @param string $function
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function getCall($function, $name, $arguments = null)
    {
        // Include the MageBridge bridge
        $bridge = MageBridgeModelBridge::getInstance();

        // Build the bridge
        $build = $bridge->build();
        return $bridge->$function($name, $arguments);
    }
}
