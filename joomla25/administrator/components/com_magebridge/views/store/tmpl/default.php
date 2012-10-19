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

// Set toolbar items for the page
$text = JText::_( 'Edit Default' );
JToolBarHelper::save();
JToolBarHelper::cancel();
?>

<form method="post" name="adminForm" id="adminForm">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td width="50%" valign="top">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Default Store' ); ?></legend>
        <table class="admintable">
        <tbody>
        <tr>
            <td width="100" align="right" class="key">
                <label for="store">
                    <?php echo JText::_( 'Magento store' ); ?>:
                </label>
            </td>
            <td class="value">
                <?php echo $this->fields['store']; ?>
            </td>
        </tr>
        </tbody>
        </table>
    </fieldset>
</td>
</tr>
</tbody>
</table>

<input type="hidden" name="option" value="com_magebridge" />
<input type="hidden" name="default" value="1" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
