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
 * Class RocketWeb_MageTesting_Model_Api_Store_Status
 *
 */
class RocketWeb_MageTesting_Model_Api_Store_Status extends RocketWeb_MageTesting_Model_Api_Abstract
{
    public function check()
    {
        $response = $this->_getClient()->restGet(
            '/api/store-status', 
            $this->getData()
        )->getBody();

        $response = $this->_validateResponse($response);

        $result = new Varien_Object();
        $result->setStatus(isset($response['message']) ? $response['message'] : '');

        return $result;
    }
}