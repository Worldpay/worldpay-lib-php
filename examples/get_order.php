<?php
namespace Worldpay;
?>

<?php
/**
 * PHP library version: 2.0.0
 */

require_once('../init.php');

// Initialise Worldpay class with your SERVICE KEY
$worldpay = new Worldpay("your-service-key");

// Sometimes your SSL doesnt validate locally
// DONT USE IN PRODUCTION
$worldpay->disableSSLCheck(true);

$orderCode = $_POST['orderCode'];

include('header.php');

// Try catch
try {
    $response = $worldpay->getOrder($orderCode);
    echo '<pre>' . print_r($response, true). '</pre>';
} catch (WorldpayException $e) {
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/>
    HTTP status code:' . $e->getHttpStatusCode() . '<br/>
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage();
}
