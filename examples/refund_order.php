<?php
namespace Worldpay;
?>

<?php
/**
 * PHP library version: 2.1.0
 */
require_once('../init.php');

// Initialise Worldpay class with your SERVICE KEY
$worldpay = new Worldpay("your-service-key");

// Sometimes your SSL doesnt validate locally
// DONT USE IN PRODUCTION
$worldpay->disableSSLCheck(true);

$worldpayOrderCode = $_POST['orderCode'];

include('header.php');

// Try catch
try {
    // Refund the order using the Worldpay order code
    $worldpay->refundOrder($worldpayOrderCode);
    echo 'Order <span id="order-code">'.$worldpayOrderCode.'</span> has been refunded!';
} catch (WorldpayException $e) {
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/>
    HTTP status code:' . $e->getHttpStatusCode() . '<br/>
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage();
}
