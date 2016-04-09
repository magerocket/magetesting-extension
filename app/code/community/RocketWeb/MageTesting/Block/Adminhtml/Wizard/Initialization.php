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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Initialization extends Mage_Adminhtml_Block_Template
{
    protected function _beforeToHtml()
    {
        $wizard = Mage::getModel('magetesting/wizard');

        $this->addData(array(
            'step' => $wizard->getWizardStep(),
            'steps' => json_encode($this->helper('magetesting/wizard')->getSteps()),
            'status' => $wizard->getWizardStatus(),
        ));

        $this->setId('wizardInitialization');

        $this->setTemplate('rocketweb_magetesting/wizard/initialization.phtml');

        return parent::_beforeToHtml();
    }

    protected function getBackupUrl()
    {
        return $this->getUrl(
            '*/mtbackup/create'
        );
    } 
}