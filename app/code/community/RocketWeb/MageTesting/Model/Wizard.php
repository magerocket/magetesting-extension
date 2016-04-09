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

/**
 * Class RocketWeb_MageTesting_Model_Wizard
 *
 */
class RocketWeb_MageTesting_Model_Wizard extends Varien_Object
{
    const STATUS_NOT_STARTED = 0;
    const STATUS_ACTIVE      = 1;
    const STATUS_COMPLETED   = 2;

    private function getConfigValue($key)
    {
        return Mage::getStoreConfig('rocketweb_magetesting/'. $key);
    }

    public function getWizardStatus()
    {
        return $this->getConfigValue('wizard/status');
    }

    public function getWizardStep()
    {
        return $this->getConfigValue('wizard/step');
    }

    public function getAccountUsername()
    {
        return $this->getConfigValue('account/username');
    }

    public function getAccountApikey()
    {
        $encryptor = Mage::helper('core')->getEncryptor();
    
        return $encryptor->decrypt($this->getConfigValue('account/apikey'));
    }

    public function getWizardStorebackup()
    {
        return $this->getConfigValue('wizard/storebackup');
    }

    public function getWizardDbbackup()
    {
        return $this->getConfigValue('wizard/dbbackup');
    }

    public function getConnectionProtocol()
    {
        return $this->getConfigValue('connection/protocol');
    }

    public function getConnectionHost()
    {
        return $this->getConfigValue('connection/host');
    }

    public function getConnectionPort()
    {
        return $this->getConfigValue('connection/port');
    }

    public function getConnectionUsername()
    {
        return $this->getConfigValue('connection/username');
    }

    public function getConnectionPassword()
    {
        return $this->getConfigValue('connection/password');
    }

    public function getConnectionRoot()
    {
        return $this->getConfigValue('connection/root');
    }
    
    public function getStoreFrontendUrl()
    {
        return $this->getConfigValue('store/frontend/url');
    }

    public function getStoreBackendUrl()
    {
        return $this->getConfigValue('store/backend/url');
    }

    public function isCompleted()
    {
        return $this->getWizardStatus() == self::STATUS_COMPLETED;
    }

    public function save()
    {
        if (!count($this->getData())) {
            return;
        }
        
        foreach ($this->getData() as $key => $value) {
            $key = str_replace("_", "/", $key);
            Mage::getConfig()->saveConfig('rocketweb_magetesting/'. $key, $value);
        }

        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }
}