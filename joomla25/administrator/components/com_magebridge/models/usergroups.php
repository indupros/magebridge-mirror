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

/*
 * MageBridge Usergroups model
 */
class MagebridgeModelUsergroups extends JModel
{
    /**
     * Data array
     *
     * @var array
     */
    var $_data = null;

    /**
     * Data total
     *
     * @var integer
     */
    var $_total = null;

    /**
     * Pagination object
     *
     * @var object
     */
    var $_pagination = null;

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

        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' ).'-usergroups';

        // Get the pagination request variables
        $limit = $application->getUserStateFromRequest( 'global.list.limit', 'limit', $application->getCfg('list_limit'), 'int' );
        $limitstart  = $application->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    /**
     * Method to get items data
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return array
     */
    public function getData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
            //echo $this->_db->getQuery();
        }

        return $this->_data;
    }

    /**
     * Method to get the total number of items
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return integer
     */
    public function getTotal()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    /**
     * Method to get a pagination object for the items
     *
     * @package MageBridge
     * @access public
     * @param null
     * @return JPagination
     */
    public function getPagination()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_pagination;
    }

    /**
     * Method to get items data
     *
     * @package MageBridge
     * @access private
     * @param null
     * @return string
     */
    private function _buildQuery()
    {
        // Get the WHERE and ORDER BY clauses for the query
        $where = $this->_buildContentWhere();
        $orderby = $this->_buildContentOrderBy();

        $query = ' SELECT s.* FROM #__magebridge_usergroups AS s '
            . $where
            . $orderby
        ;

        return $query;
    }

    /**
     * Method to get items data
     *
     * @package MageBridge
     * @access private
     * @param null
     * @return string
     */
    private function _buildContentOrderBy()
    {
        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' ).'-usergroups';

        $filter_order = $application->getUserStateFromRequest( $option.'filter_order', 'filter_order', 's.ordering', 'cmd' );
        $filter_order_Dir = $application->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );

        if ($filter_order && $filter_order_Dir) {
            $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
        } else {
            $orderby = '';
        }

        return $orderby;
    }

    /**
     * Method to get items data
     *
     * @package MageBridge
     * @access private
     * @param null
     * @return string
     */
    private function _buildContentWhere()
    {
        $application = JFactory::getApplication();
        $option = JRequest::getCmd( 'option' ).'-usergroups';

        $filter_state = $application->getUserStateFromRequest( $option.'filter_state', 'filter_state', '', 'word' );
        $filter_order = $application->getUserStateFromRequest( $option.'filter_order', 'filter_order', 's.ordering', 'cmd' );
        $filter_order_Dir = $application->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );

        $where = array();

        if ( $filter_state ) {
            if ( $filter_state == 'P' ) {
                $where[] = 's.published = 1';
            } else if ($filter_state == 'U' ) {
                $where[] = 's.published = 0';
            }
        }

        $where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

        return $where;
    }
}
