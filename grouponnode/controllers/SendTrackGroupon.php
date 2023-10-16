<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once dirname(__FILE__).'/../../../config/config.inc.php';
require_once dirname(__FILE__).'/../../../init.php';
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    $idOrder = Tools::getValue('idOrder');
    $supplierID = Configuration::get('GROUPON_SUPPLIER_ID');
    $token = Configuration::get('GROUPON_TOKEN');

    $sql= 'SELECT grouponnode.orderID,grouponnode.lineitem, ps_order_carrier.tracking_number FROM `grouponnode` INNER JOIN ps_order_carrier ON grouponnode.orderID=ps_order_carrier.id_order WHERE grouponnode.orderID='.$idOrder;
    $RQ = Db::getInstance()->executeS($sql);
    foreach ($RQ as $item) {
        $datatopost = array (
            "supplier_id" => $supplierID,
            "token" => $token,
            "tracking_info" => '[
        { "carrier" : "glsit", "fulfillment_lineitem_id" : "'.$item["lineitem"].'", "tracking" : "'.$item["tracking_number"].'"},
        { "quantity" : 1, "carrier" : "glsit", "fulfillment_lineitem_id" : "'.$item["lineitem"].'", "tracking" : "'.$item["tracking_number"].'"}
        ]'
         );
 
         $ch = curl_init ("https://scm.commerceinterface.com/api/v4/lineitem_tracking_notification");
         curl_setopt ($ch, CURLOPT_POST, true);
         curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
         curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
         $response = curl_exec ($ch);
        
         if( $response ) {
            echo $response;
            $response_json = json_decode( $response );
            if( $response_json->success == true ) {
               
            } else {
        
            }
         }
      }
?>