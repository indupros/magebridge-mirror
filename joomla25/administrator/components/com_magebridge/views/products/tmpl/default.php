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

<?php echo MageBridgeHelper::help('Here you can connect a Magento product to some logic in Joomla! to sell for instance membership. '
    . 'Use the Magento backend to manage regular products'); ?>

<form method="post" name="adminForm" id="adminForm">
<fieldset id="filter-bar">
<table width="100%">
<tr>
	<td align="left" width="40%">
        <?php echo $this->search; ?>
	</td>
	<td nowrap="nowrap" width="60%">
        <div style="float:right">
		<?php echo $this->lists['state']; ?>
		<?php echo $this->lists['connector']; ?>
        </div>
	</td>
</tr>
</table>
</fieldset>

<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="10">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="35">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th width="200" class="title">
				<?php echo JHTML::_('grid.sort',  'Label', 's.label', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="160" class="title">
				<?php echo JHTML::_('grid.sort',  'Product SKU', 's.sku', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="160" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Connector Name', 's.connector', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="200" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Connector Value', 's.connector_value', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
            <th width="70" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  'Published', 's.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 's.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 's.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="11">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
    <?php
	$k = 1;
    if (count( $this->items ) > 0) {
	    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
        {
            $row = $this->items[$i];

            $checked    = JHTML::_('grid.checkedout',   $row, $i );
            $published 	= JHTML::_('grid.published', $row, $i );
            $ordering = ($this->lists['order'] == 's.ordering');
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset( $i ); ?>
                </td>
                <td>
                    <?php echo $checked; ?>
                </td>
                <td>
                    <a href="<?php echo $row->edit_link; ?>" title="<?php echo JText::_( 'Edit product' ); ?>"><?php echo $row->label; ?></a>
                </td>
                <td>
                    <?php echo $row->sku; ?>
                </td>
                <td>
                    <?php echo $row->connector; ?>
                </td>
                <td>
                    <?php echo $row->connector_value; ?>
                </td>
                <td align="center">
                    <?php echo $published;?>
                </td>
                <td class="order">
                    <span><?php echo $this->pagination->orderUpIcon( $i, 1,'orderup', 'Move Up', $ordering ); ?></span>
                    <span><?php echo $this->pagination->orderDownIcon( $i, $n, 1, 'orderdown', 'Move Down', $ordering ); ?></span>
                    <?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
                    <input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
                </td>
                <td align="center">
                    <?php echo $row->id; ?>
                </td>
            </tr>
            <?php
            $k = 1 - $k;
        }
    } else {
    ?>
        <tr>
            <td colspan="9">
                <?php echo JText::_('No product relations configured yet'); ?>
            </td>
        </tr>
    <?php
    }
	?>
	</tbody>
	</table>
</div>

<input type="hidden" name="option" value="com_magebridge" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
