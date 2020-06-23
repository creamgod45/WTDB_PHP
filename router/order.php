<?php 

require "lib/order.php";

header('Content-Type: application/json; charset=utf-8');

$order = new order();
$object = array();
$item = @$order->GetOnceOrder(@router(2));

if(!empty($item)){
    $object = $item;
}else{
    $object['STATUS'] = 'ERROR';
    $object['CODE']   = '404'; 
}

echo json_encode($object, JSON_UNESCAPED_UNICODE);
