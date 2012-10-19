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
class TableStore extends JTable
{
    /*
     * Primary key
     * @var int 
     */
    var $id = null;

    /*
     * @var string
     */
    var $label = null;

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
    var $connector_value = null;

    /*
     * @var string
     */
    var $connector = null;

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
        parent::__construct('#__magebridge_stores', 'id', $db);

    }
}

