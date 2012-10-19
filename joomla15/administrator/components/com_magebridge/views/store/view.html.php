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
class MageBridgeViewStore extends MageBridgeView
{
    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        switch(JRequest::getCmd('task')) {
            case 'default':
                $this->showDefaultForm($tpl);
                break;

            default:
                $this->showForm($tpl);
                break;
        }
    }

    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function showDefaultForm($tpl = null)
	{
        // Initialize the view
        $this->setTitle('Edit default store');

        // Load values from the configuration
        $storegroup = MageBridgeModelConfig::load('storegroup');
        $storeview = MageBridgeModelConfig::load('storeview');

        // Construct the arguments for the HTML-element
        if (!empty($storeview)) {
            $type = 'storeview';
            $name = $storeview;
        } else if (!empty($storegroup)) {
            $type = 'storegroup';
            $name = $storegroup;
        } else {
            $type = null;
            $name = null;
        }

        // Fetch the HTML-element
        $fields = array();
        $fields['store'] = $this->getFieldStore($type, $name);

		$this->assignRef('fields', $fields);

		parent::display($tpl);
    }

    /*
     * Method to prepare the content for display
     *
     * @param string $tpl
     * @return null
     */
	public function showForm($tpl = null)
	{
        // Initialize the view
        $this->setTitle('Edit store condition');

        // Initialize common variables
        $application = JFactory::getApplication();
        $user = JFactory::getUser();
        $option = JRequest::getCmd( 'option' );

		// Get data from the model
        $model = $this->getModel();
		$item = $this->get( 'Data');

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
        $query = 'SELECT ordering AS value, name AS text'
            . ' FROM #__magebridge_stores'
            . ' ORDER BY ordering';

        // Build the fields
        $fields = array();
        $fields['store'] = $this->getFieldStore($item->type, $item->name);
        $fields['ordering'] = JHTML::_('list.specificordering',  $item, $item->id, $query );
        $fields['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );

        // Clean the object before displaying
        JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'text' );

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('connectors', MageBridgeConnectorStore::getInstance()->getConnectors());
		$this->assignRef('fields', $fields);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}

    /*
     * Helper method to get the HTML-formelement for a store
     *
     * @param string $type
     * @param string $name
     * @param string $title
     * @return string
     */
    protected function getFieldStore($type = null, $value = null)
    {
        if (!empty($type) && !empty($value)) {
            $value = ($type == 'storegroup') ? 'g:'.$value : 'v:'.$value;
        } else {
            $value = null;
        }
    
        if (empty($name)) {
            $name = 'store';
        }

        return MageBridgeFormHelper::getField('store', $name, $value, null);
    }

    /*
     * Helper method to get the HTML-formelement for a storeview
     *
     * @param string $default
     * @return string
     */
    protected function getFieldStoreview($default = null)
    {
        return MageBridgeFormHelper::getField('storeview', 'name', $value, null);
    }

    /*
     * Helper method to get the HTML-formelement for a storegroup
     *
     * @param string $default
     * @return string
     */
    protected function getFieldStoregroup($default = null)
    {
        return MageBridgeFormHelper::getField('storegroup', 'name', $value, null);
    }
}
