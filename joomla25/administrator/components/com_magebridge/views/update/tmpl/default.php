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
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
    if (pressbutton == 'update') {
        var answer = confirm("Upgrading MageBridge is easy, but still needs to be done with proper planning. Are you sure you want to continue?\n\nDon't forget to upgrade Magento as well.");
        if (answer) {
            submitform(pressbutton);
        }
    } else {
        submitform(pressbutton);
    }
}
</script>
<form method="post" name="adminForm" id="adminForm">
<div id="editcell">
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminlist">
<thead>
    <tr>
        <th width="20">
            <input type="checkbox" id="toggler" />
        </th>
        <th width="200"> 
            <?php echo JText::_( 'Extension' ); ?>
        </th>
        <th> 
            <?php echo JText::_( 'Description' ); ?>
        </th>
        <th width="110"> 
            <?php echo JText::_( 'Application' ); ?>
        </th>
        <th width="200"> 
            <?php echo JText::_( 'System name' ); ?>
        </th>
        <th width="110"> 
            <?php echo JText::_( 'Type' ); ?>
        </th>
        <th width="110">
            <?php echo JText::_( 'Current version' ); ?>
        </th>
        <th width="110">
            <?php echo JText::_( 'Latest version' ); ?>
        </th>
        <th>
            &nbsp;
        </th>
    </tr>
</thead>
<tbody>
<?php 
$i = 0;
foreach ($this->data as $package) { 

    $k = (empty($k)) ? 0 : 1;

    if ($package['current_version']) {
        $checked = '<input type="checkbox" disabled checked="checked" />';
        $checked .= '<input type="hidden" name="packages[]" value="'.$package['name'].'" />';
    } else if ($package['core'] == 1) {
        $checked = '<input type="checkbox" class="package" name="packages[]" value="'.$package['name'].'" id="package_input_'.$i.'" />';
    } else {
        $checked = '<input type="checkbox" class="package-noncore" name="packages[]" value="'.$package['name'].'" />';
    }

    $token = (method_exists('JSession', 'getFormToken')) ? JSession::getFormToken() : JUtility::getToken();
    $upgrade_url = 'index.php?option=com_magebridge&task=update&packages[]='.$package['name'].'&'.$token.'=1';

    if (isset($package['app'])) {
        if ($package['app'] == 'site') {
            $app = JText::_('Frontend');
        } else {
            $app = JText::_('Backend');
        }
    } else {
        $app = null;
    }
    ?>
    <tr class="row <?php echo 'row'.$k; ?>" id="package_<?php echo $i; ?>">
        <td>
            <?php echo $checked; ?>
        </td>
        <td class="select">
            <?php echo $package['title']; ?>
        </td>
        <td class="select">
            <?php echo $package['description']; ?>
        </td>
        <td class="select">
            <?php echo $app; ?>
        </td>
        <td class="select">
            <?php echo $package['name']; ?>
        </td>
        <td class="select">
            <?php echo ($package['core'] == 1) ? JText::_('Core') : JText::_('Optional'); ?>
        </td>
        <td class="select">
            <?php echo ($package['current_version']) ? $package['current_version'] :  JText::_('Not Installed'); ?>
        </td>
        <td class="select">
            <?php echo ($package['latest_version']) ? '<a href="'.$upgrade_url.'">'.$package['latest_version'].'</a>' :  '&nbsp;'; ?>
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
    <?php 
    $k = 1 - $k; 
    $i++;
    } 
    ?>
</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_magebridge" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
