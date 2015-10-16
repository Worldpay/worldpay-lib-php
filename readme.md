# Worldpay PHP Library v1.7

#### Documentation
https://online.worldpay.com/docs

#### API Reference
https://online.worldpay.com/api-reference

### Examples
The examples require editing to support PHP 5.2, please see the note at the end of this readme.  

#### index.php
Uses WorldpayJS to generate a token that is posted to create_order.php.  
**Change your client key**

#### create_order.php
Creates a Worldpay order with a posted token.  
**Change your service key*

#### 3ds_redirect.php
Authorises a 3DS order
**Change your service key*

#### refund.php
Enter your Worldpay order code and posts it to refund_order.php  

#### refund_order.php
Refunds a Worldpay order with a posted order code   
**Change your service key**  

#### partial_refund.php
Enter your Worldpay order code and amount to refund and posts it to partial_refund_order.php  

#### partial_refund_order.php
Refunds a Worldpay order with a posted order code   
**Change your service key**  

#### stored_cards.php
Enter your Worldpay reusable token and posts it to stored_cards.php  

#### get_stored_cards.php
Shows stored card details from posted token  
**Change your service key* 

### Requirements

PHP 5.2+  
Curl

### PHP 5.2
PHP 5.2 and below do not support extended exceptions. The library will return a standard exception in these versions. This means you will need to change the try catch, to catch on 'Exception' instead of 'WorldpayException'. The methods 'getHttpStatusCode', 'getCustomCode' and 'getDescription' will also not be available.  

If you are using the examples, you will need to edit them, please see the in file comments referencing PHP 5.2.
