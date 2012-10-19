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

// Check to ensure this file is included in Joomla!  
defined('_JEXEC') or die();

// Require the parent view
jimport( 'joomla.application.component.view');

/**
 * HTML View class 
 *
 * @static
 * @package MageBridge
 */
class MageBridgeView extends JView
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        //JToolBarHelper::help( 'screen.magebridge.usage' );
        $this->addMenuItems();

        $this->addCss('backend.css', 'media/com_magebridge/css/');
        if (JRequest::getCmd('view') == 'home') {
            $this->addCss('backend-home.css', 'media/com_magebridge/css/');
        }

        if (!MageBridgeHelper::isJoomla15()) {
            $this->addCss('backend-j16.css', 'media/com_magebridge/css/');
        }

        // If we detect the API is down, report it
        $bridge = MageBridgeModelBridge::getInstance();
        if ($bridge->getApiState() != null) {

            $message = null;
            switch(strtoupper($bridge->getApiState())) {

                case 'EMPTY METADATA':
                    $message = JText::_('The bridge-data arrived empty in Magento.' );
                    break;

                case 'LICENSE FAILED':
                    $message = JText::sprintf('The Joomla! support-key is different from the one in Magento (%s).', $bridge->getApiExtra());
                    break;

                case 'AUTHENTICATION FAILED':
                    $message = JText::_('API authentication failed. Please check your API-user and API-key.' );
                    break;

                case 'INTERNAL ERROR':
                    $help = MageBridgeHelper::getHelpText('troubleshooting');
                    $message = JText::sprintf('Bridge encountered a 500 Internal Server Error. Please check out the %s for more information.', $help );
                    break;

                case 'FAILED LOAD':
                    $help = MageBridgeHelper::getHelpText('faq-troubleshooting:api-widgets');
                    $message = JText::sprintf('Failed to load API-widgets. Please check out the %s for more information.', $help );
                    break;

                default:
                    $message = JText::_('An API-error occurred: '.$bridge->getApiState());
                    break;
            }

            MageBridgeModelDebug::getInstance()->feedback($message);
        }

        // If debugging is enabled report it
        if (MagebridgeModelConfig::load('debug') == 1 && JRequest::getCmd('tmpl') != 'component' && in_array(JRequest::getCmd('view'), array('config', 'home'))) {
            MageBridgeModelDebug::getInstance()->feedback('Debugging is currently enabled');
        }

        parent::display($tpl);
    }

    /*
     * Helper-method to set the page title
     *
     * @param string $title
     * @return null
     */
    protected function setTitle($title)
    {
        JToolBarHelper::title( JText::_('MageBridge') . ': ' . JText::_( $title ), 'yireo' );
        return;
    }

    /*
     * Helper-method to add all the submenu-items for this component
     *
     * @param null
     * @return null
     */
    protected function addMenuItems()
    {
		$menu = JToolBar::getInstance('submenu');
        $items = array(
            'Home' => 'home',
            'Configuration' => 'config',
            'Store Relations' => 'stores',
            'Product Relations' => 'products',
            'Usergroup Relations' => 'usergroups',
            'Connectors' => 'connectors',
            'URL Replacements' => 'urls',
            'Users' => 'users',
            'System Check' => 'check',
            'Logs' => 'logs',
            'Update' => 'update',
        );
			
        foreach ($items as $title => $view) {

            // Skip this view, if it does not exist on the filesystem
            if (!is_dir(JPATH_COMPONENT.'/views/'.$view)) continue;

            // Skip this view, if ACLs prevent access to it
            if (MageBridgeAclHelper::isAuthorized($view, false) == false) continue;

            // Add the view
            $active = (JRequest::getCmd('view') == $view) ? true : false;
            $url = 'index.php?option=com_magebridge&view='.$view;
    		$menu->appendButton(JText::_($title), $url, $active);
        }
        return;
    }

    /*
     * Helper-method to add all the submenu-items for this component
     *
     * @param null
     * @return null
     */
    protected function addCss($file, $path)
    {
        if (MageBridgeHelper::isJoomla15()) {
            JHTML::stylesheet($file, $path);
        } else {
            JHTML::stylesheet($path.$file);
        }
    }
}
