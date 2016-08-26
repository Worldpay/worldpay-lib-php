<?php
namespace Worldpay;

class OrderService
{
    public static function createOrder($order)
    {
        return Connection::getInstance()->sendRequest('orders', json_encode($order->toArray()), true);
    }

    public static function authorize3DSOrder($orderCode, $responseCode)
    {
        $obj = array_merge(array("threeDSResponseCode" => $responseCode), Utils::getThreeDSShopperObject());
        $json = json_encode($obj);
        return Connection::getInstance()->sendRequest('orders/' . $orderCode, $json, true, 'PUT');
    }

    public static function captureAuthorizedOrder($orderCode, $amount)
    {
        if (!empty($amount) && is_numeric($amount)) {
            $json = json_encode(array('captureAmount'=>"{$amount}"));
        } else {
            $json = false;
        }
        return Connection::getInstance()->sendRequest('orders/' . $orderCode . '/capture', $json, !!$json);
    }

    public static function cancelAuthorizedOrder($orderCode)
    {
        return Connection::getInstance()->sendRequest('orders/' . $orderCode, false, false, 'DELETE');
    }

    public static function getOrder($orderCode)
    {
        return Connection::getInstance()->sendRequest('orders/' . $orderCode, false, true, 'GET');
    }

    public static function refundOrder($orderCode, $amount)
    {
        if (!empty($amount) && is_numeric($amount)) {
            $json = json_encode(array('refundAmount'=>"{$amount}"));
        } else {
            $json = false;
        }
        return Connection::getInstance()->sendRequest('orders/' . $orderCode . '/refund', $json, false);
    }
}
