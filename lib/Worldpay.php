<?php

namespace Worldpay;

/**
 * PHP library version: 2.1.0
 */

class Worldpay
{

    private $connection;

    /**
     * Library constructor
     * @param string $service_key
     *  Your worldpay service key
     * @param int $timeout
     *  Connection timeout length
     * */
    public function __construct($service_key = false, $timeout = false)
    {
        if ($service_key == false) {
            Error::throwError("key");
        }

        $this->connection = Connection::getInstance();
        $this->setServiceKey($service_key);

        if ($timeout !== false) {
            $this->setTimeout($timeout);
        }

        //
    }

    /**
     * Set api endpoint
     * @param string
     * */
    public function setEndpoint($endpoint)
    {
        $this->connection->setEndpoint($endpoint);
    }

    /**
     * Set service key
     * @param string
     * */
    public function setServiceKey($service_key)
    {
        $this->connection->setServiceKey($service_key);
    }

    /**
     * Set plugin data
     * @param string
     * @param string
     * */
    public function setPluginData($name, $version)
    {
        $this->connection->setClientUserAgentWithPluginData($name, $version);
    }

    /**
     * Disable SSL Check ~ Use only for testing!
     * @param bool $disable
     * */
    public function disableSSLCheck($disable = false)
    {
        $this->connection->setSSLCheck(!$disable);
    }

    /**
     * Set timeout
     * @param int $timeout
     * */
    public function setTimeout($timeout = 3)
    {
        $this->connection->setTimeout($timeout);
    }

    /**
     * Create Worldpay APM order
     * @param array $order
     * @return array Worldpay order response
     * */
    public function createApmOrder($order = array())
    {
        $myOrder = new APMOrder($order);
        $response = OrderService::createOrder($myOrder);

        if (isset($response["orderCode"])) {
            //success
            return $response;
        } else {
            Error::throwError("apierror");
        }
    }


    /**
     * Create Worldpay order
     * @param array $order
     * @param boolean $debug
     * @return array Worldpay order response
     * */
    public function createOrder($order = array(), $debug=false)
    {
        
        $myOrder = new Order($order);
        $response = OrderService::createOrder($myOrder, $debug);

        if (isset($response["orderCode"])) {
            //success
            return $response;
        } else {
            Error::throwError("apierror");
        }
    }

    /**
     * Authorize Worldpay 3DS Order
     * @param string $orderCode
     * @param string $responseCode
     * */
    public function authorize3DSOrder($orderCode, $responseCode)
    {
        if (empty($orderCode) || !is_string($orderCode)) {
            Error::throwError('ip', Error::$errors['3ds']['ordercode']);
        }

        return OrderService::authorize3DSOrder($orderCode, $responseCode);
    }

    /**
     * Capture Authorized Worldpay Order
     * @param string $orderCode
     * @param string $amount
     * */
    public function captureAuthorizedOrder($orderCode = false, $amount = null)
    {
        if (empty($orderCode) || !is_string($orderCode)) {
            Error::throwError('ip', Error::$errors['capture']['ordercode']);
        }

        OrderService::captureAuthorizedOrder($orderCode, $amount);
    }

    /**
     * Cancel Authorized Worldpay Order
     * @param string $orderCode
     * */
    public function cancelAuthorizedOrder($orderCode = false)
    {
        if (empty($orderCode) || !is_string($orderCode)) {
            Error::throwError('ip', Error::$errors['capture']['ordercode']);
        }
        OrderService::cancelAuthorizedOrder($orderCode);
    }

    /**
     * Refund Worldpay order
     * @param bool $orderCode
     * @param null $amount
     */
    public function refundOrder($orderCode = false, $amount = null)
    {
        if (empty($orderCode) || !is_string($orderCode)) {
            Error::throwError('ip', Error::$errors['refund']['ordercode']);
        }
        OrderService::refundOrder($orderCode, $amount);
    }

    /**
     * Get a Worldpay order
     * @param string $orderCode
     * @return array Worldpay order response
     * */
    public function getOrder($orderCode = false)
    {
        if (empty($orderCode) || !is_string($orderCode)) {
            Error::throwError('ip', Error::$errors['orderInput']['orderCode']);
        }
        $response = OrderService::getOrder($orderCode);

        if (!isset($response["orderCode"])) {
            Error::throwError("apierror");
        }
        return $response;
    }

    /**
     * Get card details from Worldpay token
     * @param string $token
     * @return array card details
     * */
    public function getStoredCardDetails($token = false)
    {
        if (empty($token) || !is_string($token)) {
            Error::throwError('ip', Error::$errors['orderInput']['token']);
        }

        $response = TokenService::getStoredCardDetails($token);

        if (!isset($response['paymentMethod'])) {
            Error::throwError("apierror");
        }

        return $response['paymentMethod'];
    }

}
