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
class TableUrl extends JTable
{
    /*
     * Primary key
     * @var int 
     */
    var $id = null;

    /*
     * @var string
     */
    var $source = null;

    /*
     * @var int
     */
    var $source_type = null;

    /*
     * @var string
     */
    var $destination = null;

    /*
     * @var string
     */
    var $description = null;

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
        parent::__construct('#__magebridge_urls', 'id', $db);
    }

    /*
     * Override of check-method
     *
     * @param null
     * @return bool
     */
    public function check()
    {
        if (empty($this->source) || empty($this->destination)) {
			$this->setError(JText::_('Source and destination must be filled in.'));
			return false;
        }
        return true;
    }
}

