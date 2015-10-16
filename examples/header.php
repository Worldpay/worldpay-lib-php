<!DOCTYPE html>
<html>
<head>
    <title>PHP Library</title>
    <meta charset="utf-8" />
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <style>
	    body {
	        background:#efefef;
	        font-family:Verdana;
	    }
	    .container {
	        width:800px;
	        margin:auto;
	        background:#fff;
	        padding:20px;
	    }
	    .container h1 {
	        text-align:center;
	    }
	    .header {
	        font-weight: bold;
	        width: 800px;
	        height: 35px;
	        margin: 0 auto 20px;
	        border-bottom: 1px solid #D0D0D0;
	    }
	    .form-row {
	        height:50px;
	    }
	    .form-row.large {
	    	height: 150px;
	    }
	    .form-row textarea {
	        resize:none;
	        margin-left: 6px;
	    }
	    .form-row input {
	        height:20px;
	        padding:2px;
	        padding-left:10px;
	    }
	    .form-row label {
	        width:200px;
	        text-align:right;
	        padding-right:10px;
	        display:inline-block;
	    }
	    .left {
	        float: left;
	    }
	    .form-row select {
	        padding: 2px;
	        padding-left: 10px;
	        height:25px;
	    }
	    .payment-errors {
	        font-size: 20px;
	        font-weight: bold;
	        text-align: center;
	        color: red;
	        padding: 20px;
	        margin-bottom: 20px;
	    }
	    .token {
	        padding-top:20px;
	    }
	    #top-nav {
	        list-style: none;
	        text-align: center;
	    }
	    #top-nav li {
	        display:inline-block;
	    }
	    #top-nav li a {
	        text-decoration: none;
	        color:blue;
	        padding-left: 10px;
	    }
    </style>
</head>
<body>

<div class="container">

<ul id="top-nav">
    <li><a href="index.php">Create Order</a></li>&nbsp;&nbsp;|
    <li><a href="index_cardonfile.php">Create Order (CardOnFile)</a></li>&nbsp;&nbsp;|
    <li><a href="create_apm_form.php">Create APM Order</a></li>&nbsp;&nbsp;|
    <li><a href="capture_authorised_form.php">Capture Authorised Order</a></li>&nbsp;&nbsp;|
    <li><a href="cancel_authorised_form.php">Cancel Authorised Order</a></li>&nbsp;&nbsp;|
    <li><a href="refund.php">Refund</a></li>&nbsp;&nbsp;|
    <li><a href="partial_refund.php">Partial Refund</a></li>&nbsp;&nbsp;|
    <li><a href="stored_cards.php">Stored Cards</a></li>
</ul>
