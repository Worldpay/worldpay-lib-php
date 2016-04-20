<?php
namespace Worldpay;

class BillingAddress extends AbstractAddress
{
    public function __construct($address)
    {
        if (!isset($address)) {
            $address = array();
        }

        $address = array_merge(self::getAddressDefaults(), $address);
        $this->address1 = $address['address1'];
        $this->address2 = $address['address2'];
        $this->address3 = $address['address3'];
        $this->postalCode = $address['postalCode'];
        $this->city = $address['city'];
        $this->state = $address['state'];
        $this->countryCode = $address['countryCode'];

        if (!empty($address['telephoneNumber'])) {
            $this->telephoneNumber = $address['telephoneNumber'];
        }

    }

    private static function getAddressDefaults()
    {
        $defaults = array(
            'address1' => null,
            'address2' => null,
            'address3' => null,
            'postalCode' => null,
            'city' => null,
            'state' => null,
            'countryCode' => null
        );

        return $defaults;
    }
}
