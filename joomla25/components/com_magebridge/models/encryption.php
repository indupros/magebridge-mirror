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
 * Bridge encryption class
 */
class MageBridgeModelEncryption
{
    /*
     * Return an encryption key
     *
     * @deprecated
     * @param string $string
     * @return string
     */
    public static function getSaltKey($string)
    {
        return MageBridgeEncryptionHelper::getSaltKey($string);
    }

    /*
     * Encrypt data for security
     *
     * @deprecated
     * @param mixed $data
     * @return string
     */
    public static function encrypt($data)
    {
        return MageBridgeEncryptionHelper::encrypt($data);
    }

    /*
     * Decrypt data after encryption
     *
     * @deprecated
     * @param string $data
     * @return mixed
     */
    public static function decrypt($data)
    {
        return MageBridgeEncryptionHelper::decrypt($data);
    }
}
