<?php
/*
 * Joomla! Yireo Library
 *
 * @author Yireo (https://www.yireo.com/)
 * @package YireoLib
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link https://www.yireo.com/
 * @version 0.4.3
 */

defined('_JEXEC') or die('Restricted access');
?>
<?php foreach ($this->icons as $icon) { ?>
<div style="float:left">
    <div class="icon">
        <a href="<?php echo $icon['link']; ?>" target="<?php echo $icon['target']; ?>"><?php echo $icon['icon']; ?><span><?php echo $icon['text']; ?></span></a>
    </div>
</div>
<?php } ?>
<div style="clear:both;" />
