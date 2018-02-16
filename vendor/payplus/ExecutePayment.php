<?php
require_once("functions.php");

$access_token= $_GET['access_token'];
$paypalMode = $_GET['paypalMode'];
$payerId = $_GET['payerId'];
$paymentID = $_GET['paymentID'];

	if ($paypalMode=="sandbox") {
    	$host = 'https://api.sandbox.paypal.com';
	}
	if ($paypalMode=="live") {
   		$host = 'https://api.paypal.com';
	}
#GET ACCESS TOKEN
$url = $host.'/v1/payments/payment/'.$paymentID.'/execute/'; 
$execute = '{"payer_id" : "'.$payerId.'"}';
$json_resp = make_post_call($url, $execute);
$json_resp = stripslashes(json_format($json_resp));
?>
<html>
<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Checkout</title>

    <link rel="stylesheet" type="text/css"
        href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css"
        href="./Shop_files/shop.css" />
</head>

<body>
<div>
    <div class="col-md-12">
    <h2>The payment response:
    </h2>

    <pre class="json-data"><?php echo $json_resp;?></pre>

    </div>
</div>

</body>
</html>