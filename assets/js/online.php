/**
 * @author creamgod45 
 * @name 點餐系統
 * @version v3.1
 */
// AUTO GENERATE ITEM ARRAY <?php include "item.php";?>
function add_order(_itemid){
    if(item_list[_itemid]['limit'] === -1){
        item_list[_itemid]['unit']++;
        total_unit++;
        total_money += parseInt(item_list[_itemid]['price']);
        update_order();
    }else if(item_list[_itemid]['limit']-1 == item_list[_itemid]['unit']){
        item_list[_itemid]['unit']++;
        total_unit++;
        total_money += parseInt(item_list[_itemid]['price']);
        update_order();
        $("#add_"+_itemid).attr("disabled", true);
    }else if(item_list[_itemid]['limit'] > item_list[_itemid]['unit']){
        item_list[_itemid]['unit']++;
        total_unit++;
        total_money += parseInt(item_list[_itemid]['price']);
        update_order();
    }
}

function less_order(_itemid){
    // 防止數量為0
    if(item_list[_itemid]['unit'] != 0){
        item_list[_itemid]['unit']--;
        total_unit--;
        total_money -= parseInt(item_list[_itemid]['price']);
        update_order();
        if(item_list[_itemid]['unit'] != item_list[_itemid]['limit']){$("#add_"+_itemid).attr("disabled", false);}
    }
}

function del_order(_itemid){
    var count = item_list[_itemid]['unit'];
    total_money -= (count * parseInt(item_list[_itemid]['price']));
    total_unit -= count;
    item_list[_itemid]['unit'] = 0;
    $("#add_"+_itemid).attr("disabled", false);
    update_order();
}

/**
 * 所有物品分開
 * @param {*} _value 
 */
function itemlist_split(_value){
	var tmp = "";
	for (const [key, value] of Object.entries(_value)) {
        // 取消顯示商品數量為0
        if(_value[key]['unit'] != 0){
            tmp += "/" + key + ":" + _value[key]['unit'];
        }
	}
	return tmp;
}

function update_order() {
    $(".order_item").html("<tr><th scope=\"row\"></th><td>總共</td><td id=\"count_unit\">"+total_unit+"</td><td id=\"count_money\">$"+total_money+"</td></tr>");
    $("#display_unit").html(total_unit);
    var string = "";
	for (const [key, value] of Object.entries(item_list)) {
        // 取消顯示商品數量為0
        if(item_list[key]['unit'] != 0){
            string += "<tr><th scope=\"row\" onclick=\"del_order('"+key+"')\">&times;</th><td>"+item_list[key]['name']+"</td><td>"+item_list[key]['unit']+"</td><td>$"+ (item_list[key]['unit'] * item_list[key]['price'])+"</td></tr>";
        }
    }
    $(".order_item").prepend(string);
    $("#orderlist").attr('value', itemlist_split(item_list));
}

setInterval(() => {
    update_order();
}, 100);