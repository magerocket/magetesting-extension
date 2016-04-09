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

class RocketWeb_MageTesting_Block_Adminhtml_Success extends Mage_Adminhtml_Block_Template
{
    protected $_wizard = null;

    protected function getWizard()
    {
        if (is_null($this->_wizard)) {
            $this->_wizard = Mage::getModel('magetesting/wizard');
        }

        return $this->_wizard;
    }

    protected function checkStatus()
    {
        /* @var RocketWeb_MageTesting_Model_Store_Status */
        $store = Mage::getModel('magetesting/api_store_status');

        $domain = $this->getWizard()->getStoreFrontendUrl();
        $domain = parse_url($domain);
        $domain = (isset($domain['path'])) ? trim($domain['path'], '/') : '';

        $store->setUsername($this->getWizard()->getAccountUsername())
              ->setApikey($this->getWizard()->getAccountApikey())
              ->setDomain($domain);

        try {
            $result = $store->check();

            return $result->getStatus();

        } catch (RocketWeb_MageTesting_Exception $e) {
            return 'unknown';
        } catch (Exception $e) {
            Mage::logException($e);
            return 'unknown';
        }

        return false;
    }

    protected function _beforeToHtml()
    {
        $buttonBlock = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Import store once again.'),
                'onclick' => '$(\'magetesting-reset\').submit()',
                'class' => 'process_account_button'
            ) );
        $this->setChild('reset_wizard_state_button',$buttonBlock);

        $buttonBlock = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Update status'),
                'onclick' => 'location.reload(true);',
                'class' => 'check_status_button'
            ) );
        $this->setChild('check_status_button',$buttonBlock);

        return parent::_beforeToHtml();
    }
}