
<?php
/**
 * PHP library version: v1.7
 */
require_once('../lib/worldpay.php');

// Initialise Worldpay class with your SERVICE KEY
$worldpay = new Worldpay("your-service-key");

// Sometimes your SSL doesnt validate locally
// DONT USE IN PRODUCTION
$worldpay->disableSSLCheck(true);

$token = $_POST['token'];
$name = $_POST['name'];
$amount = $_POST['amount'];

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$redirect_url = $protocol . $_SERVER['HTTP_HOST'] . "/apm";
$customerIdentifiers = (!empty($_POST['customer-identifiers'])) ? json_decode($_POST['customer-identifiers']) : array();

include('header.php');

// Try catch
try {

    // Customers billing address
    $billing_address = array(
        "address1"=> $_POST['address1'],
        "address2"=> $_POST['address2'],
        "address3"=> $_POST['address3'],
        "postalCode"=> $_POST['postcode'],
        "city"=> $_POST['city'],
        "state"=> '',
        "countryCode"=> $_POST['countryCode']
    );
    // Customers delivery address
    $delivery_address = array(
        "firstName" => $_POST['delivery-firstName'],
        "lastName" => $_POST['delivery-lastName'],
        "address1"=> $_POST['delivery-address1'],
        "address2"=> $_POST['delivery-address2'],
        "address3"=> $_POST['delivery-address3'],
        "postalCode"=> $_POST['delivery-postcode'],
        "city"=> $_POST['delivery-city'],
        "state"=> '',
        "countryCode"=> $_POST['delivery-countryCode']
    );
    $response = $worldpay->createApmOrder(array(
        'token' => $token, // The token from WorldpayJS
        'orderDescription' => $_POST['description'], // Order description of your choice
        'amount' => $amount*100, // Amount in pence
        'currencyCode' => $_POST['currency'], // Currency code
        'settlementCurrency' => $_POST['settlement-currency'], // Settlement currency code
        'name' => $name, // Customer name
        'billingAddress' => $billing_address, // Billing address array
        'deliveryAddress' => $delivery_address, // Delivery address array
        'customerIdentifiers' => (!is_null($customerIdentifiers)) ? $customerIdentifiers : array(), // Custom indentifiers
        'statementNarrative' => $_POST['statement-narrative'],
        'customerOrderCode' => 'A123', // Order code of your choice
        'successUrl' => $redirect_url . '/success.php', //Success redirect url for APM
        'pendingUrl' => $redirect_url . '/pending.php', //Pending redirect url for APM
        'failureUrl' => $redirect_url . '/error.php', //Failure redirect url for APM
        'cancelUrl' => $redirect_url . '/cancel.php' //Cancel redirect url for APM
    ));

    if ($response['paymentStatus'] === 'PRE_AUTHORIZED') {
         // Redirect to URL
        $_SESSION['orderCode'] = $response['orderCode'];
        ?>
            <script>
                window.location.replace("<?php echo $response['redirectURL'] ?>");
            </script>
        <?php

        // TODO: Store the order code somewhere..
    } else {
        // Something went wrong
        echo '<p id="payment-status">' . $response['paymentStatus'] . '</p>';
        throw new WorldpayException(print_r($response, true));
    }
} catch (WorldpayException $e) { // PHP 5.3+
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/>
    HTTP status code:' . $e->getHttpStatusCode() . '<br/>
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage();
} catch (Exception $e) {  // PHP 5.2
    echo 'Error message: '. $e->getMessage();
}
