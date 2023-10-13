<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
include(dirname(__FILE__) . '/config/config.inc.php');
include(dirname(__FILE__) . '/init.php');
/*
corriere: glsit
1178481615     NN634766687
1178480390     NN634766689
1178480169     NN634766688
1178479764     NN634766690
1178479416     NN634766596
1178478204     NN634766691
1178478032     NN634766693

*/
   // requires PHP cURL http://no.php.net/curl
   $datatopost = array (
    "supplier_id" => "45022",
    "token" => "n32NXEKf6nIrFLIB4CgaIl6kjczEer8",
    "tracking_info" => '[
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178481615", "tracking" : "NN634766687"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178481615", "tracking" : "NN634766687"},
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178480390", "tracking" : "NN634766689"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178480390", "tracking" : "NN634766689"},
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178480169", "tracking" : "NN634766688"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178480169", "tracking" : "NN634766688"},
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178479764", "tracking" : "NN634766690"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178479764", "tracking" : "NN634766690"},
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178479416", "tracking" : "NN634766596"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178479416", "tracking" : "NN634766596"},
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178478204", "tracking" : "NN634766691"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178478204", "tracking" : "NN634766691"},
{ "carrier" : "glsit", "fulfillment_lineitem_id" : "1178478032", "tracking" : "NN634766693"},
{ "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "1178478032", "tracking" : "NN634766693"}
]'
 );
 
 $ch = curl_init ("https://scm.commerceinterface.com/api/v4/lineitem_tracking_notification");
 curl_setopt ($ch, CURLOPT_POST, true);
 curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
 $response = curl_exec ($ch);

 if( $response ) {
    echo "Risposta: <br>";
    echo $response;
    $response_json = json_decode( $response );
    if( $response_json->success == true ) {
      echo $response_json;
    } else {

    }
 }
?>