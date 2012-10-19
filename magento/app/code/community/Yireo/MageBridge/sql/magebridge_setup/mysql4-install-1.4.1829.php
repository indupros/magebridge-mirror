<?php
/**
 * MageBridge
 *
 * @author Yireo
 * @package MageBridge
 * @copyright Copyright 2012
 * @license Yireo EULA (www.yireo.com)
 * @link http://www.yireo.com
 */

$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS `{$installer->getTable('magebridge_customer_joomla')}` (
    `customer_id` int(10) unsigned default NULL,
    `joomla_id` int(10) unsigned default NULL,
    `website_id` int(10) unsigned default NULL,
    PRIMARY KEY  (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Relation between Joomla user and Magento customer';
");
$installer->endSetup();
