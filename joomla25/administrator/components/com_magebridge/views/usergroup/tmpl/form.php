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
$edit = JRequest::getVar('edit',true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::save();
JToolBarHelper::apply();
if (!$edit)  {
    JToolBarHelper::cancel();
} else {
    JToolBarHelper::cancel( 'cancel', 'Close' );
}
?>

<form method="post" name="adminForm" id="adminForm">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<td width="50%" valign="top">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Usergroup Relation' ); ?></legend>
        <table class="admintable">
        <tbody>
        <tr>
            <td width="100" align="right" class="key">
                <label for="joomla_group">
                    <?php echo JText::_( 'Joomla! group' ); ?>:
                </label>
            </td>
            <td class="value">
                <?php echo $this->fields['joomla_group']; ?>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="magento_group">
                    <?php echo JText::_( 'Magento group' ); ?>:
                </label>
            </td>
            <td class="value">
                <?php echo $this->fields['magento_group']; ?>
            </td>
        </tr>
        </tbody>
        </table>
    </fieldset>

    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Details' ); ?></legend>
        <table class="admintable">
        <tbody>
        <tr>
            <td width="100" align="right" class="key">
                <label for="description">
                    <?php echo JText::_( 'Description' ); ?>:
                </label>
            </td>
            <td>
                <input type="text" name="description" value="<?php echo $this->item->description; ?>" size="30" />
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <?php echo JText::_( 'Published' ); ?>:
            </td>
            <td class="value">
                <?php echo $this->fields['published']; ?>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <label for="ordering">
                    <?php echo JText::_( 'Ordering' ); ?>:
                </label>
            </td>
            <td class="value">
                <?php echo $this->fields['ordering']; ?>
            </td>
        </tr>
        </tbody>
        </table>
    </fieldset>
</td>
<!--<td width="50%" valign="top">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Parameters (optional)' ); ?></legend>
        <?php if (!empty($this->params)) echo $this->params->render(); ?>
    </fieldset>
</td>-->
</tr>
</tbody>
</table>

<input type="hidden" name="option" value="com_magebridge" />
<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
