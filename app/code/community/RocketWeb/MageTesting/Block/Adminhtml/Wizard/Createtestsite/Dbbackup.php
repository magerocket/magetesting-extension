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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Dbbackup extends RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Abstract
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('wizardInstallationDbbackup');

        $this->setTemplate('rocketweb_magetesting/wizard/createtestsite/dbbackup.phtml');
    }

    protected function _beforeToHtml()
    {
        $buttonBlock = $this->getLayout()
        ->createBlock('adminhtml/widget_button')
        ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Generate backup now'),
                'onclick' => 'WizardObj.generateBackup(\'dbclean\');',
                'class'   => 'generate_dbbackup_button'
        ) );
        $this->setChild('generate_dbbackup_button',$buttonBlock);

        $params = array(
                '\''.$this->getUrl('*/mtwizard/saveDbBackupDetails').'\'',
                '\'dbbackup\'',
        );

        $buttonBlock = $this->getLayout()
        ->createBlock('adminhtml/widget_button')
        ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Proceed'),
                'onclick' => 'WizardObj.processDbbackupStep('.implode(',',$params).');',
                'class'   => 'skip_dbbackup_button'
        ) );
        $this->setChild('process_dbbackup_button',$buttonBlock);

        return parent::_beforeToHtml();
    }

    protected function getDbBackups()
    {
        $backups = array();
    
        $collection = Mage::getSingleton('backup/fs_collection');
    
        foreach ($collection as $backup) {
            if ($backup->getType() !== $this->getDbbackupType()) continue;
    
            $backups[] = $backup->getBasename();
        }
    
        return $backups;
    }

    protected function getDbbackupType()
    {
        return RocketWeb_MageTesting_Helper_Data::TYPE_DB_CLEAN;
    }
}