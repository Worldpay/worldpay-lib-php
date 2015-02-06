<?php include("header.php"); ?>
        <h1>PHP Library Refund Example</h1>
        <form method="post" action="partial_refund_order.php" id="my-payment-form">
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
            <input type="submit" id="refund-order" value="Partial Refund Order" />
        </form>

    </div>

</body>
</html>
