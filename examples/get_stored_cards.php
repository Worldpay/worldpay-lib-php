
<?php
/**
 * PHP library version: v1.5
 */
require_once('../lib/worldpay.php');

// Initialise Worldpay class with your SERVICE KEY
$worldpay = new Worldpay("your-service-key");


// Sometimes your SSL doesnt validate locally
// DONT USE IN PRODUCTION
$worldpay->disableSSLCheck(true);

$token = $_POST['token'];

include('header.php');

try {
    $cardDetails = $worldpay->getStoredCardDetails($token);
    echo '<p>Name: <span id="name">' . $cardDetails['name'] . '</span></p>';
    echo '<p>Expiry Month: <span id="expiration-month">' . $cardDetails['expiryMonth'] . '</span></p>';
    echo '<p>Expiry Year: <span id="expiration-year">' . $cardDetails['expiryYear'] . '</span></p>';
    echo '<p>Card Type: <span id="card-type">' . $cardDetails['cardType'] . '</span></p>';
    echo '<p>Masked Card Number: <span id="masked-card-number">' . $cardDetails['maskedCardNumber'] . '</span></p>';
    echo '<pre>' . print_r($cardDetails, true). '</pre>';

} catch (WorldpayException $e) { // PHP 5.3+
    echo 'Error code: ' . $e->getCustomCode() . '<br/> 
    HTTP status code:' . $e->getHttpStatusCode() . '<br/> 
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage();
} catch (Exception $e) {  // PHP 5.2 
    echo 'Error message: '. $e->getMessage();
}
