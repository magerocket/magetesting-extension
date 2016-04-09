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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Account extends RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Abstract
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('wizardAccount');

        $this->setTemplate('rocketweb_magetesting/wizard/createtestsite/account.phtml');
    }

    protected function _beforeToHtml()
    {
        $params = array(
                '\''.$this->getUrl('*/mtwizard/saveAccountDetails').'\'',
                '\'account\''
        );
        $buttonBlock = $this->getLayout()
        ->createBlock('adminhtml/widget_button')
        ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Proceed'),
                'onclick' => 'WizardObj.processAccountStep('.implode(',',$params).');',
                'class' => 'process_account_button'
        ) );
        $this->setChild('process_account_button',$buttonBlock);

        return parent::_beforeToHtml();
    }

    protected function getMageTestingRegistrationUrl()
    {
        return RocketWeb_MageTesting_Model_Api_Abstract::API_URL . 'our-plans';
    }

    protected function getMageTestingMyAccountUrl()
    {
        return RocketWeb_MageTesting_Model_Api_Abstract::API_URL . 'my-account';
    }
}