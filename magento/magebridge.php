<?php
/**
 * Magento Bridge
 *
 * @author Yireo
 * @package Magento Bridge
 * @copyright Copyright 2012
 * @license Yireo EULA (www.yireo.com)
 * @link http://www.yireo.com
 */

// Basic PHP settings that can be overwritten
ini_set('zlib.output_compression', 0);
//ini_set('display_errors', 1);

// Use this for profiling
define('yireo_starttime', microtime(true));
if(function_exists('yireo_benchmark') == false) {
    function yireo_benchmark($title) {
        $yireo_totaltime = round(microtime(true) - yireo_starttime, 4);
        Mage::getSingleton('magebridge/debug')->profiler($title.': '.$yireo_totaltime.' seconds');
    }
}

// Initialize the bridge
require_once 'magebridge.class.php';
$magebridge = new MageBridge();

// Mask this request
$magebridge->premask();

// Support for Magento Compiler
$compilerConfig = 'includes/config.php';
if (file_exists($compilerConfig)) include $compilerConfig;

// Initialize the Magento application
require_once 'app/Mage.php';
try {

    // Determine the Mage::app() arguments from the bridge
    $app_value = $magebridge->getMeta('app_value');
    $app_type = $magebridge->getMeta('app_type');

    // Doublecheck certain values
    if($app_type == 'website' && $app_value != 'admin') $app_value = (int)$app_value;
    if($app_value == 'admin') $app_type = null;

    // Initialize app_time for benchmarking
    $app_time = time();


    #Varien_Profiler::enable();
    #Mage::setIsDeveloperMode(true);

    // Make sure the headers-sent warning does not throw an exception
    Mage::$headersSentThrowsException = false;

    // Start the Magento application
    if(!empty($app_value) && !empty($app_type)) {
        Mage::app($app_value, $app_type);
    } elseif(!empty($app_value)) {
        Mage::app($app_value);
    } else {
        Mage::app();
    }

    // Debugging
    $debug = Mage::getSingleton('magebridge/debug');
    if(!empty($debug)) {
        $debug->notice("Mage::app($app_value,$app_type)", $app_time);
    }

    // Benchmarking
    yireo_benchmark('Mage::app()');

} catch(Exception $e) {

    // Debugging
    $debug = Mage::getSingleton('magebridge/debug');
    if(!empty($debug)) {
        $debug->notice("Mage::app($app_value,$app_type) failed to start", $app_time);
        $debug->notice("Fallback to Mage::app()", $app_time);
    }

    // Start the Magento application with default values
    Mage::app();
}

// Run the bridge
$magebridge->run();

// End
