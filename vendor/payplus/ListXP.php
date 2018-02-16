<?php

require_once("functions.php");
header('content-type: text/html; charset: utf-8');

# Get form data
$clientId=$_REQUEST['clientId'];
$secret=$_REQUEST['secret'];
$paypalMode=$_REQUEST['paypalMode'];

if ($paypalMode=="sandbox") {
    $host = 'https://api.sandbox.paypal.com';
}
if ($paypalMode=="live") {
    $host = 'https://api.paypal.com';
}

#GET ACCESS TOKEN
$url = $host.'/v1/oauth2/token'; 
$postArgs = 'grant_type=client_credentials';
$access_token= get_access_token($url,$postArgs);


#CREATE WEB XP
$url = $host.'/v1/payment-experience/web-profiles';

$json_resp = make_get_call($url);
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
<div class="container">

<div>
    <div class="col-md-12">
    <h2>The response:
    </h2>

    <pre class="json-data"><?php echo $json_resp;?></pre>

    </div>
</div>

<div class="form-group pull-right">
    <a href="WebExperienceProfile.html" class="btn btn-primary" role="button">Regresar</a>
</div>

</div>

</body>
</html>