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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite extends Mage_Adminhtml_Block_Widget_Container
{
    protected function prepareButtons()
    {
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->removeButton('add');
        $this->removeButton('save');
        $this->removeButton('edit');

        $supportLink = Mage::getStoreConfig('magetesting/wizard/support_link');
        $this->_addButton('goto_support', array(
                'label'     => Mage::helper('magetesting')->__('Support'),
                'onclick'   => 'window.open(\''.$supportLink.'\', \'_blank\'); return false;',
                'class'     => 'button_link'
        ));

        $this->_addButton('reset_wizard', array(
            'label'     => Mage::helper('magetesting')->__('Reset wizard'),
            'onclick'   => 'setLocation(\''.$this->getUrl('*/*/resetWizard').'\');',
            'class'     => 'end_button'
        ));
    }

    protected function getInitializationBlockHtml()
    {
        $initializationBlock = $this->getLayout()->createBlock(
                'magetesting/adminhtml_wizard_initialization'
        );
    
        return $initializationBlock->toHtml();
    }

    protected function _beforeToHtml()
    {
        $buttonBlock = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData( array(
                'id' => 'wizard_complete',
                'label'   => Mage::helper('magetesting')->__('Finish'),
                'onclick' => 'setLocation(\''.$this->getUrl('*/*/complete').'\');',
                'class' => 'end_button',
                'style' => 'display: none'
            ) );
        $this->setChild('end_button',$buttonBlock);

        $this->setChild(
            'step_account',
            $this->helper('magetesting/wizard')->createBlock('createtestsite_account')
        );
        $this->setChild(
            'step_storebackup',
            $this->helper('magetesting/wizard')->createBlock('createtestsite_storebackup')
        );
        $this->setChild(
            'step_dbbackup',
            $this->helper('magetesting/wizard')->createBlock('createtestsite_dbbackup')
        );
        $this->setChild(
            'step_connection',
            $this->helper('magetesting/wizard')->createBlock('createtestsite_connection')
        );
        $this->setChild(
            'step_import',
            $this->helper('magetesting/wizard')->createBlock('createtestsite_import')
        );

        $this->setId('wizardCreateTestSite');

        $this->_headerText = Mage::helper('magetesting')->__('Create Test Site in Mage Testing');

        $this->prepareButtons();

        $this->setTemplate('widget/form/container.phtml');

        return parent::_beforeToHtml();
    }

    protected function _toHtml()
    {
        return parent::_toHtml()
            . $this->getInitializationBlockHtml()
            . $this->getChildHtml('step_account')
            . $this->getChildHtml('step_storebackup')
            . $this->getChildHtml('step_dbbackup')
            . $this->getChildHtml('step_connection')
            . $this->getChildHtml('step_import')
            . $this->getChildHtml('end_button');
    }


}