<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo (https://www.yireo.com/)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 * @version 0.4.3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the parent view
require_once JPATH_COMPONENT_ADMINISTRATOR.'/lib/view.php';

// Import the needed libraries
jimport('joomla.filter.output');

/**
 * Form View class
 *
 * @package Yireo
 */
class YireoViewForm extends YireoView
{
    /*
     * Flag to determine whether this view is a single-view
     */
    protected $_single = true;

    /*
     * Main constructor method
     *
     * @access public
     * @subpackage Yireo
     * @param null
     * @return null
     */
    public function __construct()
    {
        // Template-paths
        $this->templatePaths[] = dirname(__FILE__).'/form';
    
        // Call the parent constructor
        return parent::__construct();
    }

    /*
     * Main display method
     *
     * @access public
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        // Hide the menu
        JRequest::setVar('hidemainmenu', 1);
    
        // Initialize tooltips
        JHTML::_('behavior.tooltip');

        // Automatically fetch the item and assign it to the layout
        $this->fetchItem();

        // Add extra hidden fields
        $hidden_fields = '<input type="hidden" name="option" value="'.$this->_option.'" />';
        $hidden_fields .= '<input type="hidden" name="view" value="'.$this->_view.'" />';
        $hidden_fields .= '<input type="hidden" name="task" value="" />';
        $hidden_fields .= JHTML::_( 'form.token' );

        $model = $this->getModel();
        if (!empty($model)) {
            $primary_key = $model->getPrimaryKey();
            $hidden_fields .= '<input type="hidden" name="cid[]" value="'.$this->item->$primary_key.'" />';
        }

        $this->assignRef('hidden_fields', $hidden_fields);

        parent::display($tpl);
    }
}
