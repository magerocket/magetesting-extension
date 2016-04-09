<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Magetesting_Backup
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class to work with full filesystem and database backups
 *
 * @category    Mage
 * @package     Magetesting_Backup
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magetesting_Backup_Snapshot extends Magetesting_Backup_Filesystem
{
    /**
     * Database backup manager
     *
     * @var Magetesting_Backup_Db
     */
    protected $_dbBackupManager;

    /**
     * Implementation Rollback functionality for Snapshot
     *
     * @throws Mage_Exception
     * @return bool
     */
    public function rollback()
    {
        $result = parent::rollback();

        $this->_lastOperationSucceed = false;

        try {
            $this->_getDbBackupManager()->rollback();
        } catch (Exception $e) {
            $this->_removeDbBackup();
            throw $e;
        }

        $this->_removeDbBackup();
        $this->_lastOperationSucceed = true;

        return $result;
    }

    /**
     * Implementation Create Backup functionality for Snapshot
     *
     * @throws Mage_Exception
     * @return bool
     */
    public function create()
    {
        $this->_getDbBackupManager()->create();

        try {
            $result = parent::create();
        } catch (Exception $e) {
            $this->_removeDbBackup();
            throw $e;
        }

        $this->_lastOperationSucceed = false;
        $this->_removeDbBackup();
        $this->_lastOperationSucceed = true;

        return $result;
    }

    /**
     * Overlap getType
     *
     * @return string
     * @see Magetesting_Backup_Interface::getType()
     */
    public function getType()
    {
        return 'snapshot';
    }

    /**
     * Create Db Instance
     *
     * @return Magetesting_Backup_Interface
     */
    protected function _createDbBackupInstance()
    {
        return Magetesting_Backup::getBackupInstance(RocketWeb_MageTesting_Helper_Data::TYPE_DB)
            ->setBackupExtension(Mage::helper('magetesting')->getExtensionByType(RocketWeb_MageTesting_Helper_Data::TYPE_DB))
            ->setTime($this->getTime())
            ->setBackupsDir(Mage::getBaseDir("var"))
            ->setResourceModel($this->getResourceModel());
    }

    /**
     * Get database backup manager
     *
     * @return Magetesting_Backup_Db
     */
    protected function _getDbBackupManager()
    {
        if (is_null($this->_dbBackupManager)) {
            $this->_dbBackupManager = $this->_createDbBackupInstance();
        }

        return $this->_dbBackupManager;
    }

    /**
     * Remove Db backup after added it to the snapshot
     *
     * @return Magetesting_Backup_Snapshot
     */
    protected function _removeDbBackup(){
        @unlink($this->_getDbBackupManager()->getBackupPath());
        return $this;
    }
}
