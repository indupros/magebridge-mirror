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
<style>
div.description {
    padding: 10px;
    color: #525252;
}
input {
    padding: 2px;
}
</style>

<table id="adminform" width="100%">
<tr>
<td width="60%" valign="top">

<div id="cpanel">
<?php foreach ($this->icons as $icon) { ?>
<div style="float:<?php echo $this->alignment; ?>">
    <div class="icon">
        <a href="<?php echo $icon['link']; ?>" target="<?php echo $icon['target']; ?>"><?php echo $icon['icon']; ?><span><?php echo $icon['text']; ?></span></a>
    </div>
</div>
<?php } ?>
<div style="clear:both;" />
<div class="version">
    <?php echo JText::sprintf('Current version is %s', $this->current_version); ?><br/>
    <?php echo JText::sprintf('See the <a href="%s">MageBridge Changelog</a> for more information', $this->changelog_url); ?>
</div>
<div class="review">
    <?php echo JText::_('Do you like MageBridge?'); ?><br/>
    <?php echo JText::sprintf('Help us with your review or your vote on the official <a href="%s">Joomla! Extensions Directory</a> website.', $this->jed_url); ?>
</div>
</div>

</td>
<td width="40%" valign="top" style="margin-top:0; padding:0">
<h2 class="promotion_header"><?php echo JText::_('Yireo focus'); ?></h2>
<div id="promotion">
    <?php if ($this->backend_feed == 1) { ?>
    <div class="loader" />
    <?php } else { ?>
    <?php echo JText::_('Advertizement is disabled'); ?>
    <?php } ?>
    </div>
</div>
<h2 class="latest_news_header"><?php echo JText::_('Latest blog from Yireo'); ?></h2>
<div id="latest_news">
    <?php if ($this->backend_feed == 1) { ?>
    <div class="loader" />
    <?php } else { ?>
    <?php echo JText::_('RSS-feed is disabled'); ?>
    <?php } ?>
</div>

</td>
</tr>
</table>
