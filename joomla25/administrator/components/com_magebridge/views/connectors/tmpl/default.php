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
		<?php
			echo $this->lists['state'];
			echo $this->lists['type'];
		?>
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
			<th width="150" class="title">
				<?php echo JHTML::_('grid.sort',  'Title', 'p.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
            <th width="5%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',  'Published', 'p.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </th>
			<th width="80" class="title">
				<?php echo JHTML::_('grid.sort',  'Name', 'p.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="80" class="title">
				<?php echo JHTML::_('grid.sort',  'Connector Type', 'p.type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="80" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Filename', 'p.filename', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 'p.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 'p.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="11">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
        <tr>
            <td colspan="11">
            <img src="<?php echo JURI::root(); ?>/media/com_magebridge/images/active.png" width="16" height="16" border="0" alt="Visible" /> Published &nbsp; | &nbsp;
            <img src="<?php echo JURI::root(); ?>/media/com_magebridge/images/inactive.png" width="16" height="16" border="0" alt="Finished" /> Not Published &nbsp; | &nbsp;
            <img src="<?php echo JURI::root(); ?>/media/com_magebridge/images/disabled.png" width="16" height="16" border="0" alt="Archived" /> Not Available</td>
        </tr>

	</tfoot>
	<tbody>
	<?php
	$k = 0;
    if ( count( $this->items ) > 0 ) {
	    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
        {
            $row = $this->items[$i];

            $checked = ($row->enabled) ? JHTML::_('grid.checkedout',   $row, $i ) : '<input type="checkbox" disabled/>';
            $published = ($row->enabled) ? JHTML::_('grid.published', $row, $i ) : JHTML::image(JURI::root().'/media/com_magebridge/images/disabled.png', JText::_('Disabled'));
            $edit_link = ($row->enabled) ? JHTML::link($row->edit_link, $row->title) : $row->title;
            $ordering = ($this->lists['order'] == 'p.ordering');
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset( $i ); ?>
                </td>
                <td>
                    <?php echo $checked; ?>
                </td>
                <td>
                    <?php echo $edit_link; ?>
                </td>
                <td align="center">
                    <?php echo $published;?>
                </td>
                <td>
                    <?php echo $row->name; ?>
                </td>
                <td>
                    <?php echo $row->type; ?>
                </td>
                <td>
                    <?php echo $row->filename; ?>
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
