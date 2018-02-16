<?php

require_once("functions.php");
header('content-type: text/html; charset: utf-8');

# Get form data
$itemName=$_REQUEST['itemName'];
$itemDescription=$_REQUEST['itemDescription'];
$itemSku=$_REQUEST['itemSku'];
$itemPrice=$_REQUEST['itemPrice'];
$itemQuantity=$_REQUEST['itemQuantity'];
$payerEmail=$_REQUEST['payerEmail'];
$payerPhone=$_REQUEST['payerPhone'];
$payerFirstName=$_REQUEST['payerFirstName'];
$payerLastName=$_REQUEST['payerLastName'];
$shippingAddressRecipient=$_REQUEST['shippingAddressRecipient'];
$shippingAddressStreet1=$_REQUEST['shippingAddressStreet1'];
$shippingAddressStreet2=$_REQUEST['shippingAddressStreet2'];
$shippingAddressPostal=$_REQUEST['shippingAddressPostal'];
$shippingAddressCity=$_REQUEST['shippingAddressCity'];
$shippingAddressCountry=$_REQUEST['shippingAddressCountry'];
$shippingAddressState=$_REQUEST['shippingAddressState'];
$disallowRememberedCards=$_REQUEST['disallowRememberedCards'];
$rememberedCards=$_REQUEST['rememberedCards'];
$paypalMode=$_REQUEST['paypalMode'];
$clientId=$_REQUEST['clientId'];
$secret=$_REQUEST['secret'];
$experience_profile_id=$_REQUEST['experience_profile_id'];
$returnUrl=$_REQUEST['returnUrl'];
$cancelUrl=$_REQUEST['cancelUrl'];
$ppplusJsLibraryLang=$_REQUEST['ppplusJsLibraryLang'];
$currency=$_REQUEST['currency'];
$iframeHeight=$_REQUEST['iframeHeight'];
$merchantInstallmentSelection=$_REQUEST['merchantInstallmentSelection'];
$merchantInstallmentSelectionOptional=$_REQUEST['merchantInstallmentSelectionOptional'];

$total = number_format($itemPrice * $itemQuantity,2);

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


#CREATE PAYMENT
$url = $host.'/v1/payments/payment';
$payment = '{
  "intent": "sale",
  "experience_profile_id": "'.$experience_profile_id.'",
  "payer": {
    "payment_method": "paypal"
  },
  "transactions": [
    {
      "amount": {
        "currency": "'.$currency.'",
        "total": "'.$total.'",
        "details": {}
      },
      "description": "This is the payment transaction description",
      "custom": "This is a custom field you can use to identify orders for example",
      "payment_options": {
        "allowed_payment_method": "IMMEDIATE_PAY"
      },
      "item_list": {
        "items": [
          {
            "name": "'.$itemName.'",
            "description": "'.$itemDescription.'",
            "quantity": "'.$itemQuantity.'",
            "price": "'.$itemPrice.'",
            "sku": "'.$itemSku.'",
            "currency": "'.$currency.'"
          }
        ],
         "shipping_address": {
          "recipient_name": "'.$shippingAddressRecipient.'",
          "line1": "'.$shippingAddressStreet1.'",
          "line2": "'.$shippingAddressStreet2.'",
          "city": "'.$shippingAddressCity.'",
          "country_code": "'.$shippingAddressCountry.'",
          "postal_code": "'.$shippingAddressPostal.'",
          "state": "'.$shippingAddressState.'",
          "phone": "'.$payerPhone.'"
        }
      }
    }
  ],
  "redirect_urls": {
    "return_url": "'.$returnUrl.'",
    "cancel_url": "'.$cancelUrl.'"
  }
}
';

//var_dump ($json);
//die($payment);
$json_resp = make_post_call($url, $payment);

#Get the approval URL for later use
$approval_url = $json_resp['links']['1']['href'];

#Get the token out of the approval URL
$token = substr($approval_url,-20);

#Get the PaymentID for later use
$paymentID = ($json_resp['id']);

#Put JSON in a nice readable format
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
<body id="debug">
<div style="display: none;" id="paypal-config"
    data-checkout="inline"
    data-checkout-url="http://paypalplussampleshopbr-sandbox-9451.ccg21.dev.paypalcorp.com/PayPalPlusSampleShop-br/checkout-now"
