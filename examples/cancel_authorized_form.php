<?php include("header.php"); ?>
        <h1>PHP Library Cancel Authorized Order Example</h1>
        <form method="post" action="cancel_authorized_order.php" id="cancel-authorized-order-form">
            <div class="payment-errors"></div>
            <div class="form-row">
                <label>
                    Worldpay Order Code
                </label>
                <input type="text" id="order-code" name="orderCode" value="" />
            </div>
            <input type="submit" id="cancel-order" value="Cancel Authorized Order" />
        </form>

    </div>

</body>
</html>
