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

class RocketWeb_MageTesting_Adminhtml_MtwizardController extends RocketWeb_MageTesting_Controller_Adminhtml_BaseController
{
    protected function _initAction()
    {
        parent::_initAction();

        Mage::helper('magetesting/wizard')->addWizardJs();

        return $this;
    }

    public function indexAction()
    {
        /* @var $wizardHelper RocketWeb_MageTesting_Helper_Wizard */
        $wizardHelper = Mage::helper('magetesting/wizard');
        /* @var $wizard RocketWeb_MageTesting_Model_Wizard */
        $wizard = Mage::getModel('magetesting/wizard');
        
        if ($wizard->isCompleted()) {
            return $this->_redirect('*/*/success');
        }

        if (!$wizard->getWizardStatus()) {
            $wizard->setWizardStatus(
                RocketWeb_MageTesting_Model_Wizard::STATUS_ACTIVE
            );
        }

        if (!$wizard->getWizardStep()) {
            $wizard->setWizardStep(
                $wizardHelper->getFirstStep()
            );
        }

        $wizard->save();
        
        $this->_initAction()
            ->_addContent($wizardHelper->createBlock('createtestsite'))
            ->renderLayout();
    }

    public function saveAccountDetailsAction()
    {
        $username = $this->getRequest()->getParam('username');
        $apikey = $this->getRequest()->getParam('apikey');
        $nextStep = $this->getRequest()->getParam('next_step');
    
        if (!strlen($username) || strlen($apikey) != 40) {
            exit(json_encode(array(
                    'type' => 'error',
                    'message' => Mage::helper('magetesting')->__('Please fill both username and API key fields. API key should be 40 characters long.')
            )));
        }

        $user = Mage::getModel('magetesting/api_user');
        $user->setUsername($username);
        $user->setApikey($apikey);

        try { 
            $result = $user->validate();
        } catch (RocketWeb_MageTesting_Exception $e) {
            exit(json_encode(array(
                'type' => 'error',
                'message' => Mage::helper('magetesting')->__($e->getMessage())
            )));
        } catch (Exception $e) {
            Mage::logException($e);
            exit(json_encode(array(
                'type' => 'error',
                'message' => Mage::helper('magetesting')->__($e->getMessage())
            )));
        }
        
        if ($result->getCanAddStore() === false) {
            exit(json_encode(array(
                'type' => 'error',
                'message' => Mage::helper('magetesting')->__("You can't add more stores in your Mage Testing account. Please remove existing store and try again.")
            )));
        } 

        $encryptor = Mage::helper('core')->getEncryptor();
        $apikey = $encryptor->encrypt($apikey);

        $wizard = Mage::getModel('magetesting/wizard');
        $wizard->setWizardStep($nextStep);
        $wizard->setAccountUsername($username);
        $wizard->setAccountApikey($apikey);
        $wizard->save();

        $this->getResponse()->setBody(json_encode(array(
                'type' => 'success'
        )));
    }

    public function saveStorebackupDetailsAction()
    {
        $storebackup = $this->getRequest()->getParam('storebackup');
        $nextStep = $this->getRequest()->getParam('next_step');

        if (!strlen($storebackup)) {
            exit(json_encode(array(
                    'type' => 'error',
                    'message' => Mage::helper('magetesting')->__('Please select store files backup.')
            )));
        }

        $wizard = Mage::getModel('magetesting/wizard');
        $wizard->setWizardStep($nextStep);
        $wizard->setWizardStorebackup($storebackup);
        $wizard->save();
    
        $this->getResponse()->setBody(json_encode(array(
                'type' => 'success'
        )));
    }

    public function saveDbBackupDetailsAction()
    {
        $dbbackup = $this->getRequest()->getParam('dbbackup');
        $nextStep = $this->getRequest()->getParam('next_step');
    
        if (!strlen($dbbackup)) {
            exit(json_encode(array(
                    'type' => 'error',
                    'message' => Mage::helper('magetesting')->__('Please select database backup.')
            )));
        }

        $wizard = Mage::getModel('magetesting/wizard');
        $wizard->setWizardStep($nextStep);
        $wizard->setWizardDbbackup($dbbackup);
        $wizard->save();

        $this->getResponse()->setBody(json_encode(array(
                'type' => 'success'
        )));
    }
    
    public function saveConnectionDetailsAction()
    {
        $protocol = $this->getRequest()->getParam('protocol');
        $host = $this->getRequest()->getParam('host');
        $port = $this->getRequest()->getParam('port');
        $username = $this->getRequest()->getParam('username');
        $password = $this->getRequest()->getParam('password');
        $root = $this->getRequest()->getParam('root');
        $nextStep = $this->getRequest()->getParam('next_step');

        if (!strlen($protocol) || !strlen($host) ||  
            !strlen($username) || !strlen($password) || !strlen($root)) {
            exit(json_encode(array(
                    'type' => 'error',
                    'message' => Mage::helper('magetesting')->__('Please fill connection details form.')
            )));
        }

        $wizard = Mage::getModel('magetesting/wizard');
        $wizard->setWizardStep($nextStep);
        $wizard->setConnectionProtocol($protocol);
        $wizard->setConnectionHost($host);
        $wizard->setConnectionPort($port);
        $wizard->setConnectionUsername($username);
        $wizard->setConnectionPassword($password);
        $wizard->setConnectionRoot($root);
        $wizard->save();

        $this->getResponse()->setBody(json_encode(array(
                'type' => 'success'
        )));
    }

