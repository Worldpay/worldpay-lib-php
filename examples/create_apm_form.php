
 <?php include("header.php"); ?>
        <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
        <h1>PHP Library Create APM Order Example</h1>
        <form method="post" action="create_apm_order.php" id="my-payment-form">
            <div class="payment-errors"></div>
            <div class="header">Checkout</div>

            <div class="form-row">
                <label>
                    APM
                </label>
                <select id="apm-name" data-worldpay="apm-name">
                    <option value="paypal" selected="selected">PayPal</option>
                    <option value="giropay">Giropay</option>
                </select>
            </div>

            <div class="form-row">
                <label>
                    Name
                </label>
                <input type="text" id="name" name="name" value="Example Name" />
            </div>

            <div class="form-row">
                <label>
                    Amount
                </label>
                <input type="text" id="amount" size="4" name="amount" value="15.23" />
            </div>

            <div class="form-row">
                <label>
                    Currency
                </label>
                <input type="text" id="currency" name="currency" value="GBP" />
            </div>

            <div class="form-row">
                <label>Settlement Currency</label>
                 <select id="order-type" name="settlement-currency">
                    <option value="" selected></option>
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                    <option value="EUR">EUR</option>
                    <option value="CAD">CAD</option>
                    <option value="NOK">NOK</option>
                    <option value="SEK">SEK</option>
                    <option value="SGD">SGD</option>
                    <option value="HKD">HKD</option>
                    <option value="DKK">DKK</option>
                </select>
            </div>

            <div class="form-row reusable-token-row">
                <label>Reusable Token</label>
                <input type="checkbox" id="chkReusable" />
            </div>

            <div class="header">Billing address</div>

            <div class="form-row">
                <label>
                    Address 1
                </label>
                <input type="text" id="address1" name="address1" value="123 House Road" />
            </div>

            <div class="form-row">
                <label>
                    Address 2
                </label>
                <input type="text" id="address2" name="address2" value="A village" />
            </div>

            <div class="form-row">
                <label>
                    Address 3
                </label>
                <input type="text" id="address3" name="address3" value="" />
            </div>

            <div class="form-row">
                <label>
                    City
                </label>
                <input type="text" id="city" name="city" value="London" />
            </div>

            <div class="form-row">
                <label>
                    Postcode
                </label>
                <input type="text" id="postcode" name="postcode" value="EC1 1AA" />
            </div>

            <div class="form-row">
                <label>
                    Country Code
                </label>
                <input type="text" id="country-code" name="countryCode" data-worldpay="country-code" value="GB" />
            </div>

            <div class="header">Delivery address</div>
            <div class="form-row">
                <label>
                    First Name
                </label>
                <input type="text" id="delivery-first-name" name="delivery-firstName" value="John" />
            </div>
            <div class="form-row">
                <label>
                    Last Name
                </label>
                <input type="text" id="delivery-last-name" name="delivery-lastName" value="Doe" />
            </div>
            <div class="form-row">
                <label>
                    Address 1
                </label>
                <input type="text" id="delivery-address1" name="delivery-address1" value="123 House Road" />
            </div>

            <div class="form-row">
                <label>
                    Address 2
                </label>
                <input type="text" id="delivery-address2" name="delivery-address2" value="A village" />
            </div>

            <div class="form-row">
                <label>
                    Address 3
                </label>
                <input type="text" id="delivery-address3" name="delivery-address3" value="" />
            </div>

            <div class="form-row">
                <label>
                    City
                </label>
                <input type="text" id="delivery-city" name="delivery-city" value="London" />
            </div>

            <div class="form-row">
                <label>
                    Postcode
                </label>
                <input type="text" id="delivery-postcode" name="delivery-postcode" value="EC1 1AA" />
            </div>

            <div class="form-row">
                <label>
                    Country Code
                </label>
                <input type="text" id="delivery-country-code" name="delivery-countryCode" value="GB" />
            </div>

            <div class="header">Other</div>

            <div class="form-row">
                <label>
                    Order Description
                </label>
                <input type="text" id="description" name="description" value="My test order" />
            </div>

            <div class="form-row">
                <label>
                    Statement Narrative
                </label>
                <input type="text" id="statement-narrative" maxlength="24" name="statement-narrative" value="Statement Narrative" />
            </div>

            <div class="form-row language-code-row">
                <label>Shopper Language Code</label>
                <input type="text" id="language-code" maxlength="2" data-worldpay="language-code" value="EN" />
            </div>

            <div class="form-row swift-code-row" style="display:none">
                <label>
                    Swift Code
                </label>
                <input type="text" id="swift-code" value="NWBKGB21" />
            </div>

            <div class="form-row large">
                <label class='left'>
                    Customer Identifiers (json)
                </label>
                <textarea id="customer-identifiers" rows="6" cols="30" name="customer-identifiers"></textarea>
            </div>

            <input type="submit" id="place-order" value="Place Order" />

            <div class="token"></div>
            <div class="apmName"></div>

        </form>

<small>
    
</small>

    <script type="text/javascript">
        if (!window['Worldpay']) {
            document.getElementById('place-order').disabled = true;
        }
        else {
            // Set client key
            Worldpay.tokenType = 'apm';
            Worldpay.setClientKey("your-client-key");
            // Get form element
            var form = $('#my-payment-form')[0];
            Worldpay.useForm(form, function (status, response) {
                if (response.error) {
                    Worldpay.handleError(form, $('#my-payment-form .payment-errors')[0], response.error);
                } else if (status != 200) {
                    Worldpay.handleError(form, $('#my-payment-form .payment-errors')[0], response);
                } else {
                    var token = response.token;
                    Worldpay.formBuilder(form, 'input', 'hidden', 'token', token);
                    Worldpay.formBuilder(form, 'input', 'hidden', 'apmName', $('#apm-name').val());
                    $('#my-payment-form .token').html("Your token is: " + token);
                    form.submit();
                }
            });

            $('#chkReusable').prop('checked', false);


            $('#chkReusable').change(function(){
                if ($(this).is(':checked') && $('#apm-name').val() != 'giropay') {
                    Worldpay.reusable = true;
                }
                else {
                    Worldpay.reusable = false;
                }
            });

            $('#apm-name').on('change', function(){
                if ($(this).val() == 'giropay') {
                    Worldpay.reusable = false;
                    $('#swift-code').attr('data-worldpay-apm','swiftCode');
                    $('.swift-code-row').show();

                    //No language code for Giropay
                    $('#language-code').removeAttr('data-worldpay');
                    $('.language-code-row').hide();

                    //Reusable token option is not available for Giropay
                    $('.reusable-token-row').hide();

                    //Set acceptance currency to EUR
                    $('#currency').val('EUR');
                }
                else {
                    //we don't want to send swift code to the api if the apm is not Giropay
                    $('#swift-code').removeAttr('data-worldpay-apm');
                    $('.swift-code-row').hide();
                    $('.reusable-token-row').show();

                    //language code enabled by default
                    $('#language-code').attr('data-worldpay','language-code');
                    $('.language-code-row').show();

                    $('#currency').val('GBP');
                }
            });
        }
    </script>

</body>
</html>
