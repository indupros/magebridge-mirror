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
class MageBridgeViewUrl extends MageBridgeView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        // Initialize the view
        $this->setTitle('Edit URL Replacement');

        // Initialize common variables
        $application = JFactory::getApplication();
        $user = JFactory::getUser();
        $option = JRequest::getCmd('option');

		// Get data from the model
        $model = $this->getModel();
		$item = $this->get('Data');

        // Get the item
        $item = $this->get('data');
        $isNew = ($item->id < 1);

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
        $query = 'SELECT ordering AS value, source AS text'
            . ' FROM #__magebridge_urls'
            . ' ORDER BY ordering';

        // Build the fields
        $fields = array();
        $fields['ordering'] = JHTML::_('list.specificordering',  $item, $item->id, $query );
        $fields['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
        $fields['source_type'] = $this->getFieldSourceType($item->source_type);

        // Clean the object before displaying
        JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'text' );

        $user = JFactory::getUser();
		$this->assignRef('user', $user);
		$this->assignRef('fields', $fields);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}

    /*
     * Get the HTML-field for the source type setting
     *
     * @param null
     * @return string
     */
    public function getFieldSourceType($current = null)
    {
        $options = array(
            array( 'value' => 0, 'text' => JText::_('Original Magento URL')),
            array( 'value' => 1, 'text' => JText::_('Partial match')),
        );
        return JHTML::_('select.genericlist', $options, 'source_type', null, 'value', 'text', $current);
    }
}
