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

include('header.php');

try {
    $response = $worldpay->authorize3DSOrder($_SESSION['orderCode'], $_POST['PaRes']);

    if (isset($response['paymentStatus']) && ($response['paymentStatus'] == 'SUCCESS' ||  $response['paymentStatus'] == 'AUTHORIZED')) {
        echo 'Order Code: ' . $_SESSION['orderCode'] . ' has been authorized <br/>';
    } else {
        var_dump($response);
        echo 'There was a problem authorizing 3DS order <br/>';
    }
} catch (WorldpayException $e) {
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/>
    HTTP status code:' . $e->getHttpStatusCode() . '<br/>
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage() . ' <br/>' .
    'PaRes: ' . print_r($_POST, true) . '<br/>';
}
