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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Import extends RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Abstract
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('wizardInstallationImport');

        $this->setTemplate('rocketweb_magetesting/wizard/createtestsite/import.phtml');
    }

    protected function _beforeToHtml()
    {
        $params = array(
            '\''.$this->getUrl('*/mtwizard/importStore').'\'',
            '\'import\'',
            'function() {
            window.location.href=\''.$this->getUrl('*/mtwizard/success').'\';
        }'
        );

        $buttonBlock = $this->getLayout()
                            ->createBlock('adminhtml/widget_button')
                            ->setData( array(
                                'label'   => Mage::helper('magetesting')->__('Import Store'),
                                'onclick' => 'WizardObj.processImportStep('.implode(',',$params).');',
                                'class' => 'process_import_button'
                            ) );
        $this->setChild('process_import_button',$buttonBlock);

        return parent::_beforeToHtml();
    }

    protected function getEdition()
    {
        if (Mage::helper('magetesting')->isMageEnterprise()) {
            return 'Enterprise';
        } else {
            return 'Community';
        }
    }

    protected function getStoreName()
    {
        return Mage::app()->getStore()->getFrontendName();
    }
}