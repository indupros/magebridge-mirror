<?php
/**
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2011
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// @todo: Add jQuery minified
//$document = &JFactory::getDocument();
//$document->addScript(JPATH_SITE.'/media/system/js/jquery.js' );
//$document->addScriptDeclaration ( 'jQuery.noConflict();' );

$scripts = array(
    'jquery.js',
    'init.js',
    'form.js',
);

header( 'Content-Type: text/javascript' );
$content = '';
foreach($scripts as $script) {
    if(is_file($script)) {
        $content .= "/* MageBridge: Insert script $script */\n\n";
        $content .= file_get_contents($script)."\n\n";
    } else {
        $content .= "/* MageBridge: Script $script could not be located */\n\n";
    }
}
echo $content;

// @todo: Cache the result
