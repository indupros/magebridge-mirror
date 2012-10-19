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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Include the parent controller
require_once JPATH_COMPONENT.'/controller.php';

/**
 * MageBridge Controller
 */
class MageBridgeControllerConfig extends MageBridgeController
{
    /*
     * Extend the default store-method
     *
     * @param null
     * @return null
     */
    public function store($post = null)
    {
        $post = JRequest::get('post');
        $post['api_key'] = JRequest::getVar('api_key', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['api_user'] = JRequest::getVar('api_user', '', 'post', 'string', JREQUEST_ALLOWRAW);
        return parent::store($post);
    }

    /*
     * Method to import configuration from XML
     *
     * @param null
     * @return null
     */
    public function import()
    {
        JRequest::setVar('layout', 'import');
        parent::display();
    }

    /*
     * Method to export configuration to XML
     *
     * @param null
     * @return null
     */
    public function export()
    {
        // Gather the variables
        $config = MagebridgeModelConfig::load();

        $date = date('Ymd');
        $host = str_replace('.', '_', $_SERVER['HTTP_HOST']);
        $filename = 'magebridge-joomla-'.$host.'-'.$date.'.xml';
        $output = $this->getOutput($config);

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . strlen($output));
        header('Content-type: application/xml');
        header('Content-Disposition: attachment; filename='.$filename);
        print $output;

        // Close the application
        $application = JFactory::getApplication();
        $application->close();
    }

    /*
     * Method to handle the upload of a new CSV-file
     *
     * @param null
     * @return array
     */
    public function upload()
    {
        // Construct the needed variables
        $upload = JRequest::getVar('xml', null, 'files');

        // Check whether this is a valid download
        if (empty($upload) || empty($upload['name']) || empty($upload['tmp_name']) || empty($upload['size'])) {
            $this->setRedirect('index.php?option=com_magebridge&view=config&task=import', JText::_('File upload failed on system level'), 'error');
            return false;
        }

        // Check for empty content
        $xmlString = @file_get_contents($upload['tmp_name']);
        if (empty($xmlString)) {
            $this->setRedirect('index.php?option=com_magebridge&view=config&task=import', JText::_('Empty file upload'), 'error');
            return false;
        }

        $xml = @simplexml_load_string($xmlString);
        if (!$xml) {
            $this->setRedirect('index.php?option=com_magebridge&view=config&task=import', JText::_('Invalid XML-configuration'), 'error');
            return false;
        }

        $config = array();
        foreach ($xml->children() as $parameter) {
            $name = (string)$parameter->name;
            $value = (string)$parameter->value;
            if (!empty($name)) {
                $config[$name] = $value;
            }
        }

        if (empty($config)) {
            $this->setRedirect('index.php?option=com_magebridge&view=config&task=import', JText::_('Nothing to import'), 'error');
            return false;
        }

        MagebridgeModelConfig::store($config);
        $this->setRedirect('index.php?option=com_magebridge&view=config', JText::_('Imported configuration succesfully'));
        return true;
    }

    /*
     * Method to get all XML output
     *
     * @param null
     * @return null
     */
    private function getOutput($config)
    {
        $xml = null;
        if (!empty($config)) {
            $xml .= "<configuration>\n";
            foreach ($config as $c) {
                $xml .= "    <parameter>\n";
                $xml .= "        <id>".$c['id']."</id>\n";
                $xml .= "        <name>".$c['name']."</name>\n";
                $xml .= "        <value><![CDATA[".$c['value']."]]></value>\n";
                $xml .= "    </parameter>\n";
            }
            $xml .= "</configuration>\n";
        }
        return $xml;
    }
}
