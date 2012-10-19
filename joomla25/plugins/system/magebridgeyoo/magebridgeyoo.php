<?php
/**
 * Joomla! MageBridge - YOOtheme System plugin
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
 * MageBridge System Plugin
 */
class plgSystemMageBridgeYoo extends JPlugin
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
        if ($this->isEnabled() == false) return false;

        // Load variables
        $application = JFactory::getApplication();

        // Don't do anything in other applications than the frontend
        if ($application->isSite() == false) return false;

        // Load the whitelist settings
        $whitelist = $application->get('magebridge.script.whitelist');
        if (empty($whitelist)) $whitelist = array();
        if ($this->getParams()->get('enable_js_widgetkit', 1) == 1) $whitelist[] = '/widgetkit/';
        if ($this->getParams()->get('enable_js_warp', 1) == 1) $whitelist[] = '/warp/js/';
        if ($this->getParams()->get('enable_js_template', 1) == 1) $whitelist[] = '/js/template.js';
        $application->set('magebridge.script.whitelist', $whitelist);

        // Read the template-related files
        $ini = JPATH_THEMES.'/'.$application->getTemplate().'/params.ini';
        $conf = JPATH_THEMES.'/'.$application->getTemplate().'/config';
        $xml = JPATH_THEMES.'/'.$application->getTemplate().'/templateDetails.xml';
        $ini_content = @file_get_contents($ini);
        $conf_content = @file_get_contents($conf);

        // WARP-usage of "config" file
        if (!empty($conf_content)) {

            $data = json_decode($conf_content, true);
            if (is_array($data)) {
                $Itemid = JRequest::getInt('Itemid');
                $profileGet = JRequest::getCmd('profile');
                $profileDefault = (isset($data['profile_default'])) ? $data['profile_default'] : null;

                if (!empty($profileGet)) {
                    $profile = $profileGet;
                    MageBridgeTemplateHelper::load('css', 'profile-'.$profile.'.css');
                
                } else if (isset($data['profile_map'][$Itemid])) {
                    $profileMapped = $data['profile_map'][$Itemid];
                    if (!empty($profileMapped)) {
                        $profile = $profileMapped;
                        MageBridgeTemplateHelper::load('css', 'profile-'.$profile.'.css');
                    }
                } else if (!empty($profileDefault)) {
                    $profile = $profileDefault;
                    MageBridgeTemplateHelper::load('css', 'profile-'.$profile.'.css');
                }

                if (isset($data['profile_data'][$profile]['color'])) {
                    $color = $data['profile_data'][$profile]['color'];
                } else if (isset($data['profile_data']['default']['color'])) {
                    $color = $data['profile_data']['default']['color'];
                }

                if (!empty($color)) {
                    MageBridgeTemplateHelper::load('css', 'color-'.$color.'.css');
                }

                if (isset($data['profile_data'][$profile]['style'])) {
                    $style = $data['profile_data'][$profile]['style'];
                } else if (isset($data['profile_data']['default']['style'])) {
                    $style = $data['profile_data']['default']['style'];
                }

                if (!empty($style)) {
                    if ($style == 'default') $style = $profileDefault;
                    MageBridgeTemplateHelper::load('css', 'style-'.$style.'.css');
                }
            }

        // Pre-WARP reading of Joomla! parameters
        } else {

            // Create the parameters object
            jimport('joomla.html.parameter');
            $params = new JParameter($ini_content, $xml);

            // Load a specific stylesheet per color
            $color = $params->get('color');
            if (!empty($color)) {
                MageBridgeTemplateHelper::load('css', 'color-'.$color.'.css');
            }

            // Load a specific stylesheet per style
            $style = $params->get('style');
            if (!empty($style)) {
                MageBridgeTemplateHelper::load('css', 'style-'.$style.'.css');
            }
        }
    }

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

        $disable_js_mootools = MagebridgeModelConfig::load('disable_js_mootools');
        if (MageBridgeTemplateHelper::hasPrototypeJs() && $disable_js_mootools == 1) {

            $body = JResponse::getBody();
            $body = preg_replace('/Warp.Settings(.*);/', '', $body);
            JResponse::setBody($body);
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
        if (!MageBridgeHelper::isJoomla15()) {
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
