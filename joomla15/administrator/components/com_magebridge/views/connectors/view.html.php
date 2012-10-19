<?php
/*
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
 */
class MageBridgeViewConnectors extends MageBridgeView
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Initialize the view
        $this->setTitle('Connectors');
        
        // Set buttons in the toolbar
        MageBridgeToolBarHelper::help('magebridge.connectors');
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::editListX();

        // Initialize common variables
        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' ).'-connectors';

        // Handle the filters
        $filter_type = $application->getUserStateFromRequest( $option.'filter_type', 'filter_type', '', 'word' );
        $filter_state = $application->getUserStateFromRequest( $option.'filter_state', 'filter_state', '', 'word' );
        $filter_order = $application->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'p.ordering', 'cmd' );
        $filter_order_Dir = $application->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir',    '', 'word' );

        // Get data from the model
        $items = $this->get( 'Data');
        $total = $this->get( 'Total');
        $pagination = $this->get( 'Pagination' );

        // filters
        $options = array( 
            array( 'value' => '', 'text' => '- Select Type -' ),
            array( 'value' => 'store', 'text' => 'Store Connectors' ),
            array( 'value' => 'product', 'text' => 'Product Connectors' ),
            array( 'value' => 'profile', 'text' => 'Profile Connectors' ),
        );
        $javascript = 'onchange="document.adminForm.submit();"';
        $lists['type']    = JHTML::_('select.genericlist', $options, 'filter_type', $javascript, 'value', 'text', $filter_type );
        $lists['state']    = JHTML::_('grid.state',  $filter_state );

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // Prepare the items for display
        if (!empty($items)) {
            foreach ($items as $index => $item) {

                if ($item->type == 'product') {
                    $object = MageBridgeConnectorProduct::getInstance()->getConnectorObject($item);
                } else if ($item->type == 'profile') {
                    $object = MageBridgeConnectorProfile::getInstance()->getConnectorObject($item);
                } else {
                    $object = MageBridgeConnectorStore::getInstance()->getConnectorObject($item);
                }

                if (is_object($object)) {
                    $item->enabled = $object->isEnabled();
                } else {
                    $item->enabled = false;
                }
                $item->edit_link = 'index.php?option=com_magebridge&view=connector&task=edit&cid[]='.$item->id;
                $items[$index] = $item;
            }
        }

        $user = JFactory::getUser();
        $this->assignRef('user', $user);
        $this->assignRef('lists', $lists);
        $this->assignRef('items', $items);
        $this->assignRef('pagination', $pagination);
        
        parent::display($tpl);
    }
}
