<?php
namespace Worldpay;

class APMOrder extends AbstractOrder
{
    protected $successUrl;
    protected $pendingUrl;
    protected $failureUrl;
    protected $cancelUrl;

    public function __construct($order)
    {
        Order::validateInputData($order);

        $order = array_merge(self::getOrderDefaults(), $order);

        $this->orderDescription = $order['orderDescription'];
        $this->amount = $order['amount'];
        $this->currencyCode = $order['currencyCode'];
        $this->name = $order['name'];
        $this->shopperEmailAddress = $order['shopperEmailAddress'];
        $this->billingAddress = new BillingAddress($order['billingAddress']);
        $this->deliveryAddress = new DeliveryAddress($order['deliveryAddress']);
        $this->customerOrderCode = $order['customerOrderCode'];
        $this->successUrl = $order['successUrl'];
        $this->pendingUrl = $order['pendingUrl'];
        $this->failureUrl = $order['failureUrl'];
        $this->cancelUrl = $order['cancelUrl'];
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
            $_apmName = isset($_orderPM['apmName']) ? $_orderPM['apmName'] : "";
            $_shopperCountryCode = isset($_orderPM['shopperCountryCode']) ? $_orderPM['shopperCountryCode'] : "";
            $_apmFields = isset($_orderPM['apmFields']) ? $_orderPM['apmFields'] : new stdClass();

            $paymentMethod = array(
                  "type" => "APM",
                  "apmName" => $_apmName,
                  "shopperCountryCode" => $_shopperCountryCode,
                  "apmFields" => $_apmFields
            );
        }
        return $paymentMethod;
    }

    private static function getOrderDefaults()
    {
        $defaults = array(
            'name' => null,
            'amount' => null,
            'reusable' => false,
            'orderDescription' => null,
            'shopperLanguageCode' => "",
            'customerOrderCode' => null,
            'currencyCode' => null,
            'deliveryAddress' => null,
            'billingAddress' => null,
            'successUrl' => null,
            'pendingUrl' => null,
            'failureUrl' => null,
            'cancelUrl' => null,
            'shopperEmailAddress' => null,
            'orderCodePrefix' => null,
            'orderCodeSuffix' => null,
            'paymentMethod' => null
        );

        return $defaults;
    }
}
