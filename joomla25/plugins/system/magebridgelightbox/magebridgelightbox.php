<?php
/**
 * Joomla! MageBridge - Lightbox System plugin
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Import the parent class
jimport( 'joomla.plugin.plugin' );

/**
 * MageBridge Lightbox System Plugin
 */
class plgSystemMageBridgeLightbox extends JPlugin
{
    /**
     * Event onAfterRender
     *
     * @access public
     * @param null
     * @return null
     */
    public function onAfterDispatch()
    {
        // Dot not load if this is not the right document-class
        $document = JFactory::getDocument();
        if($document->getType() != 'html') {
            return false;
        }

        // Only do this on the frontend
        $application = JFactory::getApplication();
        if($application->isSite() == false) {
            return false;
        }

        if (JRequest::getCmd('option') == 'com_magebridge') {

            /*$body = JResponse::getBody();
            if (!empty($body)) {
                JResponse::setBody($body);
            }*/
            $library = $this->params->get('library');
            switch($library) {
                case 'lightbox2':
                    $this->jquery();
                    $this->addJs('lightbox2/js/lightbox.js');
                    $this->addCss('lightbox2/css/lightbox.css');
                    break;
                case 'lightview':
                    $this->jquery();
                    $this->addJs('lightview/js/spinners/spinners.min.js');
                    $this->addJs('lightview/js/lightview/lightview.js');
                    $this->addCss('lightview/css/lightview/lightview.css');
                    break;
                case 'easybox':
                    $this->jquery();
                    $this->addJs('easybox/easybox.min.js');
                    $this->addCss('easybox/easybox.min.css');
                case 'prettyphoto':
                    $this->jquery();
                    $this->addJs('prettyphoto/js/jquery.prettyPhoto.js');
                    $this->addScriptDeclaration('jQuery(document).ready(function(){jQuery("a[rel^=\'lightbox\']").prettyPhoto();});');
                    $this->addCss('prettyphoto/css/prettyPhoto.css');
                case 'pirobox':
                    $this->jquery();
                    $this->addJs('pirobox/js/jquery-ui-1.8.2.custom.min.js');
                    $this->addJs('pirobox/js/pirobox_extended.js');
                    $this->addScriptDeclaration('jQuery(document).ready(function(){jQuery().piroBox_ext({piro_speed : 900,bg_alpha : 0.1,piro_scroll:true});});');
                    $this->addCss('pirobox/css_pirobox/style_2/style.css'); // @todo: Extra parameter for style_2
                case 'pirobox':
            }
        }
    }

    /*
     * Helper method to load jQuery
     */
    protected function jquery()
    {
        if(JFactory::getApplication()->get('jquery') == false) {
            $this->addJs('common/jquery-1.8.1.min.js');
            JFactory::getApplication()->set('jquery', true);
        }
    }

    /*
     * Helper method to add a CSS-stylesheet
     */
    protected function addCss($css)
    {
        $css = '/media/plg_magebridgelightbox/'.$css;
        $document = JFactory::getDocument();
        $document->addStylesheet($css);
    }

    /*
     * Helper method to add a JavaScript-file
     */
    protected function addJs($js, $prototype = false)
    {
        $js = 'media/plg_magebridgelightbox/'.$js;

        // Add the script to this document
        if($prototype == false) {
            $document = JFactory::getDocument();
            $document->addScript($js);
        } else {
            $html = '<script type="text/javascript" src="'.$js.'"></script>';
            $document = JFactory::getDocument();
            $document->addCustomTag($html);
        }

        // Add the script to the whitelist so MageBridge doesn't strip it afterwards
        $application = JFactory::getApplication();
        $whitelist = $application->get('magebridge.script.whitelist');
        if (empty($whitelist)) $whitelist = array();
        $whitelist[] = $js;
        $application->set('magebridge.script.whitelist', $whitelist);
    }

    /*
     * Helper method to add a piece of JavaScript
     */
    protected function addScriptDeclaration($js)
    {
        $document = JFactory::getDocument();
        $document->addScriptDeclaration($js);
    }
}
