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

class RocketWeb_MageTesting_Block_Adminhtml_Backup_Grid extends Mage_Adminhtml_Block_Backup_Grid
{
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $options = $this->getColumn('type')->getOptions();
        $this->getColumn('type')->setOptions(
            array_merge($options, array(
                'dbclean' => Mage::helper('backup')->__('Clean DB (without log and cache tables)'),
                'nomedianodb' => Mage::helper('backup')->__('System (Excluding Media and DB backups)')
            ))
        );

        $this->getColumn('action')->setData('renderer', 
            'RocketWeb_MageTesting_Block_Adminhtml_Widget_Grid_Column_Renderer_Rollback'
        );

        return $this;
    }

}