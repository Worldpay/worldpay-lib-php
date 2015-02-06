
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

$token = $_POST['token'];
$name = $_POST['name'];
$amount = $_POST['amount'];
$_3ds = (isset($_POST['3ds'])) ? $_POST['3ds'] : false;

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
    $response = $worldpay->createOrder(array(
        'token' => $token, // The token from WorldpayJS
        'orderDescription' => $_POST['description'], // Order description of your choice
        'amount' => $amount*100, // Amount in pence
        'is3DSOrder' => $_3ds, // 3DS
        'currencyCode' => $_POST['currency'], // Currency code
        'name' => ($_3ds) ? '3D' : $name, // Customer name
        'billingAddress' => $billing_address, // Billing address array
        'customerIdentifiers' => array( // Custom indentifiers
            'my-customer-ref' => 'customer-ref'
        ),
        'customerOrderCode' => 'A123' // Order code of your choice
    ));
    
    if ($response['paymentStatus'] === 'SUCCESS') {
        // Create order was successful!
        $worldpayOrderCode = $response['orderCode'];
        echo '<p>Order Code: <span id="order-code">' . $worldpayOrderCode . '</span></p>';
        echo '<p>Token: <span id="token">' . $response['token'] . '</span></p>';
        echo '<p>Payment Status: <span id="payment-status">' . $response['paymentStatus'] . '</span></p>';
        echo '<pre>' . print_r($response, true). '</pre>';
        // TODO: Store the order code somewhere..
    } elseif ($response['is3DSOrder']) {
        // Redirect to URL
        // STORE order code in session
        $_SESSION['orderCode'] = $response['orderCode'];
        ?>
        <form id="submitForm" method="post" action="<?php echo $response['redirectURL'] ?>">
            <input type="hidden" name="PaReq" value="<?php echo $response['oneTime3DsToken']; ?>"/>
            <input type="hidden" id="termUrl" name="TermUrl" value="http://localhost/3ds_redirect.php"/>
            <script>
                document.getElementById('termUrl').value = 
                        window.location.href.replace('create_order.php', '3ds_redirect.php');
                document.getElementById('submitForm').submit();
            </script>
        </form>
        <?php
    } else {
        // Something went wrong
        echo '<p id="payment-status">' . $response['paymentStatus'] . '</p>';
        throw new WorldpayException(print_r($response, true));
    }
} catch (WorldpayException $e) { // PHP 5.2 - Change to Exception, only $e->getMessage() is available
    // Worldpay has thrown an exception
    echo 'Error code: ' . $e->getCustomCode() . '<br/> 
    HTTP status code:' . $e->getHttpStatusCode() . '<br/> 
    Error description: ' . $e->getDescription()  . ' <br/>
    Error message: ' . $e->getMessage();
}
