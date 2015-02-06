
<?php

/**
 * PHP library version: 1.3
 */
require_once('../lib/worldpay.php');

// Initialise Worldpay class with your SERVICE KEY
$worldpay = new Worldpay("your-service-key");

// Sometimes your SSL doesnt validate locally
// DONT USE IN PRODUCTION
$worldpay->disableSSLCheck(true);

try {
    $response = $worldpay->authorise3DSOrder($_SESSION['orderCode'], $_POST['PaRes']);
    
    if (isset($response['paymentStatus']) && $response['paymentStatus'] == 'SUCCESS') {
        echo 'Order Code: ' . $_SESSION['orderCode'] . ' has been authorised <br/>';
    } else {
        echo 'There was a problem authorising 3DS order <br/>';
    }
} catch (WorldpayException $e) { // PHP 5.2 - Change to Exception, only $e->getMessage() is available
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/> 
    HTTP status code:' . $e->getHttpStatusCode() . '<br/> 
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage() . ' <br/>' .
    'PaRes: ' . print_r($_POST, true) . '<br/>';
}
