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

defined('_JEXEC') or die('Restricted access');
?>
<table id="adminform" width="100%">
    <?php if (!empty($this->feeds)) { ?>
    <?php foreach ($this->feeds as $feed) { ?>
    <tr>
    <td>
        <a target="_new" href="<?php echo $feed['link']; ?>"><h3><?php echo $feed['title']; ?></h3></a>
        <?php echo $feed['description']; ?>
    </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
    <td>
        <?php echo JText::_( 'Failed to load feed' ); ?>
    </td>
    </tr>
    <?php } ?>
</table>
