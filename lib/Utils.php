<?php
namespace Worldpay;

class Utils {

    private static $threeDSShopperObject;

    /**
     * Checks if variable is a float
     * @param float $number
     * @return bool
     * */
    public static function isFloat($number)
    {
        return !!strpos($number, '.');
    }


    /**
     * Gets the client IP by checking $_SERVER
     * @return string
     * */
    public static function getClientIp()
    {
        $ipaddress = '';

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ipaddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
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
        return trim(explode(",", $ipaddress)[0]);
    }

    public static function setThreeDSShopperObject($threeDSShopperObject)
    {
        static::$threeDSShopperObject = $threeDSShopperObject;
    }

    public static function getThreeDSShopperObject()
    {
        if (!empty(static::$threeDSShopperObject)) {
            return static::$threeDSShopperObject;
        }

        return array('shopperIpAddress' => Utils::getClientIp(),
                     'shopperSessionId' => $_SESSION['worldpay_sessionid'],
                     'shopperUserAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                     'shopperAcceptHeader' => isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '*/*'
        );
    }

}
