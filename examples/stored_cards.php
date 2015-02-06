<?php include("header.php"); ?>
        <h1>PHP Library Stored Card Details Example</h1>
        <form method="post" action="get_stored_cards.php" id="my-payment-form">
            <div class="payment-errors"></div>
            <div class="form-row">
                <label>
                    Worldpay resuable token
                </label>
                <input type="text" id="token" name="token" value="" />
            </div>
            <input type="submit" id="show-card" value="Show card details" />
        </form>

    </div>

</body>
</html>
