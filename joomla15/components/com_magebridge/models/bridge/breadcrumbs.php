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

/*
 * Main bridge class
 */
class MageBridgeModelBridgeBreadcrumbs extends MageBridgeModelBridgeSegment
{
    /*
     * Singleton 
     *
     * @param string $name
     * @return object
     */
    public static function getInstance($name = null)
    {
        return parent::getInstance('MageBridgeModelBridgeBreadcrumbs');
    }

    /*
     * Load the data from the bridge
     */
    public function getResponseData()
    {
        return MageBridgeModelRegister::getInstance()->getData('breadcrumbs');
    }

    /*
     * Method to set the breadcrumbs
     */
    public function setBreadcrumbs()
    {
        static $set = false;
        if ($set == true) {
            return true;
        } else {
            $set = true;
        }

        if (JRequest::getCmd('view') != 'root') {
            return true;
        }

        $application = JFactory::getApplication();
        $pathway = $application->getPathway();
        $data = $this->getResponseData();

        if (!is_array($data)) {
            $data = array();
        }

        if (MageBridgeTemplateHelper::isCartPage()) {
            $pathway->addItem(JText::_('COM_MAGEBRIDGE_SHOPPING_CART'), MageBridgeUrlHelper::route('checkout/cart'));

        } else if (MageBridgeTemplateHelper::isCheckoutPage()) {
            $pathway->addItem(JText::_('COM_MAGEBRIDGE_SHOPPING_CART'), MageBridgeUrlHelper::route('checkout/cart'));
            $pathway->addItem(JText::_('COM_MAGEBRIDGE_CHECKOUT'), MageBridgeUrlHelper::route('checkout'));
        }

        @array_shift($data);
        if (empty($data)) {
            return true;
        }

        $pathway_items = array();
        foreach ($pathway->getPathway() as $pathway_item) {
            $pathway_item->link = preg_replace('/\/$/', '', JURI::root()).JRoute::_($pathway_item->link);
            $pathway_items[] = $pathway_item;
        }
        @array_pop($pathway_items);

        foreach ($data as $item) {

            // Do not add the current link
            if (MageBridgeUrlHelper::current() == $item['link']) continue;

            // Loop through the current pathway-items to prevent double links
            if (!empty($pathway_items)) {
                $match = false;
                foreach ($pathway_items as $pathway_item) {
                    if ($pathway_item->link == $item['link']) $match = true;
                }
                if ($match == true) continue;
            }

            $pathway_item = (object)null;
            $pathway_item->name = JText::_($item['label']);
            $pathway_item->link = $item['link'];
            $pathway_item->magento = 1;
            $pathway_items[] = $pathway_item;

        }

        $pathway->setPathway($pathway_items);

        return true;
    }
}
