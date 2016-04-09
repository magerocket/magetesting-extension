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

class RocketWeb_MageTesting_Controller_Adminhtml_BaseController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
    
        if ($this->getRequest()->isXmlHttpRequest() &&
                !Mage::getSingleton('admin/session')->isLoggedIn()) {
    
            exit(json_encode( array(
                    'ajaxExpired' => 1,
                    'ajaxRedirect' => $this->_getRefererUrl()
            )));
        }

        return $this;
    }

    protected function _initAction()
    {
        $this->loadLayout()
             ->_setActiveMenu('magetesting/wizard')
             ->_title(Mage::helper('magetesting')->__('Mage Testing'))
             ->_title(Mage::helper('magetesting')->__('Create Test Site'));

        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/magetesting');
    }

}