<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_MageTesting
 * @copyright  Copyright (c) 2013 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_MageTesting_Helper_Filesystem extends Mage_Core_Helper_Abstract
{
    const INFO_WRITABLE = 1;
    const INFO_READABLE  = 2;
    const INFO_SIZE      = 4;
    const INFO_ALL       = 7;

    public function getInfo($path, $infoOptions = self::INFO_ALL, $skipFiles = array())
    {
        $info = array();
        if ($infoOptions & self::INFO_READABLE) {
            $info['readable'] = true;
        }

        if ($infoOptions & self::INFO_WRITABLE) {
            $info['writable'] = true;
        }

        if ($infoOptions & self::INFO_SIZE) {
            $info['size'] = 0;
        }

        $filesystemIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST
        );

        $iterator = new Mage_Backup_Filesystem_Iterator_Filter($filesystemIterator, $skipFiles);

        foreach ($iterator as $item) {
            if (($infoOptions & self::INFO_WRITABLE) && !$item->isWritable()) {
                $info['writable'] = false;
            }

            if (($infoOptions & self::INFO_READABLE) && !$item->isReadable()) {
                $info['readable'] = false;
            }

            if ($infoOptions & self::INFO_SIZE && !$item->isDir()) {
                $info['size'] += $item->getSize();
            }
        }

        return $info;
    }
}