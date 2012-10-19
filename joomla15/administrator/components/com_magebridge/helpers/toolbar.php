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


/**
 * MageBridge ToolBar Helper
 */
class MageBridgeToolBarHelper
{
    public static function help($key) 
    {
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton( 'YireoHelp', $key);
    }
}

// Include the parent class
jimport('joomla.html.toolbar.button');

/**
 * JButton-class to fetch help from Yireo.com
 */
class JButtonYireoHelp extends JButton 
{
	/**
	 * Button type
	 *
	 * @access protected
	 * @var string
	 */
	public $_name = 'Help';

	public function fetchButton($type = 'Help', $key = '')
	{
		$text	= JText::_('Help');
        
        $url = 'http://www.yireo.com/index2.php?option=com_content&amp;task=findkey&amp;tmpl=component&amp;keyref='.$key;

		$html	= "<a href=\"#\" onclick=\"popupWindow('$url', '".JText::_('Help', true)."', 640, 480, 1);\" class=\"toolbar\">\n";
		$html .= "<span class=\"icon-32-help\" title=\"$text\">\n";
		$html .= "</span>\n";
 		$html	.= "$text\n";
		$html	.= "</a>\n";

		return $html;
	}

    public function fetchId()
    {
        return 'yireo';
    }
}
