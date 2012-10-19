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
class MageBridgeViewProduct extends MageBridgeView
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
        $this->setTitle('Edit product relation');

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

        // Initialize parameters
        $file = JPATH_ADMINISTRATOR.'/components/com_magebridge/models/product.xml';
        $params = new JParameter($item->params, $file);

        // Build the HTML-select list for ordering
        $query = 'SELECT ordering AS value, label AS text'
            . ' FROM #__magebridge_products'
            . ' ORDER BY ordering';

        // Build the fields
        $fields = array();
        $fields['product'] = $this->getFieldProduct($item->sku);
        $fields['ordering'] = JHTML::_('list.specificordering',  $item, $item->id, $query );
        $fields['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );

        // Clean the object before displaying
        JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'text' );

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('connectors', MageBridgeConnectorProduct::getInstance()->getConnectors());
		$this->assignRef('fields', $fields);
		$this->assignRef('params', $params);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}

    /*
     * Helper-method to get the HTML-field for the product
     *
     * @param string $default
     * @return string
     */
    public function getFieldProduct($default = null)
    {
        require_once JPATH_COMPONENT.'/elements/product.php';
        $node = array('return' => 'sku');
        return JElementProduct::fetchElement('sku', $default, $node);
    }
}
