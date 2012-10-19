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
require_once JPATH_COMPONENT.'/view.php';

/**
 * HTML View class 
 *
 * @static
 * @package MageBridge
 */
class MageBridgeViewHome extends MageBridgeView
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        $this->setTitle( 'Control Panel' );

        $lang = JFactory::getLanguage();
        $alignment = ($lang->isRTL()) ? 'right' : 'left';
        $this->assignRef( 'alignment', $alignment );

        $icons = array();
        $icons[] = $this->icon( 'config', 'Configuration', 'config.png');
        $icons[] = $this->icon( 'stores', 'Store Conditions', 'store.png');
        $icons[] = $this->icon( 'products', 'Product Relations', 'product.png');
        $icons[] = $this->icon( 'connectors', 'Connectors', 'connect.png');
        $icons[] = $this->icon( 'users', 'Users', 'user.png');
        $icons[] = $this->icon( 'check', 'System Check', 'cpanel.png');
        $icons[] = $this->icon( 'logs', 'Logs', 'info.png');
        $icons[] = $this->icon( 'update', 'Update', 'install.png');
        $icons[] = $this->icon( 'cache', 'Empty Cache', 'trash.png');
        $icons[] = $this->icon( 'magento', 'Magento Admin', 'magento.png', '_blank');
        $icons[] = $this->icon( 'tutorials', 'Tutorials', 'tutorials.png', '_blank');
        $icons[] = $this->icon( 'forum', 'Forum', 'forum.png', '_blank');
        $this->assignRef( 'icons', $icons );

        //jimport('joomla.html.pane');
        //$pane = JPane::getInstance('sliders');
        //$this->assignRef('pane', $pane);

        $current_version = MageBridgeUpdateHelper::getComponentVersion();
        $this->assignRef( 'current_version', $current_version );

        $changelog_url = 'http://www.yireo.com/tutorials/magebridge/updates/975-magebridge-changelog';
        $this->assignRef( 'changelog_url', $changelog_url );

        $jed_url = 'http://extensions.joomla.org/extensions/bridges/e-commerce-bridges/9440';
        $this->assignRef( 'jed_url', $jed_url );

        $backend_feed = MagebridgeModelConfig::load('backend_feed');
        $this->assignRef( 'backend_feed', $backend_feed);
        if ($backend_feed == 1) {

            $this->ajax('index.php?option=com_magebridge&view=home&format=ajax&layout=feeds', 'latest_news');
            $this->ajax('index.php?option=com_magebridge&view=home&format=ajax&layout=promotion', 'promotion');

            $document = JFactory::getDocument();
            if (JURI::getInstance()->isSSL() == true) {
                $document->addStylesheet('https://fonts.googleapis.com/css?family=Just+Me+Again+Down+Here');
            } else {
                $document->addStylesheet('http://fonts.googleapis.com/css?family=Just+Me+Again+Down+Here');
            }
        }

        parent::display($tpl);
    }

    /*
     * Helper-method to construct a specific icon
     *
     * @param string $view
     * @param string $text
     * @param string $image
     * @param string $folder
     * @return null
     */
    public function icon($view, $text, $image, $target = null, $folder = null)
    {
        $application = JFactory::getApplication();
        if (empty($folder)) {
            $folder = '../media/com_magebridge/images/';
        }

        $icon = array();
        $icon['link'] = JRoute::_( 'index.php?option=com_magebridge&view='.$view );
        $icon['text'] = JText::_($text);
        $icon['target'] = $target;
        $icon['icon'] = JHTML::_('image.site', 'icon-48-'.$image, $folder, null, null, $icon['text'] );
        return $icon;
    }

    /*
     * Add the AJAX-script to the page
     *
     * @param string $url
     * @param string $div
     * @return null
     */
    public function ajax($url, $div)
    {
        JHTML::_('behavior.mootools');

        if (MageBridgeHelper::isJoomla15()) {
            $script = "<script type=\"text/javascript\">\n"
                . "window.addEvent('domready', function(){\n"
                . "    var MBajax = new Ajax( '".$url."', {onSuccess: function(r){\n"
                . "        $('".$div."').innerHTML = r;\n"
                . "    }});\n"
                . "    MBajax.request();\n"
                . "});\n"
                . "</script>";
        } else {
            $script = "<script type=\"text/javascript\">\n"
                . "window.addEvent('domready', function(){\n"
                . "    var MBajax = new Request({\n"
                . "        url: '".$url."', \n"
                . "        onComplete: function(r){\n"
                . "            $('".$div."').innerHTML = r;\n"
                . "        }\n"
                . "    }).send();\n"
                . "});\n"
                . "</script>";
        }

        $document = JFactory::getDocument();
        $document->addCustomTag( $script );
    }
}
