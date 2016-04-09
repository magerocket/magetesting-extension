<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_MageTesting
 * @copyright  Copyright (c) 2013 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_MageTesting_Helper_Wizard extends Mage_Core_Helper_Abstract
{
    protected $steps = array(
        'account',
        'storebackup',
        'dbbackup',
        'connection',
        'import'
    );

    public function getSteps()
    {
        return $this->steps;
    }

    public function getFirstStep()
    {
        return reset($this->steps);
    }

    public function createBlock($block)
    {
        return Mage::getSingleton('core/layout')->createBlock(
            'magetesting/adminhtml_wizard_' . $block
        );
    }

    public function addWizardJs()
    {
        Mage::getSingleton('core/layout')->getBlock('head')->addJs(
            'rocketweb/magetesting/Wizard.js'
        );
    }
    
}