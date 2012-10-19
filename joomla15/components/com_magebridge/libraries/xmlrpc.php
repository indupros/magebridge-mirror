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

// No direct access
defined('_JEXEC') or die('Restricted access');

/*
 * MageBridge XML-RPC API-client
 * Note that the main MageBridge API does not depend on this class at all!
 */
class MageBridge_API_Client
{
    /*
     * Client object
     */
    private $client;

    /*
     * Connection timeout
     */
    private $timeout = 15;

    /*
     * API session-ID
     */
    private $session_id = null;

    /*
     * Magento API key
     */
    private $api_key = null;

    /*
     * Magento API username
     */
    private $api_username = null;

    /*
     * Constructor method
     * 
     * @param null
     * @return null
     */
    public function __construct()
    {
        $this->url = '/'.MagebridgeModelConfig::load('basedir').'/api/xmlrpc/';
        $this->host = MagebridgeModelConfig::load('host');
        $this->protocol = MagebridgeModelConfig::load('protocol');

        jimport( 'phpxmlrpc.xmlrpc' );
        $this->client = new xmlrpc_client($this->url, $this->host, $this->protocol);
    }

    /*
     * Method to set XML-RPC debugging
     * 
     * @param bool $bool
     * @return null
     */
    public function setDebug($bool)
    {
        $this->client->setDebug((int)$bool);
    }

    /*
     * Method to make a specific XML-RPC call
     * 
     * @param string $call
     * @param array $options
     * @return mixed
     */
    public function call($call = '', $options = array())
    {
        if(empty($this->session_id)) {
            $api_user = MagebridgeModelConfig::load('api_user');
            $api_key = MagebridgeModelConfig::load('api_key');
            $msg = new xmlrpcmsg( 'login', array( $this->encode($api_user), $this->encode($api_key)));
            $doc = $this->client->send( $msg, $this->timeout );
            if(!is_object($doc)) {
                return JText::_('XML-RPC non-object document: '.$doc);
            }

            $value = $doc->value();
            if(!is_object($value)) {
                return JText::_('XML-RPC non-object value: '.$value);
            }
            $this->session_id = $doc->value()->getval();
            if(!$this->session_id > 0) {
                return JText::_('Empty session ID: ' . $doc->faultString());
            }
        }

        $msg = new xmlrpcmsg( 'call', array( $this->encode($this->session_id), $this->encode($call)));
        $doc = $this->client->send( $msg, $this->timeout );
        if( $doc->faultCode() == 0 ) {
            return $this->decode($doc->value());
        } else {
            return $doc->faultString();
        }
    }

    /*
     * Sample method to get a country listing
     * 
     * @param null
     * @return array
     */
    public function getCountryList()
    {
        return $this->call('country.list');
    }

    /*
     * Method to encode a value going to XML-RPC
     * 
     * @param mixed $mixed
     * @return mixed
     */
    private function encode( $mixed = null )
    {
        // Encode a number
        if(is_integer($mixed) == true) {
            return new xmlrpcval( $mixed, 'string' ); // @todo: Is this "string" correct?
        }

        // Encode a string
        if(is_string($mixed) == true) {
            return new xmlrpcval( $mixed, 'string' );
        }
    }

    /*
     * Method to decode a value coming from XML-RPC
     * 
     * @param mixed $mixed
     * @return mixed
     */
    private function decode( $mixed = null )
    {
        if(get_class($mixed) == 'xmlrpcval') {
            $mixed = $mixed->getval();
        }

        if(is_array($mixed) == true && !empty($mixed)) {
            foreach($mixed as $index => $value) {
                $mixed[$index] = $this->decode($value);
            }
        }
        return $mixed;
    }
}
