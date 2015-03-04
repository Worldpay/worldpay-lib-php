
 <?php include("header.php"); ?>
        <script src="https://cdn.worldpay.com/v1/worldpay.js"></script>
        <h1>PHP Library Create Order Example</h1>
        <form method="post" action="create_order.php" id="my-payment-form">
            <div class="payment-errors"></div>
            <div class="form-row">
                <label>
                    Name
                </label>
                <input type="text" id="name" name="name" data-worldpay="name" value="Example Name" />
            </div>

            <div class="form-row">
                <label>
                    Card Number
                </label>
                <input type="text" id="card" size="20" data-worldpay="number" value="4444333322221111" />

            </div>


            <div class="form-row">
                <label>
                    CVC
                </label>
                <input type="text" id="cvc" size="4" data-worldpay="cvc" value="321" />
            </div>


            <div class="form-row">
                <label>
                    Expiration (MM/YYYY)
                </label>
                <select id="expiration-month" data-worldpay="exp-month">
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10" selected="selected">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <span> / </span>
                <select id="expiration-year" data-worldpay="exp-year">
                    <option value="2015">2015</option>
                    <option value="2016" selected="selected">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                </select>
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
                <input type="text" id="country-code" name="countryCode" value="GB" />
            </div>

            <div class="form-row">
                <label>
                    Description
                </label>
                <input type="text" id="description" name="description" value="My test order" />
            </div>

            <div class="form-row">
                <label>Reusable Token:</label>
                <input type="checkbox" id="chkReusable" />
            </div>

            <div class="form-row">
                <label>Use 3DS :</label>
                <input type="checkbox" id="chk3Ds" name="3ds" />
            </div>
            
            <div class="form-row">
                <label>Authorise Only:</label>
                <input type="checkbox" id="chkAuthoriseOnly" name="authoriseOnly" />
            </div>
            
                <input type="submit" id="place-order" value="Place Order" />
            </div>

            <div class="token"></div>

        </form>

    </div>

    

   

    <script type="text/javascript">
        if (!Worldpay) {
            alert('Worldpay JS not loaded!');
        }

        // Set client key
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
        		$('#my-payment-form .token').html("Your token is: " + token);
        		form.submit();
        	}
        });

        $('#chkReusable').change(function(){
            if ($(this).is(':checked')) {
                Worldpay.reusable = true;
            }
            else {
                Worldpay.reusable = false;
            }
        });
        $('#chkReusable').prop('checked', false);
    </script>

</body>
</html>
