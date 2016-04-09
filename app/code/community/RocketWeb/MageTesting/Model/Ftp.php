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
 * Class RocketWeb_MageTesting_Model_Ftp
 *
 */
class RocketWeb_MageTesting_Model_Ftp extends Varien_Object
{
    const DEFAULT_FTP_PORT = 21;
    
    protected $connection = false;
    protected $isPassive = false; 
    
    public function getHost()
    {
        $host = trim(str_replace('ftp://', '', $this->getData('host')), '/');
        return $host;
    }

    public function getPort()
    {
        if (strlen($this->getData('port'))) {
            return (int) $this->getData('port');
        }
        
        return self::DEFAULT_FTP_PORT;
    }

    public function findRootPath()
    {
        $this->connection = $this->ftpConnect();
        
        if (!$this->connection) {
            throw new RocketWeb_MageTesting_Exception("Couldn't log into " . $this->getHost() . ' server.');
        }

        for ($i = 0; $i < 3; $i++) {
            $contents = $this->listDirectory();

            $magentoPath = $this->checkForMagentoFolders($contents);

            if (strlen($magentoPath)) {
                return $magentoPath;
            }

            if (!$this->changeDirectory(
                $this->getMatchingDirectory($contents)
            )) {
                throw new RocketWeb_MageTesting_Exception('Magento root has not been found.');
            }
        }

        if (!$contents){
            throw new RocketWeb_MageTesting_Exception('Magento root has not been found.');
        }        
    }

    protected function ftpConnect()
    {
        $connection = @ftp_connect($this->getHost(), $this->getPort(), 30);

        if ($connection) {
            if (@ftp_login($connection, $this->getUsername(), $this->getPassword())) {
                return $connection;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function listDirectory()
    {
        $contents = ftp_nlist($this->connection, ".");

        if (!$contents && !$this->isPassive) {
            ftp_pasv($this->connection, true);
            $this->isPassive = true;
            $contents = ftp_nlist($this->connection, ".");
        }

        return $contents;
    }

    protected function checkForMagentoFolders($contents)
    {
        if (in_array('app', $contents)
            && in_array('lib', $contents)
            && in_array('js', $contents)
            && in_array('skin', $contents)
        ) {
            return ftp_pwd($this->connection);
        }

        return false;
    }

    protected function getMatchingDirectory($contents)
    {
        $baseFolders = array('www', 'public_html', 'web', 'htdocs', $this->getHost());

        return array_values(array_intersect($baseFolders, $contents));
    }

    protected function changeDirectory($directory)
    {
        $result = @ftp_chdir($this->connection, current($directory));

        return $result;
    }

}