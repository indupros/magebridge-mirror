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
 * Helper for handling the register
 */
class MageBridgeRegisterHelper extends JModuleHelper
{
    /*
     * Pre-register the modules, because they are loaded after the component output
     *
     * @param null
     * @return null
     */
    public static function preload()
    {
        // Don't preload anything if this is the API
        if (MageBridge::isApiPage() == true) {
            return null;
        }

        // Don't preload anything if the current output contains only the component-area
        if (in_array(JRequest::getCmd('tmpl'), array('component', 'raw'))) {
            return null;
        }

        // Fetch all the current modules
        $modules = MageBridgeModuleHelper::load();
        $register = MageBridgeModelRegister::getInstance();

        // Loop through all the available Joomla! modules
        if (!empty($modules)) {
            foreach ($modules as $module) {

                // Check the name to see if this is a MageBridge-related module
                if (preg_match('/^mod_magebridge_/', $module->module)) {

                    // Initialize variables
                    $type = null;
                    $name = null;
                    jimport('joomla.html.parameter');
                    $params = new JParameter($module->params);
                    $conf = JFactory::getConfig();
                    $user = JFactory::getUser();

                    // Check whether caching returns a valid module-output
                    if ($params->get('cache', 0) && $conf->getValue( 'config.caching' )) {
                        $cache = JFactory::getCache($module->module);
                        $cache->setLifeTime($params->get('cache_time', $conf->getValue('config.cachetime')*60));
                        $contents =  $cache->get(array('JModuleHelper', 'renderModule'), array($module, $params->toArray()), $module->id. $user->get('aid', 0));

                        // If the contents are not empty, there is a cached version so we skip this
                        if (!empty($contents)) {
                            continue;
                        }

                        // If the contents are empty, make sure we have a fresh start
                        // @todo: Why was this needed? This causes under certain circumstances numerous bridge-calls which is bad.
                        //if (empty($contents)) {
                        //    $cache->clean();
                        //}
                    }

                    // If the layout is AJAX-ified, do not fetch the block at all
                    if ($params->get('layout') == 'ajax') {
                        continue;
                    }

                    // Try to include the helper-file
                    if (is_file(JPATH_SITE.'/modules/'.$module->module.'/helper.php')) {
                        $module_file = JPATH_SITE.'/modules/'.$module->module.'/helper.php';
                    } else if (is_file(JPATH_ADMINISTRATOR.'/modules/'.$module->module.'/helper.php')) {
                        $module_file = JPATH_ADMINISTRATOR.'/modules/'.$module->module.'/helper.php';
                    }

                    // If there is a module-file, include it
                    if (!empty($module_file) && is_file($module_file)) {

                        require_once $module_file;

                        // Construct and detect the module-class
                        $class = preg_replace( '/_([a-z]{1})/', '\1', $module->module).'Helper';
                        if (class_exists($class)) {

                            // Instantiate the class and check for the "register" method
                            $o = new $class();
                            if (method_exists($o, 'register')) {

                                // Fetch the requested tasks
                                $requests = $o->register($params);
                                if (is_array($requests) && count($requests) > 0) {
                                    foreach ($requests as $request) {

                                        // Add each requested task to the MageBridge register
                                        if (!empty($request[2])) {
                                            $register->add($request[0], $request[1], $request[2]);
                                        } else if (!empty($request[1])) {
                                            $register->add($request[0], $request[1]);
                                        } else {
                                            $register->add($request[0]);
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
