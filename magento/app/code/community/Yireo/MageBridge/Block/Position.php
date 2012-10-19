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

/*
 * MageBridge class for the position-block
 */
class Yireo_MageBridge_Block_Position extends Mage_Core_Block_Template
{
    /*
     * Constructor method
     *
     * @access public
     * @param null
     * @return null
     */
    public function _construct()
    {
        parent::_construct();
        if(Mage::helper('magebridge')->isBridge()) {
            $this->setTemplate('magebridge/position.phtml');
        }
    }

    /*
     * Helper method to get the XML-layout position
     *
     * @access public
     * @param null
     * @return string
     */
    public function getPosition()
    {
        $name = parent::getPosition();
        if(empty($name)) {
            $name = $this->getNameInLayout();
        }
        return $name;
    }

    /**
     * Render block HTML
     *
     * @access public
     * @param null
     * @return string
     */
    protected function _toHtml()
    {
        if(Mage::helper('magebridge')->isBridge()) {
            return parent::_toHtml();
        }
        
        $result = Mage::getSingleton('magebridge/client')->call('magebridge.position', array($this->getPosition(), $this->getStyle()));
        return $result;
    }
}
