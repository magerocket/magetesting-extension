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

class RocketWeb_MageTesting_Adminhtml_MtbackupController extends RocketWeb_MageTesting_Controller_Adminhtml_BaseController
{
    public function createAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->getUrl('*/*/index');
        }

        $response = new Varien_Object();

        try {
            $type = $this->getRequest()->getParam('type');
        
            if ($type != RocketWeb_MageTesting_Helper_Data::TYPE_SNAPSHOT_WITHOUT_MEDIA_WITHOUT_DB &&
                $type != RocketWeb_MageTesting_Helper_Data::TYPE_DB_CLEAN
            ) {
                $type = RocketWeb_MageTesting_Helper_Data::TYPE_DB_CLEAN;
            }

            if ($type === RocketWeb_MageTesting_Helper_Data::TYPE_SNAPSHOT_WITHOUT_MEDIA_WITHOUT_DB) {
                /* @var $backupDb Magetesting_Backup_Nomedianodb */
                $backupFiles = Magetesting_Backup::getBackupInstance($type)
                    ->setBackupExtension(Mage::helper('magetesting')->getExtensionByType($type))
                    ->setTime(time())
                    ->setBackupsDir(Mage::helper('magetesting')->getBackupsDir())
                    ->setRootDir(Mage::getBaseDir())
                    ->addIgnorePaths(Mage::helper('magetesting')->getBackupIgnorePaths());

                $backupFiles->create();
            } else {
                /* @var $backupDb Mage_Backup_Model_Db */
                $backupDb = Mage::getModel('magetesting/dbclean');
                $backup   = Mage::getModel('backup/backup')
                ->setTime(time())
                ->setType($type)
                ->setPath(Mage::getBaseDir("var") . DS . "backups");

                Mage::register('backup_model', $backup);

                $backupDb->createBackup($backup);
            }

            $this->_getSession()->addSuccess(Mage::helper('adminhtml')->__('Backup successfully created'));
            $response->setRedirectUrl($this->getUrl('*/*/index'));
        } catch (Exception  $e) {
            Mage::log($e->getMessage());
            $errorMessage = Mage::helper('backup')->__('An error occurred while creating the backup: ' . $e->getMessage());
        }

        if (isset($errorMessage)) {
            $response->setType('error');
            $response->setMessage($errorMessage);
        }

        $this->getResponse()->setBody($response->toJson());
    }

}