# Worldpay PHP Library 2.1.2

#### Issues
Please see our [support contact information]( https://developer.worldpay.com/jsonapi/faq/articles/how-can-i-contact-you-for-support) to raise an issue.

#### Installation

##### Composer
Add to your composer.json:
```javascript
"worldpay/worldpay-lib-php": "~2.1.2"
```

Or run:
```
composer require worldpay/worldpay-lib-php
```
##### Manual

```php
require_once('worldpay-php/init.php');
```

#### Documentation
https://online.worldpay.com/docs

#### API Reference
https://online.worldpay.com/api-reference

### Examples
The examples require editing to support PHP 5.3.

#### index.php
Uses WorldpayJS to generate a token that is posted to create_order.php.
**Change your client key**

#### create_order.php
Creates a Worldpay order with a posted token.
**Change your service key*

#### 3ds_redirect.php
Authorizes a 3DS order
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

PHP 5.3+
Curl
