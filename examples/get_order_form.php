<?php include("header.php"); ?>
        <h1>PHP Library Get Order Example</h1>
        <form method="post" action="get_order.php" id="get-order-form">
            <div class="form-row">
                <label>
                    Worldpay Order Code
                </label>
                <input type="text" id="order-code" name="orderCode" value="" />
            </div>
            <input type="submit" id="btn-get-order" value="Get Order" />
        </form>

    </div>

</body>
</html>
