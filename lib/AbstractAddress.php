<?php
namespace Worldpay;

abstract class AbstractAddress
{
    protected $address1;
    protected $address2;
    protected $address3;
    protected $postalCode;
    protected $city;
    protected $state;
    protected $countryCode;

    public function toArray()
    {
        return get_object_vars($this);
    }
}