></div>


<div class="container">

<h1 class="page-header">
    PayPal Plus Sample Shop
    <small>(<a href="index.html" id="">config</a>)</small>
</h1>




<div class="row" style="">

<form method="post" class="horizontal-form" action="?action=inline"
    id="checkout-form" onSubmit="return false;"
    data-checkout="inline">

<div class="col-md-6">


<h2>How do you want to pay?</h2>

<div class="form-group" id="psp-group">
    <label class="control-label">Choose your favorite way to spend some money:</label>

    <div class="panel">
        <div class="panel-body">
            <div id="pppDiv"> <!-- the div which id the merchant reaches into the clientlib configuration -->
                <script type="text/javascript">
                    document.write("iframe is loading...");
                </script>
                <noscript> <!-- in case the shop works without javascript and the user has really disabled it and gets to the merchant's checkout page -->
                    <iframe src="https://www.paypalobjects.com/webstatic/ppplusbr/ppplusbr.min.js/public/pages/pt_BR/nojserror.html" style="height: 400px; border: none;"></iframe>
                </noscript>
            </div>
        </div>
    </div>

</div>


</div>
<div class="col-md-6">

<h2>Shipping Details</h2>

    <div class="form-group" id="shipping-address-group">
        <label class="control-label">Shopping Cart</label>
        <table class="table table-striped">
            <tr>
                <td>Item</td>
                <td>Quantity</td>
                <td>Price</td>
                <td>Subtotal</td>
            </tr>

                <tr>
                    <td><?php echo $itemName?></td>
                    <td><?php echo $itemQuantity?></td>
                    <td>$<?php echo $itemPrice." ".  $currency?></td>
                    <td>$<?php echo $total." ".  $currency?></td>
                </tr>
        </table>
        <label class="control-label">Total: <?php echo $total." ".  $currency?></label>
    </div>

    <div class="form-group" id="shipping-address-group">
        <label class="control-label">Shipping Address:</label>
        <table class="table table-striped">
            <tr>
                <td>recipient name:</td>
                <td><?php echo $payerFirstName ." " . $payerLastName ?></td>
            </tr>
            <tr>
                <td>street1:</td>
                <td><?php echo $shippingAddressStreet1 ?></td>
            </tr>
            <tr>
                <td>street2:</td>
                <td><?php echo $shippingAddressStreet2 ?></td>
            </tr>
            <tr>
                <td>postal code:</td>
                <td><?php echo $shippingAddressPostal ?></td>
            </tr>
            <tr>
                <td>city:</td>
                <td><?php echo $shippingAddressCity ?></td>
            </tr>
            <tr>
                <td>state:</td>
                <td><?php echo $shippingAddressState ?></td>
            </tr>
            <tr>
                <td>country code:</td>
                <td><?php echo $shippingAddressCountry ?></td>
            </tr>
        </table>

    </div>

    <br />

<p><strong>The outside continue button:</strong></p>
<button
  type="submit"
  id="continueButton"
  class="btn btn-lg btn-primary btn-block infamous-continue-button"
  onclick="ppp.doContinue(); return false;">
    
    Continue
</button>
<a id="payNowButton" class="btn btn-lg btn-primary btn-block infamous-continue-button hidden" href="?action=commit">Pay now</a>

</div><!-- col -->
</form>
</div><!-- row -->

<div>
    <div class="col-md-12">
    <hr />

        <h2>Developer Info</h2>
        <p><strong><code>The iframe response is : <code/><strong/></p>
        <div id="installments" ></div>
        <pre id="installmentsJson" class="json-data">{"result": "no data yet"}</pre>
        <div id="responseDiv"></div>
        <pre id="responseJson" class="json-data">{"result": "no data yet"}</pre>
        <pre id="responseOnError" class="json-data"></pre>

    <h3>General information</h3>

    <p>The approval_url is:
        <strong><code><?php echo $approval_url;?></code></strong></p>
    <p>The EC-Token is:
        <strong><code><?php echo $token;?></code></strong></p>
    <p>The Payment-ID is:
        <strong><code><?php echo $paymentID;?></code></strong></p>

    <h3>The payment created:
    </h3>
    <pre class="json-data"><?php echo $json_resp;?></pre>
    </div>
