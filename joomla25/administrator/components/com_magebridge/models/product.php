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

// Import Joomla! libraries
jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

/*
 * MageBridge Product model
 */
class MagebridgeModelProduct extends JModel
{
    /**
     * Item id
     *
     * @var int
     */
    var $_id = null;

    /**
     * Item data
     *
     * @var array
     */
    var $_data = null;

    /**
     * Constructor method
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return null
     */
    public function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid', array(0), '', 'array');
        $edit = JRequest::getVar('edit',true);
        if ($edit) {
            $this->setId((int)$array[0]);
        }
    }

    /**
     * Method to set the item identifier
     *
     * @package MageBridge
     * @access public
     * @param int $id
     * @return null
     */
    public function setId($id)
    {
        // Set item id and wipe data
        $this->_id = $id;
        $this->_data = null;
    }

    /**
     * Method to get the item identifier
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Method to get an item
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return array
     */
    public function &getData()
    {
        // Load the item data
        if (!$this->_loadData()) {
            $this->_initData();
        }
        return $this->_data;
    }

    /**
     * Tests if item is checked out
     *
     * @package MageBridge
     * @access public
     * @param int $uid
     * @return bool
     */
    public function isCheckedOut($uid = 0)
    {
        if ($this->_loadData()) {
            if ($uid) {
                return ($this->_data->checked_out && $this->_data->checked_out != $uid);
            } else {
                return $this->_data->checked_out;
            }
        }
    }

    /**
     * Method to checkin/unlock the item
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return bool
     */
    public function checkin()
    {
        if ($this->_id) {
            $item = $this->getTable();
            if (! $item->checkin($this->_id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return false;
    }

    /**
     * Method to checkout/lock the item
     *
     * @package MageBridge
     * @access public
     * @param int $uid
     * @return bool
     */
    public function checkout($uid = null)
    {
        if ($this->_id) {
            // Make sure we have a user id to checkout the article with
            if (is_null($uid)) {
                $user = JFactory::getUser();
                $uid = $user->get('id');
            }

            // Checkout the table
            $item = $this->getTable();
            if (!$item->checkout($uid, $this->_id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }
        return false;
    }

    /**
     * Method to store the item
     *
     * @package MageBridge
     * @access public
     * @param array $data
     * @return bool
     */
    public function store($data)
    {
        $row = $this->getTable();

        if (empty($data['sku'])) {
            $this->setError(JText::_('No product was selected'));
            return false;
        }

        if (empty($data['connector'])) {
            $this->setError(JText::_('No connector was selected'));
            return false;
        }

        $connector = MageBridgeConnectorProduct::getConnector($data['connector']);
        if ($connector == false) {
            $this->setError(JText::_('Failed to load connector'));
            return false;
        }

        $data['connector_value'] = $connector->getFormPost($data);

        if (empty($data['label'])) {
            $data['label'] = $data['sku'];
        }


        // Bind the form fields to the item table
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the item table is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Product the item table to the database
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Save the ID for later usage
        $this->_id = $row->id;

        return true;
    }

    /**
     * Method to remove an item
     *
     * @package MageBridge
     * @access public
     * @param $cid array
     * @return bool
     */
    public function delete($cid = array())
    {
        $result = false;

        if (count( $cid )) {
            JArrayHelper::toInteger($cid);
            $cids = implode( ',', $cid );
            $query = 'DELETE FROM #__magebridge_products WHERE id IN ( '.$cids.' )';
            $this->_db->setQuery( $query );
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    /**
     * Method to (un)publish an item
     *
     * @package MageBridge
     * @access public
     * @param array $cid
     * @param int $publish
     * @return bool
     */
    public function publish($cid = array(), $publish = 1)
    {
        $user = JFactory::getUser();

        if (count( $cid )) {
            JArrayHelper::toInteger($cid);
            $cids = implode( ',', $cid );

            $query = 'UPDATE #__magebridge_products'
                . ' SET published = '.(int) $publish
                . ' WHERE id IN ( '.$cids.' )'
                . ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
            ;
            $this->_db->setQuery( $query );
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    /**
     * Method to move an item
     *
     * @package MageBridge
     * @access public
     * @param string $direction
     * @return bool
     */
    public function move($direction)
    {
        $row = $this->getTable();
        if (!$row->load($this->_id)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move( $direction, ' published >= 0 ' )) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    /**
     * Method to move an item
     *
     * @package MageBridge
     * @access public
     * @param array $cid
     * @param string $order
     * @return bool
     */
    public function saveorder($cid = array(), $order)
    {
        $row = $this->getTable();
        $groupings = array();

        // Update ordering values
        for( $i=0; $i < count($cid); $i++ ) {
            $row->load( (int) $cid[$i] );

            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Method to load content item data
     *
     * @package MageBridge
     * @access private
     * @param null
     * @return bool
     */
    private function _loadData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data)) {
            $query = 'SELECT s.*' .
                ' FROM #__magebridge_products AS s' .
                ' WHERE s.id = '.(int) $this->_id ;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean) $this->_data;
        }
        return true;
    }

    /**
     * Method to initialise the item data
     *
     * @package MageBridge
     * @access private
     * @param null
     * @return bool
     */
    private function _initData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data)) {
            $item = new stdClass();
            $item->id = 0;
            $item->label = null;
            $item->sku = null;
            $item->connector = null;
            $item->connector_value = null;
            $item->access = 0;
            $item->ordering = 0;
            $item->published = 0;
            $item->checked_out = 0;
            $item->checked_out_time = 0;
            $item->params = null;
            $this->_data = $item;
            return (boolean) $this->_data;
        }
        return true;
    }
}
