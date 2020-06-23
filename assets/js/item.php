<?php 
    require "../../lib/item.php";
    $item = new item();

    session_start();
    header("Content-Type: application/javascript");
    
    $member_list = $_SESSION['cache_js_member'];    
    $js_num = $item->GetNum($member_list);
    $js_item = $item->GetItem($member_list);

echo "
var item_list = Array();
var total_money = 0;
var total_unit = 0;
";
for ($i=1; $i <= $js_num; $i++) { 
    echo "item_list['".$js_item[$i]['iid']."'] = Array(); ";
    echo "item_list['".$js_item[$i]['iid']."']['unit'] = 0; ";
    echo "item_list['".$js_item[$i]['iid']."']['name'] = '".$js_item[$i]['name']."'; ";
    echo "item_list['".$js_item[$i]['iid']."']['price'] = '".$js_item[$i]['price']."'; ";
    echo "item_list['".$js_item[$i]['iid']."']['limit'] =".$js_item[$i]['unit']."; ";
}
echo "";
/**
 *  清除快取
 */
unset($_SESSION['cache_js_member']);
?>