
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

include('header.php');

try {
    $response = $worldpay->authorise3DSOrder($_SESSION['orderCode'], $_POST['PaRes']);
    
    if (isset($response['paymentStatus']) && $response['paymentStatus'] == 'SUCCESS') {
        echo 'Order Code: ' . $_SESSION['orderCode'] . ' has been authorised <br/>';
    } else {
        echo 'There was a problem authorising 3DS order <br/>';
    }
} catch (WorldpayException $e) { // PHP 5.3+
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/> 
    HTTP status code:' . $e->getHttpStatusCode() . '<br/> 
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage() . ' <br/>' .
    'PaRes: ' . print_r($_POST, true) . '<br/>';
} catch (Exception $e) {  // PHP 5.2 
    echo 'Error message: '. $e->getMessage();
}
