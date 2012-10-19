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
class MageBridgeViewUsergroups extends MageBridgeView
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
        $this->setTitle('Usergroup relations');

        // Set toolbar items for the page
        //MageBridgeToolBarHelper::help('magebridge.usergroups');
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();

        // Initialize common variables
        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' ).'-usergroups';

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
                $item->edit_link = 'index.php?option=com_magebridge&view=usergroup&task=edit&cid[]='.$item->id;
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
