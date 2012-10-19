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
<fieldset id="filter-bar">
<table width="100%">
<tr>
	<td align="left" width="40%">
        <?php echo $this->loadTemplate('search'); ?>
	</td>
	<td nowrap="nowrap" width="60%">
        <div style="float:right">
        <?php echo $this->loadTemplate('lists'); ?>
        </div>
	</td>
</tr>
</table>
</fieldset>

<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'Message', 'log.message', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="80" class="title">
				<?php echo JHTML::_('grid.sort',  'Type', 'log.type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="80" class="title">
				<?php echo JHTML::_('grid.sort',  'Origin', 'log.origin', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="80" class="title">
				<?php echo JHTML::_('grid.sort',  'IP', 'log.ip', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Debug Session', 'log.session', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="140" class="title">
				<?php echo JHTML::_('grid.sort',  'Time', 'log.timestamp', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 'log.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
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
	$k = 0;
    if ( count( $this->items ) > 0 ) {
	    for ($i=0, $n=count( $this->items ); $i < $n; $i++)
        {
            $row = $this->items[$i];
            $ordering = ($this->lists['order'] == 'log.timestamp');
            $message = $row->message;
            if (strlen($message) > 100) {
                $message = substr($message, 0, 97).'...';
            }
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset( $i ); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row->message); ?>
                </td>
                <td>
                    <?php echo $this->printType($row->type); ?>
                </td>
                <td>
                    <?php echo JText::_($row->origin); ?>
                </td>
                <td>
                    <?php echo $row->remote_addr; ?>
                </td>
                <td>
                    <?php echo $row->session; ?>
                </td>
                <td>
                    <?php echo $row->timestamp; ?>
                </td>
                <td>
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
            <?php echo JText::_( 'No logs found' ); ?>
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
