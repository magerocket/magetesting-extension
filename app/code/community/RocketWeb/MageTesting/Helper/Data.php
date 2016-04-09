<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_MageTesting
 * @copyright  Copyright (c) 2013 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_MageTesting_Helper_Data extends Mage_Backup_Helper_Data
{
    const TYPE_DB = 'db';
    const TYPE_FILESYSTEM = 'filesystem';
    const TYPE_SYSTEM_SNAPSHOT = 'snapshot';
    const TYPE_MEDIA = 'media';
    const TYPE_SNAPSHOT_WITHOUT_MEDIA = 'nomedia';
    const TYPE_SNAPSHOT_WITHOUT_MEDIA_WITHOUT_DB = 'nomedianodb';
    const TYPE_DB_CLEAN = 'dbclean';

    public function getClassConstantAsJson($class)
    {
        $class = 'RocketWeb_MageTesting_'.$class;
    
        $reflectionClass = new ReflectionClass($class);
        $tempConstants = $reflectionClass->getConstants();
    
        $constants = array();
        foreach ($tempConstants as $key => $value) {
            $constants[] = array(strtoupper($key), $value);
        }
    
        return json_encode($constants);
    }

    public function isMageEnterprise() 
    {
        return Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' ) && 
               Mage::getConfig ()->getModuleConfig ( 'Enterprise_AdminGws' ) && 
               Mage::getConfig ()->getModuleConfig ( 'Enterprise_Checkout' ) && 
               Mage::getConfig ()->getModuleConfig ( 'Enterprise_Customer' );
    }

    public function isMageProfessional() 
    {
        return Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' ) && 
              !Mage::getConfig ()->getModuleConfig ( 'Enterprise_AdminGws' ) && 
              !Mage::getConfig ()->getModuleConfig ( 'Enterprise_Checkout' ) && 
              !Mage::getConfig ()->getModuleConfig ( 'Enterprise_Customer' );
    }

    public function isMageCommunity() 
    {
        return !$this->isMageEnterprise() && !$this->isMageProfessional();
    }
    
    public function getEditionCode()
    {
        if ($this->isMageEnterprise()) {
            return 'EE';
        } else {
            return 'CE';
        }
    }

    /*
     * Needs to be there because it isn't implemented in older magento.
     */
    public function getBackupsDir()
    {
        return Mage::getBaseDir('var') . DS . 'backups';
    }
    
    /*
     * Needs to be there because it isn't implemented in older magento.
     */
    public function getExtensionByType($type)
    {
        $extensions = $this->getExtensions();
        return isset($extensions[$type]) ? $extensions[$type] : '';
    }

    public function getExtensions()
    {
        return array(
            self::TYPE_SYSTEM_SNAPSHOT => 'tgz',
            self::TYPE_SNAPSHOT_WITHOUT_MEDIA => 'tgz',
            self::TYPE_SNAPSHOT_WITHOUT_MEDIA_WITHOUT_DB => 'tgz',
            self::TYPE_MEDIA => 'tgz',
            self::TYPE_DB => 'gz',
            self::TYPE_DB_CLEAN => 'gz'
        );
    }

    /*
     * Needs to be there because it isn't implemented in older magento.
     */
    public function getBackupIgnorePaths()
    {
        return array(
            '.svn',
            'maintenance.flag',
            Mage::getBaseDir('var') . DS . 'session',
            Mage::getBaseDir('var') . DS . 'cache',
            Mage::getBaseDir('var') . DS . 'full_page_cache',
            Mage::getBaseDir('var') . DS . 'locks',
            Mage::getBaseDir('var') . DS . 'log',
            Mage::getBaseDir('var') . DS . 'report'
        );
    }
}