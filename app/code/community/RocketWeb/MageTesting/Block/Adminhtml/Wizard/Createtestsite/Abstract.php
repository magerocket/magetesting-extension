<?php

/**
 * Rocket Web Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is available through the world-wide-web at this URL:
 * http://www.rocketweb.com/RW-LICENSE.txt
 *
 * @category   RocketWeb
 * @package    RocketWeb_MageTesting
 * @copyright  Copyright (c) 2013 RocketWeb (http://www.rocketweb.com)
 * @author     Rocket Web Inc.
 * @license    http://www.rocketweb.com/RW-LICENSE.txt
 */

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Abstract extends Mage_Adminhtml_Block_Template
{
    protected $_wizard = null;

    protected function getWizard()
    {
        if (is_null($this->_wizard)) {
            $this->_wizard = Mage::getModel('magetesting/wizard'); 
        }

        return $this->_wizard;
    }   
}