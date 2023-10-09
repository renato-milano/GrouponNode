<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once dirname(__FILE__).'/../../../config/config.inc.php';
require_once dirname(__FILE__).'/../../../init.php';

 /*   // Create user for orders imported from this module.
    $new_customer = new Customer();
    $new_customer->email = 'test@test.com';
    $new_customer->lastname = 'test';
    $new_customer->firstname = 'User';
    $new_customer->passwd = 'no password';
    $new_customer->add();
    $id_customer = $new_customer->id;

    // Create delivery address.
    $new_address = new Address();
    $new_address->alias = 'Test Address';
    $new_address->firstname = 'Test';
    $new_address->lastname = 'Address';
    $new_address->city = 'City';
    $new_address->id_state = 0;
    $new_address->id_customer = $id_customer;
    $new_address->id_country = 10;
    $new_address->address1 = 'Address 1';
    $new_address->address2 = 'Address 2';
    $new_address->add();
    $id_address = $new_address->id;

    // Cart information
    $new_cart = new Cart();
    $new_cart->id_customer = $new_customer->id;
    $new_cart->id_address_delivery = $id_address;
    $new_cart->id_address_invoice  = $id_address;
    $new_cart->id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
    $new_cart->id_currency = 1;
    $new_cart->id_carrier = 1;

    $new_cart->add();

    // Add the products to the cart
    $result = $new_cart->updateQty(15,3); // Added 15 products to product with the id number 3

    // Creating order from cart
    $payment_module = Module::getInstanceByName('ps_wirepayment');
    $result = $payment_module->validateOrder($new_cart->id, Configuration::get('PS_OS_BANKWIRE'), $new_cart->getOrderTotal(), 'Groupon', 'Test');

    // Get the order id after creating it from the cart.
    $id_order = Order::getOrderByCartId($new_cart->id);
    $new_order = new Order($id_order);*/
    switch (Tools::getValue('method')) {
case 'ImportOrdini':
    $response = file_get_contents ("ResponseText.json");
    if( $response ) {
       $response_json = json_decode ( $response );
       if( $response_json->success == true ) {
        foreach ($response_json->data as $value) {
            $new_customer = new Customer();
    $new_customer->email = "ClienteGenerico@Groupon.com";
    $new_customer->lastname = $value->customer->billing_address->name;
    $new_customer->firstname = $value->customer->billing_address->name;
    $new_customer->passwd = 'no password';
    $new_customer->add();
    $id_customer = $new_customer->id;

    // Create delivery address.
    $new_address = new Address();
    $new_address->alias = 'Nessuno';
    $new_address->firstname = $value->customer->name;
    $new_address->lastname = $value->customer->name;
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
    $new_billing_address->firstname = $value->customer->billing_address->name;
    $new_billing_address->lastname = $value->customer->billing_address->name;
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
    $result = $new_cart->updateQty($item->quantity,Product::getIdByReference($item->sku)); // Adding Products to cart
    }
    // Creating order from cart
    $payment_module = Module::getInstanceByName('ps_wirepayment');
    $result = $payment_module->validateOrder($new_cart->id, Configuration::get('PS_OS_BANKWIRE'), $new_cart->getOrderTotal(), 'Groupon', 'Test');

    // Get the order id after creating it from the cart.
    $id_order = Order::getOrderByCartId($new_cart->id);
    $new_order = new Order($id_order);
    $history = new OrderHistory();
    $history->id_order = $id_order;
    $history->changeIdOrderState(2, $id_order);
    echo 'Ordine Importato: '.$value->orderid.'<br>';
          }
         
       } else {
         //Alarm!
       }
    }
break;
default:
    break;
}
?>