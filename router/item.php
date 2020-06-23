<?php 

require "lib/item.php";

header('Content-Type: application/json; charset=utf-8');

$item = new item();
$object = array();
$tmp = @$item->GetOnceItem(@router(2));

if(!empty($tmp)){
    $object = $tmp;
}else{
    $object['STATUS'] = 'ERROR';
    $object['CODE']   = '404'; 
}

echo json_encode($object, JSON_UNESCAPED_UNICODE);
