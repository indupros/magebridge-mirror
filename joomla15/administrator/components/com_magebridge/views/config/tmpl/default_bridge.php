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

<?php echo MageBridgeHelper::help('Before configuring things in this tab, make sure the API is up and running'); ?>

<fieldset class="adminform">
<legend><?php echo JText::_('Website'); ?></legend>
<table class="admintable">
    <?php if ($this->mode == 'advanced') { ?>
    <tr>
        <td class="key" valign="top">
            <?php echo JText::_('Backend'); ?>
        </td>
        <td class="value">
            <?php echo $this->fields['backend']; ?>
        </td>
        <td class="status">
        </td>
        <td class="description">
            <span><?php echo JText::_( 'BACKEND_DESCRIPTION' ); ?></span>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td class="key vital" valign="top">
            <?php echo JText::_('Website ID'); ?>
        </td>
        <td class="value">
            <?php echo $this->fields['website']; ?>
        </td>
        <td class="status">
        </td>
        <td class="description">
            <span><?php echo JText::_( 'WEBSITE_DESCRIPTION' ); ?></span>
        </td>
    </tr>
</table>
</fieldset>

<fieldset class="adminform">
<legend><?php echo JText::_('SSL settings'); ?></legend>
<table class="admintable">
    <tr>
        <td class="key" valign="top">
            <?php echo JText::_('Enforce_SSL'); ?>
        </td>
        <td class="value">
            <?php echo $this->fields['enforce_ssl']; ?>
        </td>
        <td class="status">
        </td>
        <td class="description">
            <span><?php echo JText::_( 'ENFORCE_SSL_DESCRIPTION' ); ?></span>
        </td>
    </tr>
    <?php if ($this->mode == 'advanced') { ?>
    <tr>
        <td class="key" valign="top">
            <?php echo JText::_('Secure_URLs'); ?>
        </td>
        <td class="value">
            <input type="text" name="payment_urls" value="<?php echo $this->config['payment_urls']['value']; ?>" size="30" />
        </td>
        <td class="status">
        </td>
        <td class="description" valign="top">
            <span><?php echo JText::_( 'SECURE_URLS_DESCRIPTION' ); ?></span>
        </td>
    </tr>
    <?php } ?>
</table>
</fieldset>

<fieldset class="adminform">
<legend><?php echo JText::_('Offline settings'); ?></legend>
<table class="admintable">
    <tr>
        <td class="key" valign="top">
            <?php echo JText::_('Offline'); ?>
        </td>
        <td class="value">
            <?php echo $this->fields['offline']; ?>
        </td>
        <td class="status">
        </td>
        <td class="description" valign="top">
            <span><?php echo JText::_( 'OFFLINE_DESCRIPTION' ); ?></span>
        </td>
    </tr>
    <tr>
        <td class="key" valign="top">
            <?php echo JText::_('Offline_message'); ?>
        </td>
        <td class="value">
            <textarea type="text" name="offline_message" rows="3" cols="40" maxlength="255"><?php echo $this->config['offline_message']['value']; ?></textarea>
        </td>
        <td class="status">
        </td>
        <td class="description" valign="top">
            <span><?php echo JText::_( 'OFFLINE_MESSAGE_DESCRIPTION' ); ?></span>
        </td>
    </tr>
    <tr>
        <td class="key" valign="top">
            <?php echo JText::_('Offline_Exclude_IP'); ?>
        </td>
        <td class="value">
            <input type="text" name="offline_exclude_ip" value="<?php echo $this->config['offline_exclude_ip']['value']; ?>" size="30" />
        </td>
        <td class="status">
        </td>
        <td class="description" valign="top">
            <span><?php echo JText::_( 'OFFLINE_EXCLUDE_IP_DESCRIPTION' ); ?></span>
        </td>
    </tr>
</table>
</fieldset>
