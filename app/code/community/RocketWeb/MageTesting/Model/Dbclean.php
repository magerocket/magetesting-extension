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


class RocketWeb_MageTesting_Model_Dbclean
{

    /**
     * Buffer length for multi rows
     * default 100 Kb
     *
     */
    const BUFFER_LENGTH = 102400;

    /**
     * List of tables which data should not be backed up
     *
     * @var array
     */
    protected $_ignoreDataTablesList = array(
        'core/cache',
        'core/cache_option',
        'core/cache_tag',
        'core/session',
        //'importexport/importdata',
        'log/customer',
        'log/quote_table',
        'log/summary_table',
        'log/summary_type_table',
        'log/url_table',
        'log/url_info_table',
        'log/visitor',
        'log/visitor_info',
        'index/event',
        'index/process_event',
        'report_event',
        'report_viewed_product_index',
        'dataflow_batch_export',
        'dataflow_batch_import',
    );

    /**
     * Retrieve resource model
     *
     * @return Mage_Backup_Model_Mysql4_Db
     */
    public function getResource()
    {
        return Mage::getResourceSingleton('backup/db');
    }

    public function getTables()
    {
        return $this->getResource()->getTables();
    }

    public function getTableCreateScript($tableName, $addDropIfExists=false)
    {
        return $this->getResource()->getTableCreateScript($tableName, $addDropIfExists);
    }

    public function getTableDataDump($tableName)
    {
        return $this->getResource()->getTableDataDump($tableName);
    }

    public function getHeader()
    {
        return $this->getResource()->getHeader();
    }

    public function getFooter()
    {
        return $this->getResource()->getFooter();
    }

    public function renderSql()
    {
        ini_set('max_execution_time', 0);
        $sql = $this->getHeader();

        $tables = $this->getTables();
        foreach ($tables as $tableName) {
            $sql.= $this->getTableCreateScript($tableName, true);
            $sql.= $this->getTableDataDump($tableName);
        }

        $sql.= $this->getFooter();
        return $sql;
    }

    /**
     * Create backup and stream write to adapter
     *
     * @param Mage_Backup_Model_Backup $backup
     * @return Mage_Backup_Model_Db
     */
    public function createBackup(Mage_Backup_Model_Backup $backup)
    {
        $backup->open(true);

        $this->getResource()->beginTransaction();

        $tables = $this->getResource()->getTables();

        $backup->write($this->getResource()->getHeader());

        $ignoreDataTablesList = $this->getIgnoreDataTablesList();

        foreach ($tables as $table) {
            $backup->write($this->getResource()->getTableHeader($table)
                . $this->getResource()->getTableDropSql($table) . "\n");
            $backup->write($this->getResource()->getTableCreateSql($table, false) . "\n");

            $tableStatus = $this->getResource()->getTableStatus($table);

            if ($tableStatus->getRows() && !in_array($table, $ignoreDataTablesList)) {
                $backup->write($this->getResource()->getTableDataBeforeSql($table));

                if ($tableStatus->getDataLength() > self::BUFFER_LENGTH) {
                    if ($tableStatus->getAvgRowLength() < self::BUFFER_LENGTH) {
                        $limit = floor(self::BUFFER_LENGTH / $tableStatus->getAvgRowLength());
                        $multiRowsLength = ceil($tableStatus->getRows() / $limit);
                    }
                    else {
                        $limit = 1;
                        $multiRowsLength = $tableStatus->getRows();
                    }
                }
                else {
                    $limit = $tableStatus->getRows();
                    $multiRowsLength = 1;
                }

                for ($i = 0; $i < $multiRowsLength; $i ++) {
                    $backup->write($this->getResource()->getTableDataSql($table, $limit, $i*$limit));
                }

                $backup->write($this->getResource()->getTableDataAfterSql($table));
            }
        }
        $backup->write($this->getResource()->getTableForeignKeysSql());
        $backup->write($this->getResource()->getFooter());

        $this->getResource()->commitTransaction();

        $backup->close();

        return $this;
    }

    /**.
     * Returns the list of tables which data should not be backed up
     *
     * @return array
     */
    public function getIgnoreDataTablesList()
    {
        $result = array();
        $resource = Mage::getSingleton('core/resource');

        foreach ($this->_ignoreDataTablesList as $table) {
            $result[] = $resource->getTableName($table);
        }

        return $result;
    }
}