<?php
namespace Worldpay;

define("ORDER_TYPES", serialize(array("ECOM", "MOTO", "RECURRING")));

class Order extends AbstractOrder
{
    protected $orderType;
    protected $is3DSOrder;
    protected $authorizeOnly;
    protected $redirectURL;

    public function __construct($order)
    {
        Order::validateInputData($order);

        $order = array_merge(self::getOrderDefaults(), $order);

        $this->orderType = in_array($order['orderType'], unserialize(ORDER_TYPES)) ? $order['orderType'] : 'ECOM';
        $this->redirectURL = false;
        $this->orderDescription = $order['orderDescription'];
        $this->amount = $order['amount'];
        $this->is3DSOrder = $order['is3DSOrder'] ? true : false;
        $this->currencyCode = $order['currencyCode'];
        $this->name = $order['name'];
        $this->shopperEmailAddress = $order['shopperEmailAddress'];
        $this->authorizeOnly = isset($order['authorizeOnly']) && $order['authorizeOnly']
                            || isset($order['authoriseOnly']) && $order['authoriseOnly']
                            ? true : false;
        $this->billingAddress = new BillingAddress($order['billingAddress']);
        $this->deliveryAddress = new DeliveryAddress($order['deliveryAddress']);
        $this->customerOrderCode = $order['customerOrderCode'];
        $this->orderCodePrefix = $order['orderCodePrefix'];
        $this->orderCodeSuffix = $order['orderCodeSuffix'];
        $this->paymentMethod = self::extractPaymentMethodFromData($order);

        if ($this->isDirectOrder()) {
            $this->reusable = $order['reusable'];
            $this->shopperLanguageCode = $order['shopperLanguageCode'];
        } else {
            $this->token = $order['token'];
            unset($this->paymentMethod);
        }

        if ($this->is3DSOrder) {
            $_SESSION['worldpay_sessionid'] = $order['shopperSessionId'];
            $threeDSShopper = Utils::getThreeDSShopperObject();
            $this->shopperIpAddress = $threeDSShopper['shopperIpAddress'];
            $this->shopperSessionId = $threeDSShopper['shopperSessionId'];
            $this->shopperUserAgent = $threeDSShopper['shopperUserAgent'];
            $this->shopperAcceptHeader = $threeDSShopper['shopperAcceptHeader'];
        }

        if (isset($order['shopperIpAddress'])) {
            $this->shopperIpAddress = $order['shopperIpAddress'];
        }
        if (isset($order['shopperSessionId'])) {
            $this->shopperSessionId = $order['shopperSessionId'];
        }
        if (isset($order['shopperUserAgent'])) {
            $this->shopperUserAgent = $order['shopperUserAgent'];
        }
        if (isset($order['shopperAcceptHeader'])) {
            $this->shopperAcceptHeader = $order['shopperAcceptHeader'];
        }
        if (isset($order['siteCode'])) {
            $this->siteCode = $order['siteCode'];
        }
        if (isset($order['statementNarrative'])) {
            $this->statementNarrative = $order['statementNarrative'];
        }
        if (!empty($order['settlementCurrency'])) {
            $this->settlementCurrency = $order['settlementCurrency'];
        }
        if (!empty($order['customerIdentifiers'])) {
            $this->customerIdentifiers = $order['customerIdentifiers'];
        }
    }

    private static function extractPaymentMethodFromData($data)
    {
        $paymentMethod = array();
        if (isset($data['paymentMethod'])) {
            $_orderPM = $data['paymentMethod'];
            $_name = isset($_orderPM['name']) ? $_orderPM['name'] : "";
            $_expiryMonth = isset($_orderPM['expiryMonth']) ? $_orderPM['expiryMonth'] : "";
            $_expiryYear = isset($_orderPM['expiryYear']) ? $_orderPM['expiryYear'] : "";
            $_cardNumber = isset($_orderPM['cardNumber']) ? $_orderPM['cardNumber'] : "";
            $_cvc = isset($_orderPM['cvc']) ? $_orderPM['cvc'] : "";

            $paymentMethod = array(
                  "type" => "Card",
                  "name" => $_name,
                  "expiryMonth" => $_expiryMonth,
                  "expiryYear" => $_expiryYear,
                  "cardNumber"=> $_cardNumber,
                  "cvc"=> $_cvc,
            );
        }
        return $paymentMethod;
    }

    private static function getOrderDefaults()
    {
        $defaults = array(
            'orderType' => 'ECOM',
            'name' => null,
            'billingAddress' => null,
            'reusable' => false,
            'shopperLanguageCode' => "",
            'deliveryAddress' => null,
            'isDirectOrder' => false,
            'is3DSOrder' => false,
            'authorizeOnly' => false,
            'redirectURL' => false,
            'currencyCode' => null,
            'shopperEmailAddress' => null,
            'orderCodePrefix' => null,
            'orderCodeSuffix' => null,
            'customerOrderCode' => null,
            'paymentMethod' => null
        );
        return $defaults;
    }
}
