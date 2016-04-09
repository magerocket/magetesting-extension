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

class RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Connection extends RocketWeb_MageTesting_Block_Adminhtml_Wizard_Createtestsite_Abstract
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('wizardConnection');

        $this->setTemplate('rocketweb_magetesting/wizard/createtestsite/connection.phtml');
    }

    protected function _beforeToHtml()
    {
        $params = array(
                '\''.$this->getUrl('*/mtwizard/saveConnectionDetails').'\'',
                '\'connection\''
        );
        $buttonBlock = $this->getLayout()
        ->createBlock('adminhtml/widget_button')
        ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Proceed'),
                'onclick' => 'WizardObj.processConnectionStep('.implode(',',$params).');',
                'class' => 'process_connection_button'
        ) );
        $this->setChild('process_connection_button',$buttonBlock);

        $params = array(
            '\''.$this->getUrl('*/mtwizard/findRootPath').'\'',
        );
        $findRootBlock = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData( array(
                'label'   => Mage::helper('magetesting')->__('Find path'),
                'onclick' => 'WizardObj.findRootPath('.implode(',',$params).');',
                'class' => 'find_root_button'
            ) );
        $this->setChild('find_root_button',$findRootBlock);

        return parent::_beforeToHtml();
    }

    protected function getProtocols() 
    {
        return array(
            'ssh' => 'ssh',
            'ftp' => 'ftp',
        );
    }
    
    protected function getConnectionHost()
    {
        $connectionHost = $this->getWizard()->getConnectionHost();

        if (strlen($connectionHost)) {
            return $connectionHost;
        }

        return $_SERVER['HTTP_HOST'];
    }
}