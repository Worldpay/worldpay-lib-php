<?php
namespace Worldpay;

class Error {

     public static $errors = array(
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
            'orderCode'         => 'No order_code entered',
            'orderDescription'  => 'No order_description found',
            'amount'            => 'No amount found, or it is not a whole number',
            'currencyCode'      => 'No currency_code found',
            'name'              => 'No name found',
            'billingAddress'    => 'Invalid billing_address',
            'deliveryAddress'    => 'Invalid delivery_address'
        ),
        'notificationPost'      => 'Notification Error: Not a post',
        'notificationUnknown'   => 'Notification Error: Cannot be processed',
        'refund'    =>  array(
            'ordercode'         => 'No order code entered'
        ),
        'capture'    =>  array(
            'ordercode'         => 'No order code entered'
        ),
        '3ds'    =>  array(
            'ordercode'         => 'No order code entered'
        ),
        'json'      => 'JSON could not be decoded',
        'key'       => 'Please enter your service key',
        'sslerror'  => 'Worldpay SSL certificate could not be validated',
        'timeouterror'=> 'Gateway timeout - possible order failure. 
            Please review the order in the portal to confirm success.'
    );

    /**
     * Handle errors
     * @param string-error_key $error
     * @param string $message
     * @param string $code
     * @param string $httpStatusCode
     * @param string $description
     * @param string $customCode
     * */
    public static function throwError(
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
        throw new WorldpayException(
            $error_message,
            $code,
            null,
            $httpStatusCode,
            $description,
            $customCode
        );
    }

}
