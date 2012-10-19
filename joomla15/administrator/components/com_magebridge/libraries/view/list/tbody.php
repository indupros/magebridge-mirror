<?php
/**
 * Joomla! Yireo Library
 *
 * @author Yireo
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com/
 * @version 0.4.3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<td>
    <?php if (JTable::isCheckedOut($this->user->get ('id'), $item->checked_out )) { ?>
        <span class="checked_out"><?php echo $item->title; ?></span>
    <?php } else { ?>
        <a href="<?php echo $item->edit_link; ?>" title="<?php echo JText::_( 'Edit Item' ); ?>"><?php echo $item->title; ?></a>
    <?php } ?>
</td>
