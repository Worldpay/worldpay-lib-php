<?php

/**
 * PHP library version: 1.3
 */

final class Worldpay
{

    /**
     * Library variables
     * */

    private $service_key = "";
    private $timeout = 10;
    private $disable_ssl = false;
    private $endpoint = 'https://api.worldpay.com/v1/';
    private static $use_external_JSON = false;

    private static $errors = array(
        "ip"        => "Invalid parameters",
        "cine"      => "php_curl was not found",
        "to"        => "Request timed out",
        "nf"        => "Not found",
        "apierror"  => "API Error",
        "uanv"      => "Worldpay is currently unavailable, please try again later",
        "contact"   => "Error contacting Worldpay, please try again later",
        'ssl'       => 'You must enable SSL check in production mode',
        'verify'    => 'Worldpay not verifiying SSL connection',
        'orderInput'=> array(
            'token'             => 'No token found',
            'orderDescription'  => 'No order_description found',
            'amount'            => 'No amount found, or it is not a whole number',
            'currencyCode'      => 'No currency_code found',
            'name'              => 'No name found',
            'billingAddress'    => 'No billing_address found'
        ),
        'notificationPost'      => 'Notification Error: Not a post',
        'notificationUnknown'   => 'Notification Error: Cannot be processed',
        'refund'    =>  array(
            'ordercode'         => 'No order code entered'
        ),
        'json'      => 'JSON could not be decoded',
        'key'       => 'Please enter your service key',
        'sslerror'  => 'Worldpay SSL certificate could not be validated'
    );

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
            self::onError("key");
        }
        $this->service_key = $service_key;

        if ($timeout !== false) {
            $this->timeout = $timeout;
        }

        if (!function_exists("curl_init")) {
            self::onError("cine");
        }

        //
    }

    /**
     * Gets the client IP by checking $_SERVER
     * @return string
     * */
    private function getClientIp()
    {
        $ipaddress = '';
        
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
    /**
     * Checks if variable is a float
     * @param float $number
     * @return bool
     * */
    private function isFloat($number)
    {
        return !!strpos($number, '.');
    }

    /**
     * Checks order input array for validity
     * @param array $order
     * */
    private function checkOrderInput($order)
    {
        $errors = array();
        if (empty($order) || !is_array($order)) {
            self::onError('ip');
        }
        if (!isset($order['token'])) {
            $errors[] = self::$errors['orderInput']['token'];
        }
        if (!isset($order['orderDescription'])) {
            $errors[] = self::$errors['orderInput']['orderDescription'];
        }
        if (!isset($order['amount']) || !($order['amount'] > 0) || $this->isFloat($order['amount'])) {
            $errors[] = self::$errors['orderInput']['amount'];
        }
        if (!isset($order['currencyCode'])) {
            $errors[] = self::$errors['orderInput']['currencyCode'];
        }
        if (!isset($order['name'])) {
            $errors[] = self::$errors['orderInput']['name'];
        }
        if (!isset($order['billingAddress'])) {
            $errors[] = self::$errors['orderInput']['billingAddress'];
        }

        if (count($errors) > 0) {
            self::onError('ip', implode(', ', $errors));
        }
    }

    /**
     * Sends request to Worldpay API
     * @param string $action
     * @param string $json
     * @param bool $expectResponse
     * @param string $method
     * @return string JSON string from Worldpay
     * */
    private function sendRequest($action, $json = false, $expectResponse = false, $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint.$action);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        $arch = 'x86';
        switch(PHP_INT_SIZE) {
            case 4:
                $arch = 'x86';
                break;
            case 8:
                $arch = 'x64';
                break;
            default:
                $arch = 'x64';
        }

        $clientUserAgent = 'os.name=' . php_uname('s') . ';os.version=' . php_uname('r') . ';os.arch=' .
        $arch . ';lang.version='. phpversion() . ';lib.version=1.3;' .
        'api.version=v1;lang=php;owner=worldpay';

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: $this->service_key",
                "Content-Type: application/json",
                "X-wp-client-user-agent: $clientUserAgent",
                "Content-Length: " . strlen($json)
            )
        );
        // Disabling SSL used for localhost testing
        if ($this->disable_ssl === true) {
            if (substr($this->service_key, 0, 1) != 'T') {
                self::onError('ssl');
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        // Curl error
        if ($result === false) {
            if ($errno === 60) {
                self::onError('sslerror', false, $errno, null, $err);
            } else {
                self::onError('uanv', false, $errno, null, $err);
            }
        }

        if (substr($result, -1) != '}') {
            $result = substr($result, 0, -1);
        }

        // Decode JSON
        $response = self::handleResponse($result);

        // Check JSON has decoded correctly
        if ($expectResponse && ($response === null || $response === false )) {
            self::onError('uanv', self::$errors['json'], 503);
        }
        
        // Check the status code exists
        if (isset($response["httpStatusCode"])) {

            if ($response["httpStatusCode"] != 200) {
                self::onError(
                    false,
                    $response["message"],
                    $info['http_code'],
                    $response['httpStatusCode'],
                    $response['description'],
                    $response['customCode']
                );

            }

        } elseif ($expectResponse && $info['http_code'] != 200) {
            // If we expect a result and we have an error
            self::onError('uanv', self::$errors['json'], 503);
            
        } elseif (!$expectResponse) {

            if ($info['http_code'] != 200) {
                self::onError('apierror', $result, $info['http_code']);
            } else {
                $response = true;
            }
        }

        return $response;
    }


    /**
     * Create Worldpay order
     * @param array $order
     * @return array Worldpay order response
     * */
    public function createOrder($order = array())
    {

        $this->checkOrderInput($order);

        $defaults = array(
            'orderType' => 'ECOM',
            'customerIdentifiers' => null,
            'billingAddress' => null,
            'is3DSOrder' => false,
            'redirectURL' => false
        );

        $order = array_merge($defaults, $order);
        
        $obj = array(
            "token" => $order['token'],
            "orderDescription" => $order['orderDescription'],
            "amount" => $order['amount'],
            "is3DSOrder" => ($order['is3DSOrder']) ? true : false,
            "currencyCode" => $order['currencyCode'],
            "name" => $order['name'],
            "orderType" => $order['orderType'],
            "billingAddress" => $order['billingAddress'],
            "customerOrderCode" => $order['customerOrderCode'],
            "customerIdentifiers" => $order['customerIdentifiers']
        );
        
        if ($obj['is3DSOrder']) {
            $_SESSION['worldpay_sessionid'] = uniqid();
            $obj['shopperIpAddress'] = $this->getClientIp();
            $obj['shopperSessionId'] = $_SESSION['worldpay_sessionid'];
            $obj['shopperUserAgent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            $obj['shopperAcceptHeader'] = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
        }

        $json = json_encode($obj);
        
        $response = $this->sendRequest('orders', $json, true);

        if (isset($response["orderCode"])) {
            //success
            return $response;
        } else {
            self::onError("apierror");
        }
    }
    
    /**
     * Authorise Worldpay 3DS Order
     * @param string $orderCode
     * @param string $responseCode
     * */
    public function authorise3DSOrder($orderCode, $responseCode)
    {
        $json = json_encode(array(
            "threeDSResponseCode" => $responseCode,
            "shopperSessionId" => $_SESSION['worldpay_sessionid'],
            "shopperAcceptHeader" => $_SERVER['HTTP_ACCEPT'],
            "shopperUserAgent" => $_SERVER['HTTP_USER_AGENT'],
            "shopperIpAddress" => $this->getClientIp()
        ));
        return $this->sendRequest('orders/' . $orderCode, $json, true, 'PUT');
    }

    /**
     * Refund Worldpay order
     * @param bool $orderCode
     * @param null $amount
     */
    public function refundOrder($orderCode = false, $amount = null)
    {
        if (empty($orderCode) || !is_string($orderCode)) {
            self::onError('ip', self::$errors['refund']['ordercode']);
        }

        if (!empty($amount) && is_int($amount)) {
            $json = json_encode(array('refundAmount'=>"{$amount}"));
        } else {
            $json = false;
        }

        $this->sendRequest('orders/' . $orderCode . '/refund', $json, !!$json);
    }

    /**
     * Get card details from Worldpay token 
     * @param string $token
     * @return array card details
     * */
    public function getStoredCardDetails($token = false)
    {
        if (empty($token) || !is_string($token)) {
            self::onError('ip', self::$errors['orderInput']['token']);
        }
        $response = $this->sendRequest('tokens/' . $token, false, true, 'GET');

        if (!isset($response['paymentMethod'])) {
            self::onError("apierror");
        }
        
        return $response['paymentMethod'];
    }

    /**
     * Disable SSL Check ~ Use only for testing!
     * @param bool $disable
     * */
    public function disableSSLCheck($disable = false)
    {
        $this->disable_ssl = $disable;
    }


    /**
     * Set timeout
     * @param int $timeout
     * */
    public function setTimeout($timeout = 3)
    {
        $this->timeout = $timeout;
    }

    /**
     * Handle errors
     * @param string-error_key $error
     * @param string $message
     * @param string $code
     * @param string $httpStatusCode
     * @param string $description
     * @param string $customCode
     * */
    public static function onError(
        $error = false,
        $message = false,
        $code = null,
        $httpStatusCode = null,
        $description = null,
        $customCode = null
    ) {

        $error_message = ($message) ? $message : '';
        if ($error) {
            $error_message = self::$errors[$error];
            if ($message) {
                $error_message .=  ' - '. $message;
            }
        }

        if (version_compare(phpversion(), '5.3.0', '>=')) {
            throw new WorldpayException(
                $error_message,
                $code,
                null,
                $httpStatusCode,
                $description,
                $customCode
            );
        } else {
            throw new Exception(
                $error_message,
                $code
            );
        }
    }

    /**
     * Use external library to do JSON decode
     * @param bool $external
     * */
    public function setExternalJSONDecode($external = false)
    {
        self::$use_external_JSON = $external;
    }

    /**
     * Handle response object
     * @param string $response
     * */
    public static function handleResponse($response)
    {
        // Backward compatiblity for JSON
        if (!function_exists('json_encode') || !function_exists('json_decode') || self::$use_external_JSON) {
            require_once('JSON.php');
        }
        if (self::$use_external_JSON) {
            return json_decode_external($response, true);
        } else {
            return json_decode($response, true);
        }
    }
}

class WorldpayException extends Exception
{
    private $httpStatusCode;
    private $description;
    private $customCode;


    public function __construct(
        $message = null,
        $code = 0,
        Exception $previous = null,
        $httpStatusCode = null,
        $description = null,
        $customCode = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->httpStatusCode = $httpStatusCode;
        $this->description = $description;
        $this->customCode = $customCode;
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function getCustomCode()
    {
        return $this->customCode;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
