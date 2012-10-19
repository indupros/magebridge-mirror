<?php
/*
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2012
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * MageBridge Structure
 */
class HelperAbstract
{
    /**
     * Structural data of this component
     */
    static public function getStructure()
    {
        return array(
            'title' => 'MageBridge',
            'menu' => array(
                'home' => 'HOME',
                'config' => 'CONFIG',
                'stores' => 'STORE_RELATIONS',
                'products' => 'PRODUCT_RELATIONS',
                'usergroups' => 'USERGROUP_RELATIONS',
                'connectors' => 'CONNECTORS',
                'urls' => 'URL_REPLACEMENTS',
                'users' => 'USERS',
                'check' => 'SYSTEM_CHECK',
                'logs' => 'LOGS',
                'update' => 'UPDATE',
            ),
            'views' => array(
                'log' => 'Log',
                'logs' => 'Logs',
            ),
        );
    }
}

