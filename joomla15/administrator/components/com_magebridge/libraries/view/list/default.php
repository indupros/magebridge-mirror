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
<form method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
    <td align="left" width="40%">
        <?php echo $this->loadTemplate('search'); ?>
    </td>
    <td align="right" width="60%">
        <?php echo $this->loadTemplate('lists'); ?>
    </td>
</tr>
</table>
<div id="editcell">
    <table class="adminlist" width="600">
    <thead>
        <tr>
            <th width="5">
                <?php echo JText::_('LIB_YIREO_VIEW_NUM'); ?>
            </th>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
            <?php echo $this->loadTemplate('thead'); ?>
            <th width="5%" class="title">
                <?php echo JHTML::_('grid.sort', 'LIB_YIREO_TABLE_FIELDNAME_PUBLISHED', $this->fields['state_field'], $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
            <th width="8%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'LIB_YIREO_TABLE_FIELDNAME_ORDERING', $this->fields['ordering_field'], $this->lists['order_Dir'], $this->lists['order'] ); ?>
                <?php echo JHTML::_('grid.order', $this->items ); ?>
            </th>
            <th width="5">
                <?php echo JHTML::_('grid.sort', 'LIB_YIREO_TABLE_FIELDNAME_ID', $this->fields['primary_field'], $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="100">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
    <?php
    $i = 0;
    if (!empty($this->items)) {
        foreach ($this->items as $item)
        {
            $checked = $this->checkedout($item, $i);
            $published = $this->published($item, $i, $this->getModel());
            $ordering = ($this->lists['order'] == $this->fields['ordering_field']);
            $auto_columns = true;
            ?>
            <tr class="<?php echo "row".($i%2); ?>">
                <td>
                    <?php echo $i+1; ?>
                </td>
                <td>
                    <?php echo $checked; ?>
                </td>
                <?php echo $this->loadTemplate('tbody', array('item' => $item, 'auto_columns' => $auto_columns, 'published' => $published)); ?>
                <?php if($auto_columns): ?>
                <td>
                    <?php echo $published; ?>
                </td>
                <td class="order">
                    <span><?php echo $this->pagination->orderUpIcon( $i, true,'orderup', 'Move Up', $ordering ); ?></span>
                    <span><?php echo $this->pagination->orderDownIcon( $i, 0, true, 'orderdown', 'Move Down', $ordering ); ?></span>
                    <?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
                    <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
                </td>
                <td>
                    <?php echo $item->id; ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php
            $i++;
        }
    } else {
        ?>
        <tr>
            <td colspan="100">
                <?php echo JText::_('LIB_YIREO_VIEW_LIST_NO_ITEMS') ; ?>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
</div>

<?php echo $this->loadTemplate('formend'); ?>
</form>

<?php // @todo: Copyright + logo of Yireo ?>
