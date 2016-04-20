<?php
namespace Worldpay;

class WorldpayException extends \Exception
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
