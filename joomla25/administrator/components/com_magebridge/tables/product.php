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

// Include the parent-class
require_once JPATH_COMPONENT_ADMINISTRATOR.'/libraries/table.php';

/**
* MageBridge Table class
*
* @package MageBridge
*/
class TableProduct extends YireoTable
{
    /**
     * Constructor
     *
     * @param JDatabase $db
     * @return null
     */
    public function __construct(& $db) 
    {
        parent::__construct('#__magebridge_products', 'id', $db);
    }
}
