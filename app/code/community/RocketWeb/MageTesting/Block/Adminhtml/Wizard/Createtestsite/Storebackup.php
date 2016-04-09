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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Storebackup extends RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Abstract
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('wizardInstallationStorebackup');

        $this->setTemplate('rocketweb_magetesting/wizard/createtestsite/storebackup.phtml');
    }

    protected function _beforeToHtml()
    {
        $buttonBlock = $this->getLayout()
        ->createBlock('adminhtml/widget_button')
        ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Generate backup now'),
                'onclick' => 'WizardObj.generateBackup(\'nomedianodb\');',
                'class'   => 'generate_storebackup_button'
        ) );
        $this->setChild('generate_storebackup_button',$buttonBlock);

        $params = array(
            '\''.$this->getUrl('*/mtwizard/saveStorebackupDetails').'\'',
            '\'storebackup\'',
        );

        $buttonBlock = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Proceed'),
                'onclick' => 'WizardObj.processStorebackupStep('.implode(',',$params).');',
                'class'   => 'skip_storebackup_button'
            ) );
        $this->setChild('process_storebackup_button',$buttonBlock);

        return parent::_beforeToHtml();
    }
    
    protected function getStoreBackups()
    {
        $backups = array();

        $collection = Mage::getSingleton('backup/fs_collection');

        foreach ($collection as $backup) {
            if ($backup->getType() !== $this->getStorebackupType()) continue;

            $backups[] = $backup->getBasename();
        }

        return $backups;
    }

    protected function getStorebackupType()
    {
        return RocketWeb_MageTesting_Helper_Data::TYPE_SNAPSHOT_WITHOUT_MEDIA_WITHOUT_DB;
    }
}