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
class MageBridgeViewStores extends MageBridgeView
{
    /*
     * Method to prepare the content for display
     */
	public function display($tpl = null)
	{
        // Initialize the view
        $this->setTitle('Store Relations');

        // Set toolbar items for the page
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();

        // Initialize common variables
        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' ).'-stores';

        // Handle the filters
		$filter_state = $application->getUserStateFromRequest( $option.'filter_state', 'filter_state', '', 'word' );
		$filter_order = $application->getUserStateFromRequest( $option.'filter_order', 'filter_order', 's.ordering', 'cmd' );
		$filter_order_Dir = $application->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir',	'', 'word' );

		// Get data from the model
		$items = $this->get( 'Data');
		$total = $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

        // Prepare the items for display
        if (!empty($items)) {
            foreach ($items as $index => $item) {
                $item->edit_link = 'index.php?option=com_magebridge&view=store&task=edit&cid[]='.$item->id;
                $items[$index] = $item;
            }
        }

        // Get the current values
        $default = $this->getDefault();

        $user = JFactory::getUser();
		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('items', $items);
		$this->assignRef('default', $default);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}

    public function getDefault()
    {
        // Load the configuration values
        $storegroup = MageBridgeModelConfig::load('storegroup');
        $storeview = MageBridgeModelConfig::load('storeview');

        if (!empty($storeview)) {
            $default = array(
                'name' => $storeview,
                'title' => '',
                'type' => 'Store View',
            );
        } else if (!empty($storegroup)) {
            $default = array(
                'name' => $storegroup,
                'title' => '',
                'type' => 'Store Group',
            );
        } else {
            $default = array(
                'name' => '',
                'title' => '',
                'type' => '',
            );
        }

        if (empty($default['type'])) {
            return $default;
        }

        // Loop through the API-result just to get the title
        $options = MageBridgeWidgetHelper::getWidgetData('store');
        if (!empty($options)) {
            foreach ($options as $index => $group) {
                if ($default['type'] == 'Store Group') {
                    if ($default['name'] == $group['value']) {
                        $default['title'] = $group['label'];
                        return $default;
                    }
                } else {
                    foreach ($group['childs'] as $view) {
                        if ($default['name'] == $view['value']) {
                            $default['title'] = $view['label'];
                            return $default;
                        }
                    }
                }
            }
        }

        return $default;
    }
}
