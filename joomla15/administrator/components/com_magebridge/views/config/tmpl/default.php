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

<style type="text/css">
td.key {
    font-weight: normal !important;
    width: 200px !important;
}
td.value {
    width: 300px;
}
td.status {
    width: 30px;
}
input {
    padding: 2px;
}
</style>
<form method="post" name="adminForm" id="adminForm" autocomplete="off">

<?php echo $this->pane->startPane('config'); ?>

<?php echo $this->pane->startPanel('Support', 'config_support'); ?>
<?php echo $this->loadTemplate('license'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->startPanel('API', 'config_api'); ?>
<?php echo $this->loadTemplate('api'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->startPanel('Bridge', 'config_bridge'); ?>
<?php echo $this->loadTemplate('bridge'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->startPanel('Users', 'config_user'); ?>
<?php echo $this->loadTemplate('user'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->startPanel('CSS', 'config_css'); ?>
<?php echo $this->loadTemplate('css'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->startPanel('JavaScript', 'config_js'); ?>
<?php echo $this->loadTemplate('js'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php if ($this->mode == 'advanced') { ?>
<?php echo $this->pane->startPanel('Theming', 'config_theme'); ?>
<?php echo $this->loadTemplate('theme'); ?>
<?php echo $this->pane->endPanel(); ?>
<?php } ?>

<?php echo $this->pane->startPanel('Debugging', 'config_debug'); ?>
<?php echo $this->loadTemplate('debug'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->startPanel('Other settings', 'config_other'); ?>
<?php echo $this->loadTemplate('other'); ?>
<?php echo $this->pane->endPanel(); ?>

<?php echo $this->pane->endPane(); ?>

<input type="hidden" name="option" value="com_magebridge" />
<input type="hidden" name="view" value="config" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
