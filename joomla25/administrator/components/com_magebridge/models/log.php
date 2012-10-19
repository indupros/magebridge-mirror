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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport('joomla.application.component.model');
jimport('joomla.utilities.date');

/*
 * MageBridge Logs model
 */
class MagebridgeModelLog extends JModel
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
     * @param int $id Identifier for this item
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
     * @return int Identifier for this item
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
     * @return array Data for this item
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
     * Method to insert a new log
     *
     * @package MageBridge
     * @access public
     * @param string $message
     * @param int $level 
     * @return bool
     */
    public function add($message, $level = 0)
    {
        $data = array(
            'message' => $message,
            'level' => $level,
        );

        return $this->store($data);
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

        // Prepare the data
        $now = new JDate('now');

        // Build the data
        $data['remote_addr'] = $_SERVER['REMOTE_ADDR'];
        $data['http_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['timestamp'] = $now->toMySQL();

        // Bind the form fields to the table
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the table is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Store the table to the database
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Set the ID for further usage
        $this->_id = $row->id;
        return true;
    }

    /**
     * Method to remove an item
     *
     * @package MageBridge
     * @access public
     * @param array $cid
     * @return bool
     */
    public function delete($cid = array())
    {
        $result = false;

        if (count( $cid )) {
            JArrayHelper::toInteger($cid);
            $cids = implode( ',', $cid );
            $query = 'DELETE FROM #__magebridge_log WHERE id IN ( '.$cids.' )';
            $this->_db->setQuery( $query );
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
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
     * @return mixed
     */
    private function _loadData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data)) {
            $query = 'SELECT log.*' .
                ' FROM #__magebridge_log AS log' .
                ' WHERE log.id = '.(int) $this->_id ;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return $this->_data;
        }
        return true;
    }

    /**
     * Method to initialise the data
     *
     * @package MageBridge
     * @access private
     * @param null
     * @return mixed
     */
    private function _initData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data)) {
            $log = new stdClass();
            $log->id = 0;
            $log->message = null;
            $log->level = 0;
            $log->remote_addr = null;
            $log->http_agent = null;
            $log->timestamp = null;
            $this->_data = $log;
            return $this->_data;
        }
        return true;
    }
}
