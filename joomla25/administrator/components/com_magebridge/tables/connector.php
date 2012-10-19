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

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* MageBridge Table class
*
* @package MageBridge
*/
class TableConnector extends JTable
{
    /*
     * Primary key
     * @var int 
     */
    var $id = null;

    /*
     * @var string
     */
    var $title = null;

    /*
     * @var string
     */
    var $name = null;

    /*
     * @var string
     */
    var $type = null;

    /*
     * @var string
     */
    var $filename = null;

    /*
     * @var int
     */
    var $access = null;

    /*
     * @var int
     */
    var $ordering = null;

    /*
     * @var int
     */
    var $published = null;

    /*
     * @var int
     */
    var $iscore = null;

    /*
     * @var text
     */
    var $params = null;

    /**
     * Constructor
     *
     * @param JDatabase $db
     * @return null
     */
    public function __construct(& $db) {

        // Call the constructor
        parent::__construct('#__magebridge_connectors', 'id', $db);

    }

    /**
     * Bind method
     *
     * @param array $array
     * @param string $ignore
     * @return mixed
     */
    public function bind($array, $ignore = '')
    {
        if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = $registry->toString();
        }

        return parent::bind($array, $ignore);
    }
}

