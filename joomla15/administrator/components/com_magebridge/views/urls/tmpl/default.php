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
<form method="post" name="adminForm" id="adminForm">
<table>
<tr>
	<td nowrap="nowrap">
	</td>
</tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th width="250" class="title">
				<?php echo JHTML::_('grid.sort',  'Source', 'u.source', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'Destination', 'u.destination', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
            <th width="70" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  'Published', 's.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 's.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items ); ?>
			</th>
			<th width="40" class="title">
				<?php echo JText::_('ID'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="7">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
    if ( count( $this->items ) > 0 ) {
	    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
        {
            $row = $this->items[$i];
            $checked = JHTML::_('grid.checkedout', $row, $i);
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
                    <a href="<?php echo JRoute::_('index.php?option=com_magebridge&view=url&task=edit&cid[]='.$row->id); ?>"><?php echo $row->source; ?></a>
                </td>
                <td>
                    <?php echo $row->destination; ?>
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
        <td colspan="11">
            <?php echo JText::_( 'No items' ); ?>
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
