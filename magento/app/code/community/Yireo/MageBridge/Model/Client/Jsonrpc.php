<?php
/**
 * MageBridge
 *
 * @author Yireo
 * @package MageBridge
 * @copyright Copyright 2012
 * @license Yireo EULA (www.yireo.com)
 * @link http://www.yireo.com/
 */

/*
 * MageBridge model for JSON-RPC client-calls
 */
class Yireo_MageBridge_Model_Client_Jsonrpc extends Yireo_MageBridge_Model_Client
{
    /*
     * Method to call a JSON-RPC method
     *
     * @access public
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function makeCall($url, $method, $params = array())
    {
        // Get the authentication data
        $auth = $this->getAPIAuthArray();
        $method = preg_replace('/^magebridge\./', '', $method);

        // If these values are not set, we are unable to continue
        if(empty($url ) || $auth == false) {
            return false;
        }

        // Add the $auth-array to the parameters
        $params['api_auth'] = $auth;

        // Construct an ID
        $id = md5($method);

        // Construct the POST-data
        $post = array(
            'method' => $method,
            'params' => $params,
            'id' => $id,
        );
        $post = Zend_Json_Encoder::encode($post);

        // Initialize a CURL-client
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // @todo: Check what other CURL-options need to be set

        // Build the CURL connection and receive feedback
        $data = curl_exec($ch);

        if(empty($data) || !preg_match('/^\{/', $data)) {
            Mage::getSingleton('magebridge/debug')->trace('JSON-RPC: Wrong data in JSON-RPC reply', $data);
            Mage::getSingleton('magebridge/debug')->trace('JSON-RPC: CURL-error', curl_error($ch));
            return false;
        }

        // Try to decode the result
        $decoded = json_decode($data, true);
        if(empty($decoded)) {
            Mage::getSingleton('magebridge/debug')->error('JSON-RPC: Empty JSON-response');
            return false;
        }

        $data = $decoded;
        if(!is_array($data)) {
            Mage::getSingleton('magebridge/debug')->trace('JSON-RPC: JSON-response is not an array', $data);
            return false;
        } else {
            if(isset($data['error']) && !empty($data['error']['message'])) {
                Mage::getSingleton('magebridge/debug')->trace('JSON-RPC: JSON-error', $data['error']['message']);
                return false;
            } elseif(!isset($data['result'])) {
                Mage::getSingleton('magebridge/debug')->error('JSON-RPC: No result in JSON-data');
                return false;
            }
        }

        return $data['result'];
    }
} 
