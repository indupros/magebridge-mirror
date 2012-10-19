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

// Include the parent class
jimport( 'joomla.application.component.view');

/**
 * HTML View class
 * @package MageBridge
 */
class MageBridgeView extends JView
{
    protected $block_name = null;
    protected $block = null;
    protected $block_built = false;

    /*
     * Method to display the requested view
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Build the block
        $block = $this->build();
        if (!empty($block)) {
            $this->block = $this->addFixes($block);
        }

        // Asign this block to the template
        $this->assignRef('block', $this->block);

        // Display the view 
        parent::display($tpl);

        // Set debug-data
        $this->addDebug();

    }

    /*
     * Helper-method to build the bridge
     * 
     * @param string $block_name
     * @return null
     */
    public function build()
    {
        static $block = null;
        if (empty($block)) {

            // Get the register and add all block-requirements to it
            $register = MageBridgeModelRegister::getInstance();
            $register->add('headers');
            $register->add('block', $this->block_name);

            // Only request breadcrumbs if we are loading another page than the homepage
            $request = MageBridgeUrlHelper::getRequest();
            if (!empty($request)) {
                $register->add('breadcrumbs');
            }

            // Build the bridge
            MageBridgeModelDebug::getInstance()->notice('Building view');
            $bridge = MageBridge::getBridge();
            $bridge->build();
            $bridge->setHeaders();

            // Add things for the frontend specifically
            $application = JFactory::getApplication();
            if ($application->isSite()) {
                $bridge->setBreadcrumbs();
            }

            // Query the bridge for the block
            $block = $bridge->getBlock($this->block_name);

            // Empty blocks
            if (empty($block)) {
                MageBridgeModelDebug::getInstance()->warning( 'JView: Empty block: '.$this->block_name );
                $block = JText::_($this->getOfflineMessage());
            }
        }

        return $block;
    }

    /*
     * Helper-method to fetch add block to the bridge-register
     * 
     * @param string $block_name
     * @return null
     */
    public function setBlock($block_name)
    {
        // Set the block-name for internal usage
        $this->block_name = $block_name;
    }

    /*
     * Helper-method to set the request as REQUEST-variable
     *
     * @param string $request
     * @return null
     */
    public function setRequest($request)
    {
        $segments = explode( '/', $request);
        if (!empty($segments)) {
            foreach ($segments as $index => $segment) {
                $segments[$index] = preg_replace('/^([a-zA-Z0-9]+)\:/', '\1-', $segment);
            }
            $request = implode('/', $segments);
        }

        MageBridgeUrlHelper::setRequest($request);
    }

    /*
     * Helper-method to set the debugging information
     *
     * @param null
     * @return null
     */
    public function addDebug()
    {
        if (MageBridgeModelDebug::isDebug() && MagebridgeModelConfig::load('debug_bar')) {

            $debug = MageBridgeModelDebug::getInstance();
            $bridge = MageBridgeModelBridge::getInstance();
            $register = MageBridgeModelRegister::getInstance();
            $request = MageBridgeUrlHelper::getRequest();

            if (MagebridgeModelConfig::load('debug_bar_request')) {
                $url = $bridge->getMagentoUrl().$request;
                if (empty($request)) $request = '[empty]';

                $Itemid = JRequest::getInt('Itemid');
                $root_item = MageBridgeUrlHelper::getRootItem();
                $root_item_id = ($root_item) ? $root_item->id : false;
                $menu_message = 'Menu-Item: '.$Itemid;
                if ($root_item_id == $Itemid) $menu_message .= ' (Root Menu-Item)';

                JError::raiseNotice( 'notice', $menu_message);
                JError::raiseNotice( 'notice', JText::sprintf( 'Page request: %s', (!empty($request)) ? $request : '[empty]'));
                JError::raiseNotice( 'notice', JText::sprintf( 'Received request: %s', $bridge->getMageConfig('request')));
                JError::raiseNotice( 'notice', JText::sprintf( 'Received referer: %s', $bridge->getMageConfig('referer')));
                JError::raiseNotice( 'notice', JText::sprintf( 'Current referer: %s', $bridge->getHttpReferer()));
                JError::raiseNotice( 'notice', JText::sprintf( 'Magento request: <a href="%s" target="_new">%s</a>', $url, $url ));
                JError::raiseNotice( 'notice', JText::sprintf( 'Magento session: %s', $bridge->getMageSession()));

                if (MageBridgeTemplateHelper::isCategoryPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isCategoryPage() == TRUE'));
                if (MageBridgeTemplateHelper::isProductPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isProductPage() == TRUE'));
                if (MageBridgeTemplateHelper::isCatalogPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isCatalogPage() == TRUE'));
                if (MageBridgeTemplateHelper::isCustomerPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isCustomerPage() == TRUE'));
                if (MageBridgeTemplateHelper::isCartPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isCartPage() == TRUE'));
                if (MageBridgeTemplateHelper::isCheckoutPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isCheckoutPage() == TRUE'));
                if (MageBridgeTemplateHelper::isSalesPage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isSalesPage() == TRUE'));
                if (MageBridgeTemplateHelper::isHomePage()) JError::raiseNotice( 'notice', JText::_('MageBridgeTemplateHelper::isHomePage() == TRUE'));
            }

            if (MagebridgeModelConfig::load('debug_bar_store')) {
                JError::raiseNotice( 'notice', JText::sprintf( 'Magento store loaded: %s', $bridge->getMageConfig('store_name')));
            }

            if (MagebridgeModelConfig::load('debug_bar_parts')) {
                $i = 0;
                foreach ($register->getRegister() as $segment) {
                    if (isset($segment['status']) && $segment['status'] == 1) {
                        switch ($segment['type']) {
                            case 'breadcrumbs': 
                            case 'meta': 
                            case 'debug': 
                            case 'headers': 
                            case 'events': 
                                JError::raiseNotice('notice', JText::sprintf('Magento [%d]: %s', $i, ucfirst($segment['type'])));
                                break;
                            case 'api': 
                                JError::raiseNotice('notice', JText::sprintf('Magento [%d]: API resource "%s"', $i, $segment['name']));
                                break;
                            case 'block': 
                                JError::raiseNotice('notice', JText::sprintf('Magento [%d]: Block "%s"', $i, $segment['name']));
                                break;
                            default:
                                $name = (isset($segment['name'])) ? $segment['name'] : null;
                                $type = (isset($segment['type'])) ? $segment['type'] : null;
                                JError::raiseNotice('notice', JText::sprintf('Magento [%d]: type %s, name %s', $i, $type, $name));
                                break;
                        }
                        $i++;
                    }
                }
            }

            $this->assignRef('debug', $debug->getData());

        }
    }

    /*
     * Helper-method to add specific fixes to the current page
     *
     * @param string $html
     * @return string
     */
    public function addFixes($html)
    {
        // Check for a template-override of this file
        $application = JFactory::getApplication();
        $file = JPATH_BASE.'/templates/'.$application->getTemplate().'/html/com_magebridge/fixes.php';
        if (!file_exists($file)) {
            $file = JPATH_SITE.'/components/com_magebridge/views/fixes.php';
        }
        
        // Include the file and allow $html to be altered
        require_once $file;
        return $html;
    }

    /*
     * Helper-method to get the offline message
     * 
     * @param null
     * @return string
     */
    public function getOfflineMessage()
    {
        return MagebridgeModelConfig::load('offline_message');
    }
}

