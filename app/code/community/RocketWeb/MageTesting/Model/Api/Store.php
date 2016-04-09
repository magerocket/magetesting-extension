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
 * Class RocketWeb_MageTesting_Model_Api_Store
 *
 */
class RocketWeb_MageTesting_Model_Api_Store extends RocketWeb_MageTesting_Model_Api_Abstract
{
    public function save()
    {
        $response = $this->_getClient()->restPost(
            '/api/store', 
            $this->getData()
        )->getBody();

        $response = $this->_validateResponse($response);

        $result = new Varien_Object();
        $result->setFrontendUrl(isset($response['store_frontend_url']) ? $response['store_frontend_url'] : '');
        $result->setBackendUrl(isset($response['store_backend_url']) ? $response['store_backend_url'] : '');

        return $result;
    }
}