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
 * Class RocketWeb_MageTesting_Model_Api_User
 *
 */
class RocketWeb_MageTesting_Model_Api_User extends RocketWeb_MageTesting_Model_Api_Abstract
{
    public function validate()
    {
        $response = $this->_getClient()->restGet(
            '/api/user', 
            $this->getData()
        )->getBody();

        $response = $this->_validateResponse($response);

        $result = new Varien_Object();
        $result->setFirstname(isset($response['firstname']) ? $response['firstname'] : '');
        $result->setLastname(isset($response['lastname']) ? $response['lastname'] : '');

        if (isset($response['remainingStores']) && $response['remainingStores'] > 0) {
            $result->setRemainingStores($response['remainingStores']);
            $result->setCanAddStore(true);
        } else {
            $result->setRemainingStores(0);
            $result->setCanAddStore(false);
        }

        return $result;
    }
}