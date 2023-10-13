<?php
/**
*  Copyright (C) Prestalia - All Rights Reserved
*
*  Unauthorized copying of this file, via any medium is strictly prohibited
*  Proprietary and confidential
*
*  @author    Prestalia
*  @copyright 2015-2019 Prestalia
*  @license   This is proprietary software thus it cannot be distributed or reselled
*/

class GrouponUtility extends ModuleAdminController
{

public function __construct(Type $var = null) {
    $this->var = $var;
}

public function insertOrder($idOrder,$fulfillment_lineitem_id){
    $sql = 'INSERT INTO `grouponnode` VALUES (NULL,'.$idOrder.','.$fulfillment_lineitem_id.')';
    Db::getInstance()->execute($sql);
    }

public function UpdateOrder($idOrder){

    //UPDATE grouponnode SET lineitem="EXPORT" WHERE grouponnode.orderID="142";
    $sql = 'UPDATE `grouponnode` SET lineitem="EXPORT" WHERE grouponnode.orderID='.$idOrder;
    Db::getInstance()->execute($sql);
//SELECT grouponnode.orderID,grouponnode.lineitem, ps_order_carrier.tracking_number 
//FROM `grouponnode` 
//INNER JOIN ps_order_carrier ON grouponnode.orderID=ps_order_carrier.id_order 
//WHERE grouponnode.orderID="adsadhask";

    $sql= 'SELECT grouponnode.orderID,grouponnode.lineitem, ps_order_carrier.tracking_number FROM `grouponnode` INNER JOIN ps_order_carrier ON grouponnode.orderID=ps_order_carrier.id_order WHERE grouponnode.orderID='.$idOrder;
    $RQ = Db::getInstance()->executeS($sql);
    foreach ($RQ as $item) {
        $datatopost = array (
            "supplier_id" => "45022",
            "token" => "n32NXEKf6nIrFLIB4CgaIl6kjczEer8",
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
            $response_json = json_decode( $response );
            if( $response_json->success == true ) {
            
            } else {
        
            }
         }
      }
}

public function SendTracking($idOrder){

}

}

?>