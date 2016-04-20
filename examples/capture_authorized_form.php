<?php include("header.php"); ?>
        <h1>PHP Library Capture Authorized Order Example</h1>
        <form method="post" action="capture_authorized_order.php" id="capture-authorized-order-form">
            <div class="payment-errors"></div>
            <div class="form-row">
                <label>
                    Worldpay Order Code
                </label>
                <input type="text" id="order-code" name="orderCode" value="" />
            </div>
            <div class="form-row">
                <label>
                    Amount
                </label>
                <input type="text" id="amount" name="amount" value="" />
            </div>
            <input type="submit" id="capture-order" value="Capture Authorized Order" />
        </form>
    </div>
</body>
</html>
