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
 * Class RocketWeb_MageTesting_Model_Backup
 *
 * Class rewrite added to let us add "nomedia" backup type to database in older
 * Magento versions.
 */
class RocketWeb_MageTesting_Model_Backup extends Mage_Backup_Model_Backup
{
    private $_type  = 'db';

    public function setType($value='db')
    {
        if(!in_array($value, array('snapshot','db','media','nomedia','view','nomedianodb','dbclean'))) {
            $value = 'db';
        }

        $this->_type = $value;
        $this->setData('type', $this->_type);

        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getFileName()
    {
        $filename = $this->getTime() . "_" . $this->getType();
        $backupName = $this->getName();

        if (!empty($backupName)) {
            $filename .= '_' . $backupName;
        }

        $filename .= '.' . Mage::helper('magetesting')->getExtensionByType($this->getType());

        return $filename;
    }
}