</div>
<script src="https://www.paypalobjects.com/webstatic/ppplusdcc/ppplusdcc.min.js?123456"></script>
<script>

    var ppp = PAYPAL.apps.PPP({

        approvalUrl: "<?php echo $approval_url;?>",

        buttonLocation: "outside",
        preselection: "none",
        surcharging: false,
        hideAmount: false,
        placeholder: "pppDiv",

        disableContinue: "continueButton",
        enableContinue: "continueButton",

        // merchant integration note:
        // this is executed when the iframe posts the "checkout" action to the library
        // the merchant can do an ajax call to his shop backend to save the remembered cards token
        onContinue: function (rememberedCards, payerId, token, term) {
            console.log(term);
            // TODO: remove payNowButton
            $('#payNowButton').removeClass('hidden');
            $('#continueButton').addClass('hidden');
            var access_token = "<?php echo $access_token; ?>";
            var paymentID = "<?php echo $paymentID; ?>";
            var paypalMode = "<?php echo $paypalMode; ?>";
            var payURL = "ExecutePayment.php?access_token=" + access_token + "&payerId=" + payerId + "&paymentID=" + paymentID + "&paypalMode=" + paypalMode;
            $('#payNowButton').prop('href', payURL);
            
            document.getElementById("installmentsJson").innerHTML = (term ? "<p><strong><code id='installmentsText'>"+ JSON.stringify(term) +"</code></strong></p>" : "No installments option selected");
           
		    document.getElementById("responseJson").innerHTML = JSON.stringify('Success');
            if(rememberedCards) {
            
			document.getElementById("responseDiv").innerHTML = "<p><strong><code>Transaction has been approved</code></strong></p>"+
                "<p><strong><code id='rememberedCardsText'>user token (remembered cards) = "+ rememberedCards +"</code></strong></p>";

            } else {
                //document.getElementById("responseDiv").innerHTML = "<p><strong><code>Transaction has been approved</code></strong></p>";
            }

            // TODO: use this instead of payNowButton to go directly to the Execute Payment Page (and finalize the payment)
            /*var url = "ExecutePayment.php?access_token=" + access_token + "&payerId=" + payerId + "&paymentID=" + paymentID + "&paypalMode=" + paypalMode;
            $('#payNowButton').prop('href', payURL);
            window.top.location = url;*/
            
        },

        onError: function (err) {
            var msg = jQuery("#responseOnError").html()  + "<BR />" + JSON.stringify(err);
            jQuery("#responseOnError").html(msg);
        },

        language: "<?php echo $ppplusJsLibraryLang; ?>",
        country: "<?php echo $shippingAddressCountry; ?>",
        disallowRememberedCards: "<?php echo $disallowRememberedCards; ?>",
        rememberedCards: "<?php echo $rememberedCards; ?>",
        mode: "<?php echo $paypalMode; ?>",
        useraction: "continue",
        payerEmail: "<?php echo $payerEmail; ?>",
        payerPhone: "<?php echo $payerPhone; ?>",
        payerFirstName: "<?php echo $payerFirstName; ?>",
        payerLastName: "<?php echo $payerLastName; ?>",
        payerTaxId: "",
        payerTaxIdType: "",
        merchantInstallmentSelection: "<?php echo $merchantInstallmentSelection; ?>",
        merchantInstallmentSelectionOptional:"<?php echo $merchantInstallmentSelectionOptional; ?>",
        hideMxDebitCards: false,
        iframeHeight: "<?php echo $iframeHeight; ?>"
        
    });
</script>

<style>
    .hidden {display:none;}
</style>
<div class="row">
    <div class="col-md-12">
    <hr />
    <footer>PayPal Plus Sample Shop</footer>
    </div>
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
$("#debug").on("click", function (e) {
    $('.open').toggleClass('open closed');
});
$("#sessionInfo").on("click", function (e) {
    e.stopPropagation();
});
$("#sessionInfoDrawer").on("click", function (e) {
    e.stopPropagation();
    $(this).toggleClass('closed open');
});
</script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="./Shop_files/sample_shop.js"></script>

</body>
</html>