    public function importStoreAction()
    {
        $import = $this->getRequest()->getParam('import');

        if (!strlen($import) || $import != 1) {
            exit(json_encode(array(
                'type' => 'error',
                'message' => Mage::helper('magetesting')->__('Import has not beed scheduled successfully.')
            )));
        }
        
        /* @var RocketWeb_MageTesting_Model_Store */
        $store = Mage::getModel('magetesting/api_store');

        /* @var RocketWeb_MageTesting_Model_Wizard */
        $wizard = Mage::getModel('magetesting/wizard');

        $store->setUsername($wizard->getAccountUsername())
            ->setApikey($wizard->getAccountApikey()) //WoAROTP7dMZMxO2JbqtFWoAROTP7dMZMxO2JbqtF
            ->setName(Mage::app()->getStore()->getFrontendName())
            ->setDescription('')
            ->setEdition(Mage::helper('magetesting')->getEditionCode())
            ->setVersion(Mage::getVersion())
            ->setProtocol($wizard->getConnectionProtocol())
            ->setHost($wizard->getConnectionHost())
            ->setLogin($wizard->getConnectionUsername())
            ->setPassword($wizard->getConnectionPassword())
            ->setPort($wizard->getConnectionPort())
            ->setPathSql(rtrim($wizard->getConnectionRoot(), "/") . '/var/backups/' . $wizard->getWizardDbbackup())
            ->setPathBackup(rtrim($wizard->getConnectionRoot(), "/") . '/var/backups/' . $wizard->getWizardStorebackup());

        try {
            $result = $store->save();

            $wizard->setWizardStatus(RocketWeb_MageTesting_Model_Wizard::STATUS_COMPLETED);
            $wizard->setStoreFrontendUrl($result->getFrontendUrl());
            $wizard->setStoreBackendUrl($result->getBackendUrl());
            $wizard->save();
        } catch (RocketWeb_MageTesting_Exception $e) {
            exit(json_encode(array(
                'type' => 'error',
                'message' => Mage::helper('magetesting')->__($e->getMessage())
            )));
        } catch (Exception $e) {
            Mage::logException($e);
            exit(json_encode(array(
                'type' => 'error',
                'message' => Mage::helper('magetesting')->__($e->getMessage())
            )));
        }

        $this->getResponse()->setBody(json_encode(array(
            'type' => 'success',
        )));
    }
    
    public function findRootPathAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->getUrl('*/*/index');
        }

        $response = new Varien_Object();

        try {
            $protocol = $this->getRequest()->getParam('protocol');
            $host = $this->getRequest()->getParam('host');
            $port = $this->getRequest()->getParam('port');
            $username = $this->getRequest()->getParam('username');
            $password = $this->getRequest()->getParam('password');
            
            if ($protocol === 'ssh') {
                $response->setRootPath(Mage::getBaseDir());
            } else {
                $ftp = Mage::getModel('magetesting/ftp');
                
                $ftp->setHost($host);
                $ftp->setPort($port);
                $ftp->setUsername($username);
                $ftp->setPassword($password);
                
                $response->setRootPath($ftp->findRootPath());
            }

            $response->setType('success');
            $response->setMessage(Mage::helper('backup')->__('Magento root path has been successfully found.'));
        } catch (Exception  $e) {
            Mage::log($e->getMessage());
            $errorMessage = Mage::helper('backup')->__('An error occurred while checking magento root path: ' . $e->getMessage());
        }

        if (isset($errorMessage)) {
            $response->setType('error');
            $response->setMessage($errorMessage);
        }

        $this->getResponse()->setBody($response->toJson());
    }

    public function successAction()
    {
        /* @var $wizardHelper RocketWeb_MageTesting_Helper_Wizard */
        $wizardHelper = Mage::helper('magetesting/wizard');
        $wizard = Mage::getModel('magetesting/wizard');

        if (!$wizard->isCompleted()) {
            return $this->_redirect('*/*/index');
        }

        if ($this->getRequest()->isPost()) {
            $wizard->setWizardStep($wizardHelper->getFirstStep());
            $wizard->setWizardStatus(RocketWeb_MageTesting_Model_Wizard::STATUS_ACTIVE);
            $wizard->setStoreFrontendUrl('');
            $wizard->setStoreBackendUrl('');
            $wizard->setWizardStorebackup('');
            $wizard->setWizardDbbackup('');
            $wizard->save();

            return $this->_redirect('*/*/index');
        }

        $this->_initAction()
            ->renderLayout();
    }

    public function resetWizardAction()
    {
        /* @var $wizardHelper RocketWeb_MageTesting_Helper_Wizard */
        $wizardHelper = Mage::helper('magetesting/wizard');
        $wizard = Mage::getModel('magetesting/wizard');

        $wizard->setWizardStep($wizardHelper->getFirstStep());
        $wizard->setWizardStatus(RocketWeb_MageTesting_Model_Wizard::STATUS_ACTIVE);
        $wizard->setStoreFrontendUrl('');
        $wizard->setStoreBackendUrl('');
        $wizard->setWizardStorebackup('');
        $wizard->setWizardDbbackup('');
        $wizard->save();

        return $this->_redirect('*/*/index');
    }

}