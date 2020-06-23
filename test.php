<?php 
date_default_timezone_set("Asia/Taipei");

require_once "lib/auth.php";
require_once "lib/item.php";
require_once "lib/logger.php";
require_once "lib/order.php";
require_once "lib/conn.php";

$auth = new auth();
$item = new item();
$logger = new logX();
$order = new order();
$conn = new conn();

$test[0]=$auth->test();
$test[1]=$item->test();
$test[2]=$logger->test();
$test[3]=$order->test();
$test[4]=$conn->test();

for ($i=0; $i < count($test); $i++) { 
    if($test[$i]){
        echo $i."載入成功<br>";
    }else{
        echo $i."載入失敗<br>";
    }
}
