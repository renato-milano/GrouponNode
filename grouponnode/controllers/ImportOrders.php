<?php
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once dirname(__FILE__).'/../../../config/config.inc.php';
require_once dirname(__FILE__).'/../../../init.php';
require_once dirname(__FILE__).'/GrouponUtility.php';
$utility = new GrouponUtility();

    switch (Tools::getValue('method')) {
case 'ImportOrdini':
    $supplierID = Configuration::get('GROUPON_SUPPLIER_ID');
    $token = Configuration::get('GROUPON_TOKEN');
    $response = file_get_contents ( 'https://scm.commerceinterface.com/api/v4/get_orders?supplier_id='.$supplierID.'&token='.$token );
    //$response = file_get_contents ('ResponseText.json');
    if( $response ) {
       $response_json = json_decode ( $response );
      // echo $response;
       if( $response_json->success == true ) {
        // echo $response_json->success;
        $ids_to_mark = array();
        foreach ($response_json->data as $value) {
         echo '<br> Lavoro Ordine:  '.$value->orderid;
            $new_customer = new Customer();
    $new_customer->email = "ClienteGenerico@Groupon.com";
    $value->customer->billing_address->name = str_replace("/","",$value->customer->billing_address->name);
    $name = explode(" ",$value->customer->billing_address->name);
    $i = 1;
    $cognome = "";
    while($i < count($name)){
    $cognome .= " ".$name[$i];
    $i = $i+1;
                  }
    $new_customer->lastname = $cognome;
    $new_customer->firstname = $name[0];
    $new_customer->passwd = 'no password';
    $new_customer->add();
    $id_customer = $new_customer->id;
    // Create delivery address.
    $new_address = new Address();
    $new_address->alias = 'Nessuno';
    $ShipName= explode(" ", $value->customer->name);
    $i = 1;
    $cognome = "";
    while($i < count($ShipName)){
    $cognome .= " ".$ShipName[$i];
    $i = $i+1;
                  }
    $new_address->firstname = $ShipName[0];
    $new_address->lastname = $cognome;
    $new_address->city = $value->customer->city;
    $new_address->id_state = 0;
    $new_address->id_customer = $id_customer;
    $new_address->id_country = Country::getByIso($value->customer->country);
    $new_address->postcode = $value->customer->zip;
    $new_address->phone = $value->customer->phone;
    $new_address->address1 = $value->customer->address1;
    $new_address->address2 = $value->customer->address2;
    $new_address->add();
    $id_address = $new_address->id;
 
    $new_billing_address = new Address();
    $new_billing_address->alias = 'Nessuno';
    $new_billing_address->firstname = $name[0];
    $new_billing_address->lastname = $cognome;
    $new_billing_address->city = $value->customer->billing_address->city;
    $new_billing_address->id_state = 0;
    $new_billing_address->id_customer = $id_customer;
    $new_billing_address->id_country = Country::getByIso($value->customer->billing_address->country);
    $new_billing_address->postcode = $value->customer->billing_address->zip;
    $new_billing_address->phone = $value->customer->billing_address->phone;
    $new_billing_address->address1 = $value->customer->billing_address->address1;
    $new_billing_address->address2 = $value->customer->billing_address->address2;
    $new_billing_address->add();
    $id_billing_address = $new_billing_address->id;
    // Cart information
    $new_cart = new Cart();
    $new_cart->id_customer = $new_customer->id;
    $new_cart->id_address_delivery = $id_address;
    $new_cart->id_address_invoice  = $id_billing_address;
    $new_cart->id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
    $new_cart->id_currency = 1;
    $new_cart->id_carrier = 1;
   
    $new_cart->add();
    // Add the products to the cart
    foreach($value->line_items as $item){
    $result = $new_cart->updateQty($item->quantity,Product::getIdByReference($item->bom_sku)); // Adding Products to cart
    }
    // Creating order from cart
    $payment_module = Module::getInstanceByName('ps_wirepayment');
    $result = $payment_module->validateOrder($new_cart->id, Configuration::get('PS_OS_BANKWIRE'), $new_cart->getOrderTotal(), 'Groupon', 'Test');
    if(!$result){
      echo 'ERRORE ORDINE! '.
      continue;
    }
    // Get the order id after creating it from the cart.
    $id_order = Order::getOrderByCartId($new_cart->id);
    $new_order = new Order($id_order);
    $history = new OrderHistory();
    $history->id_order = $id_order;
    $history->changeIdOrderState(2, $id_order);

    foreach($value->line_items as $item){
      $utility->insertOrder($id_order,$item->fulfillment_lineitem_id,$item->quantity);
      array_push($ids_to_mark,$item->ci_lineitemid);
      echo ' INSERITO ORDINE: '.$id_order;
    }
          }// FINE LETTURA RIGHE
         
          $utility->SetExported($ids_to_mark);

       } else {
         //Alarm!
       }
    }
break;
case 'SendTracking':

  $sql = 'SELECT value FROM `grouponnode` WHERE grouponnode.order="'.$_GET['orderID'].'"';

$rq = Db::getInstance()->executeS($sql);
foreach ($rq as $item) {
  var_dump($item);
}
    break;
default:
    break;
}
?>