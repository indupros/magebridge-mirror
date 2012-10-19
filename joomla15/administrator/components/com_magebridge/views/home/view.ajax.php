<?php
/**
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

// Require the parent view
require_once JPATH_COMPONENT.'/view.php';

/**
 * HTML View class 
 *
 * @static
 * @package MageBridge
 */
class MageBridgeViewHome extends MageBridgeView
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
    public function display($tpl = null)
    {
        switch(JRequest::getVar('layout')) {
            case 'feeds':
                $feeds = $this->fetchFeeds('http://www.yireo.com/blog?format=feed&type=rss', 3);
                $this->assignRef( 'feeds', $feeds);
                break;
            case 'promotion':
                $html = $this->fetchPage('http://www.yireo.com/advertizement.php');
                print $html;
                exit;
        }

        parent::display($tpl);
    }

    /*
     * Display method
     *
     * @param string $url
     * @param int $max
     * @return array
     */
    public function fetchFeeds($url = '', $max = 3)
    {
        if(method_exists('JFactory', 'getFeedParser')) {
            $rss = JFactory::getFeedParser($url);
        } else {
            $rss = JFactory::getXMLParser('rss', array('rssUrl' => $url));
        }

        if ($rss == null) {
            return false;
        }
        $result = $rss->get_items();
        $feeds = array();
        $i = 0;
        foreach ($result as $r) {
            if ($i == $max) break;
            $feed = array();
            $feed['link'] = $r->get_link();
            $feed['title'] = $r->get_title();
            $feed['description'] = preg_replace( '/<img([^>]+)>/', '', $r->get_description());
            $feeds[] = $feed;
            $i++;
        }
        return $feeds;
    }

    /**
     * Method to fetch a specific page
     *
     * @access public
     * @param null
     * @return bool
     */
    public function fetchPage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'].' [MageBridge]');
        $contents = curl_exec($ch);
        return $contents;
    }
}
