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
 * Class RocketWeb_MageTesting_Model_Api_Abstract
 *
 */
abstract class RocketWeb_MageTesting_Model_Api_Abstract extends Varien_Object
{
    const API_URL = 'http://www.magetesting.com/';

    const ERROR_CONNECTION = 'Problem occured when trying to connect with Mage Testing web service. Please try again later or contact with support.';

    /**
     * @return Zend_Rest_Client
     */
    protected function _getClient()
    {
        return new Zend_Rest_Client(RocketWeb_MageTesting_Model_Api_Abstract::API_URL);
    }

    /**
     * @param $response
     * @return mixed
     * @throws RocketWeb_MageTesting_Exception
     */
    protected function _validateResponse($response)
    {
        $response = Zend_Json::decode($response);

        if (!isset($response['type'])) {
            throw new RocketWeb_MageTesting_Exception(self::ERROR_CONNECTION);
        }

        if ($response['type'] !== 'success') {
            if (isset($response['message']) && strlen($response['message'])) {
                throw new RocketWeb_MageTesting_Exception($response['message']);
            }

            throw new RocketWeb_MageTesting_Exception(self::ERROR_CONNECTION);
        }
        return $response;
    }
}