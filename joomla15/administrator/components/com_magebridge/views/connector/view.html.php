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

// Import the needed libraries
jimport('joomla.filter.output');

/**
 * HTML View class
 */
class MageBridgeViewConnector extends MageBridgeView
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
        $this->setTitle('Edit connector');

        // Initialize common variables
        $application = JFactory::getApplication();
        $user = JFactory::getUser();
        $option = JRequest::getCmd( 'option' );

		// Get data from the model
        $model = $this->getModel();
		$item = $this->get( 'Data');

        // Get the item
        $item = $this->get('data');
        $isNew      = ($item->id < 1);

        // Fail if checked out not by 'me'
        if ($model->isCheckedOut( $user->get('id') )) {
            $msg = JText::sprintf( 'Item locked', $item->name);
            $application->redirect( 'index.php?option='. $option, $msg );
        }

        // Edit or Create?
        if (!$isNew) {
            $model->checkout( $user->get('id') );
        } else {
            // initialise new record
            $item->published = 1;
            $item->order = 0;
        }

        // Build the HTML-select list for ordering
        $query = 'SELECT ordering AS value, name AS text'
            . ' FROM #__magebridge_connectors'
            . ' ORDER BY ordering';

        // Build the fields
        $fields = array();
        $fields['ordering'] = JHTML::_('list.specificordering',  $item, $item->id, $query );
        $fields['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );

        // Read the connector-parameters 
        $params = null;
        if (!empty($item->name) && !empty($item->type)) {
            $file = JPATH_SITE.'/components/com_magebridge/connectors/'.$item->type.'/'.$item->name.'.xml';
            if (is_file($file)) {
                $params = new JParameter( $item->params, $file );
            }
        }

        // Clean the object before displaying
        JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'text' );

        // Get the pane
        jimport('joomla.html.pane');
        $pane = JPane::getInstance('sliders');

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('fields', $fields);
		$this->assignRef('params', $params);
		$this->assignRef('pane', $pane);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}
