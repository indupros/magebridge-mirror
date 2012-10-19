<?php
/**
 * Joomla! template MageBridge Root
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Magento block-imitation
 */
function modChrome_magento($module, &$params, &$attribs)
{ ?>
        <div class="block module<?php echo $params->get('moduleclass_sfx'); ?>">
            <?php if ($module->showtitle != 0) : ?>
                <div class="block-title">
                    <strong><span><?php echo $module->title; ?></span></strong>
                </div>
            <?php endif; ?>
            <div class="block-content" style="padding:5px">
                <?php echo $module->content; ?>
            </div>
        </div>
    <?php
}
