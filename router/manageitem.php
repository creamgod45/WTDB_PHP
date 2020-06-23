<?php
    require "lib/auth.php";
    require "lib/item.php";
    $auth = new auth();
    $item = new item();
    if(@$auth->isMember($_SESSION['Member_Data'])){
        if(@$_POST['edit'] != null){
            @$id = $_POST['item_id'];
            @$name = $_POST['item_name'];
            @$dec = $_POST['item_dec'];
            @$price = $_POST['item_price'];
            @$limit = $_POST['item_limitunit'];
            @$unit = $_POST['item_unit'];
            @$images = $_POST['images'];
            @$enable = $_POST['enable'];
            if((int)$unit === 0){
                $unit = -1;
            }
            $option = [$id, $name, $images, $dec, $price, $unit,  $enable, GetDevice(), GetIP()];
            $query = $item->SettingItem($_SESSION['Member_Data'], $option);
            if($query){
                header('refresh:0;url="/vendor/item"');
            }else{
                echo $query;
            }
        }elseif(@$_POST['delete_all'] != null){
            $oldrows = explode(",", $_POST['rows']);
            $rows = array();
            for($i=0;$i<=count($oldrows)-1;$i++){
                $rows[$i+1] = (int)$oldrows[$i];
            }
            $query = array();
            for($i=1;$i<=count($rows);$i++){
                $query[$i] = $item->RemoveItem($_SESSION['Member_Data'], [
                    $rows[$i], GetDevice(), GetIP()
                ]);
                echo 'ID:'.$rows[$i].'刪除完成'."<br>";
            }
            $ok = 0;
            for($i=1;$i<=count($query);$i++){
                if($query[$i] === true){
                    $ok++;
                }
            }
            if($ok === count($query)){
                echo '執行完成';
                header('refresh:1;url="/vendor/item"');
            }else{
                echo '執行失敗';
                var_dump($query);
            }
        }elseif(@$_POST['submit'] != null){

            if(@$_POST['item_limitunit'] === "on" && $_POST['item_unit'] != 0){
                $unit = $_POST['item_unit'];
            }else{
                $unit = -1;
            }

            // 建立商品
            $query = $item->CreateItem($_SESSION['Member_Data'], [
                'id' => GetRandoom(10),
                'name' => $_POST['item_name'], 
                'image' => $_POST['images'], 
                'description' => $_POST['item_dec'], 
                'price' => $_POST['item_price'], 
                'unit' => $unit, 
                'enable' => "true",
                'device' => GetDevice(),
                'ip' => GetIP(),
            ]);
            if($query){
                echo "建立成功";
                header('refresh:0;url="/vendor/item"');
            }else{
                echo $query;
            }
        }else{
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
                <link rel="stylesheet" type="text/css" href="../assets/css/app_system.css">
                <link rel="stylesheet" type="text/css" href="../assets/DataTables/datatables.min.css"/>
                <script type="text/javascript" src="../assets/js/core.js"></script>
                <script type="text/javascript" src="../assets/DataTables/datatables.min.js"></script>
                <script type="text/javascript" src="../assets/js/util.js"></script>
                <script>
                var oedd = 0;
                $(document).ready(function() {                 
                    const table = $(\'#example\').DataTable( {
                        columnDefs: [ {
                            orderable: false,
                            className: \'select-checkbox\',
                            targets:   0
                        } ],
                        select: {
                            style: \'multi\',
                            blurable: true,
                            selector: \'td:first-child\',
                        },
                        order: [[ 0, \'asc\' ]],
                        language: {
                            select: {
                                rows: {
                                    _: "你選擇 %d 列",
                                    0: "尚未選擇列",
                                    1: "你選擇 %d 列"
                                }
                            }
                        },
                        dom: \'Bfrtip\',
                        buttons: [
                            {
                                text: \'全部\',
                                action: function () {
                                    table.rows().select();
                                }
                            },
                            {
                                text: \'奇數/偶數\',
                                action: function () {
                                    table.rows().deselect();
                                    if(oedd === 0){
                                        for(var i=1;i<='.($item->GetNum($_SESSION['Member_Data'])/2).';i++){
                                            table.rows(i*2-2).select();
                                        }
                                        oedd = 1;
                                    }else{
                                        for(var i=1;i<='.($item->GetNum($_SESSION['Member_Data'])/2).';i++){
                                            table.rows(i*2-1).select();
                                        }
                                        oedd = 0;
                                    }
                                }
                            },
                            {
                                text: \'反向\',
                                action: function () {
                                    var rows = table.rows( { selected: true } )[0];
                                    if (Array.isArray(rows) && rows.length) {
                                        table.rows().select();
                                        table.rows(rows).deselect();
                                    }
                                }
                            },
                            {
                                text: \'取消\',
                                action: function () {
                                    table.rows().deselect();
                                }
                            },
                        ]
                    });
                    $(".dt-buttons").css({\'width\':\'auto\',\'display\':\'-webkit-box\'});
                    $(".dt-buttons").append(\'<span class="input-group" style="display: flex !important;"><div class="input-group-prepend"><button class="btn btn-primary" style="margin: 8px;margin-left: 0px;margin-right: 0px;" type="button" data-toggle="modal" data-target="#exampleModalScrollable">建立商品</button></div><form method="POST" onsubmit="return deleteall();" action="" class="input-group-append"><input type="hidden" id="select_rows" name="rows" value=""><input class="btn btn-danger" name="delete_all" id="deleted_select_item" style="margin: 8px;margin-left: 0px;" type="submit" value="刪除商品"></form></span>\');
                    $(".dt-button").css({\'min-width\':\'auto\',\'margin\':\'8px 8px 8px 0px\',\'text-overflow\':\'clip\'});
                    $(".dataTables_filter").css({\'margin\':\'8px\'});
                    setInterval(() => {
                        var count = table.rows( { selected: true } ).count();
                        var rows = table.rows( { selected: true } ).data();
                        var cache = "";
                        $("#deleted_select_item").click(function(){
                            for(var i=0;i<=count-1;i++){
                                if(i === count-1){
                                    cache = cache + rows[i][1];
                                }else{
                                    cache = cache + rows[i][1] + ",";
                                }
                            }
                            $("#select_rows").attr("value", cache);
                        });
                    }, 100);
                });
                </script>
                <title>嗨囉! '.$_SESSION['Member_Data']['store_name'].'</title>
            </head>
            <body>';
            if(@$module['menu'] === true) {include "router/object_menu2.html";}
            echo '
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">廠商代號</li>
                        <li class="breadcrumb-item"><a href="/vendor">'.$_SESSION['Member_Data']['store_code'].'</a></li>
                        <li class="breadcrumb-item active" aria-current="page">管理商品</li>
                    </ol>
                </nav>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <form action="" method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalScrollableTitle">建立商品</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="item_name">商品名稱</span>
                                    </div>
                                    <input type="text" class="form-control" name="item_name" placeholder="商品名稱" aria-describedby="item_name" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="item_dec">商品說明</span>
                                    </div>
                                    <input type="text" class="form-control" name="item_dec" placeholder="商品說明" aria-describedby="item_dec" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" name="item_price" aria-label="Amount (to the nearest dollar)" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text">
                                        <input type="checkbox" id="limititem" name="item_limitunit" aria-label="Checkbox for following text input">
                                        <span>&nbsp;數量限制</span>
                                      </div>
                                    </div>
                                    <input type="number" id="limititem_unit" name="item_unit" class="form-control" aria-label="Text input with checkbox">
                                    <script>                                    
                                        setInterval(() => {
                                            var checked = document.getElementById("limititem").checked;
                                            if(checked){
                                                $("#limititem_unit").attr(\'disabled\', false);
                                            }else{
                                                $("#limititem_unit").attr(\'disabled\', true);
                                            }
                                        }, 100);
                                    </script>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01">商品圖片</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" title="圖片會壓縮" onchange="readFile(this);" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                        <input type="hidden" name="images" id="images" value="">
                                        <label class="custom-file-label" for="inputGroupFile01">選擇圖片(建議：144px X 144px)</label>
                                    </div>
                                </div>
                                <div style="width:100%;height:300px;display:block;margin:auto;">
                                    <img class="input-group mb-3" id="blah" src="../assets/images/logo.png" />
                                </div>
                            </div>
                            <div class="modal-footer">
                              <input type="reset" class="btn btn-secondary" data-dismiss="modal" value="關閉">
                              <input type="submit" class="btn btn-primary" name="submit"  value="建立">
                            </div>
                        </form>
                    </div>
                </div>
                <table id="example" class="table table-hover table_order display table-bordered" style="width:100%">
                    <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">商品</th>
                            <th class="text-center" scope="col">圖片</th>
                            <th class="text-center" scope="col">說明</th>
                            <th class="text-center" scope="col">價錢</th>
                            <th class="text-center" scope="col">限制</th>
                            <th class="text-center" scope="col">啟用</th>
                            <th class="text-center" scope="col">建立</th>
                            <th class="text-center" scope="col">更新</th>
                            <th class="text-center" class="btn_actions" scope="col">動作</th>
                        </tr>
                    </thead>
                    <tbody>
                    ';
                    $item_list = $item->GetItem($_SESSION['Member_Data']);
                    for($i = 1; $i <= $item->GetNum($_SESSION['Member_Data']);$i++){
                            echo "
                            <tr>
                                <td></td>
                                <td class='text-center'>".$item_list[$i]['id']."</td>
                                <td class='text-center'>".$item_list[$i]['name']."</td>
                                <td class='text-center'><img src='".$item_list[$i]['image']."' width='32px' height='32px'</td>
                                <td class='text-center'>".$item_list[$i]['dec']."</td>
                                <td class='text-center'>$".$item_list[$i]['price']."</td>
                                <td class='text-center'>".item_limit((int)$item_list[$i]['unit'])."</td>
                                <td class='text-center'>".item_enable($item_list[$i]['enable'])."</td>
                                <td class='text-center'>".$item_list[$i]['created_time']."</td>
                                <td class='text-center'>".$item_list[$i]['updated_time']."</td>
                                <td class=\"text-center btn_actions\">
                                    <div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                                        <button type=\"button\" data-toggle=\"modal\" data-target=\"#edit_".$item_list[$i]['id']."\" class=\"btn btn-warning\">編輯</button>
                                        <button type=\"button\" data-toggle=\"modal\" data-target=\"#remove_".$item_list[$i]['id']."\" class=\"btn btn-danger\">刪除</a>
                                    </div>
                                </td>
                            </tr>
                            ";
                    }
                    echo '
                    </tbody>
                </table>';
                for($i = 1; $i <= (count($item_list));$i++){
                    echo "
                    <div class=\"modal fade\" id=\"remove_".$item_list[$i]['id']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\" aria-hidden=\"true\">
                      <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">
                        <div class=\"modal-content\">
                            <div class=\"modal-header\">
                              <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">刪除 <kbd>".$item_list[$i]['name']."</kbd> 商品</h5>
                              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                <span aria-hidden=\"true\">&times;</span>
                              </button>
                            </div>
                            <div class=\"modal-body\">
                                <div class=\"alert alert-danger\" role=\"alert\">
                                    <i class=\"fas fa-exclamation-circle\"></i>&nbsp;刪除後無法回復資料
                                </div>
                                你確定要刪除 ".$item_list[$i]['name']." ?
                            </div>
                            <form method=\"POST\" action=\"\" class=\"modal-footer\">
                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">取消</button>
                                <input type=\"hidden\" name=\"rows\" value=\"".$item_list[$i]['id']."\">
                                <input type=\"submit\" name=\"delete_all\" class=\"btn btn-danger\" value=\"刪除\">
                            </form>
                        </div>
                      </div>
                    </div>
                    ";
                }
                echo "
                ";
                for($i = 1; $i <= (count($item_list));$i++){
                    echo "
                    <script>
                        function checkForm".$item_list[$i]['id']."(_checkbox, _unit){
                            var checked = document.getElementById(_checkbox).value;
                            var unit = document.getElementById(_unit).value;
                            document.getElementById(_checkbox).setAttribute('name', 'item_limitunit');
                            document.getElementById(_unit).setAttribute('name', 'item_unit');
                            var em1 = document.getElementById(\"item_limitunit\");
                            var em2 = document.getElementById(\"item_unit\");
                            return true;
                        }
                    </script>
                    <div class=\"modal fade\" id=\"edit_".$item_list[$i]['id']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">
                        <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">
                            <form name=\"edit\" action=\"\" method=\"POST\" class=\"modal-content\" onsubmit=\"return checkForm".$item_list[$i]['id']."('limititem".$item_list[$i]['id']."', 'limititem_unit".$item_list[$i]['id']."');\">
                                <div class=\"modal-header\">
                                    <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">編輯 <kbd>".$item_list[$i]['name']."</kbd> 商品</h5>
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                </div>
                                <div class=\"modal-body\">
                                    <div class=\"alert alert-warning\" role=\"alert\">
                                        <i class=\"fas fa-exclamation-circle\"></i>&nbsp;編輯送出後無法回復資料
                                    </div>
                                    <div class=\"input-group mb-3\">
                                        <div class=\"input-group-prepend\">
                                            <span class=\"input-group-text\" id=\"basic-addon1\">商品名稱</span>
                                        </div>
                                        <input type=\"text\" class=\"form-control\" placeholder=\"商品名稱\" value='".$item_list[$i]['name']."' name='item_name' aria-label=\"item_name\" aria-describedby=\"basic-addon1\">
                                    </div>
                                    <div class=\"input-group mb-3\">
                                        <div class=\"input-group-prepend\">
                                            <span class=\"input-group-text\" id=\"basic-addon1\">商品介紹</span>
                                        </div>
                                        <input type=\"text\" class=\"form-control\" placeholder=\"商品介紹\" value=\"".$item_list[$i]['dec']."\" name='item_dec' aria-label=\"item_decx`\" aria-describedby=\"basic-addon1\">
                                    </div>
                                    <div class=\"input-group mb-3\">
                                        <div class=\"input-group-prepend\">
                                            <span class=\"input-group-text\">$</span>
                                        </div>
                                        <input type=\"text\" class=\"form-control\" name=\"item_price\" value=\"".$item_list[$i]['price']."\" aria-label=\"Amount (to the nearest dollar)\" required>
                                        <div class=\"input-group-append\">
                                            <span class=\"input-group-text\">.00</span>
                                        </div>
                                    </div>
                                    <div class=\"input-group mb-3\">
                                        <div class=\"input-group-prepend\">
                                          <div class=\"input-group-text\">
                                            <input type=\"checkbox\" id=\"limititem".$item_list[$i]['id']."\" name=\"item_limitunit".$item_list[$i]['id']."\" aria-label=\"Checkbox for following text input\">
                                            <span>&nbsp;數量限制</span>
                                          </div>
                                        </div>";
                                        if($item_list[$i]['unit'] === "-1"){
                                            echo "
                                            <input type=\"number\" id=\"limititem_unit".$item_list[$i]['id']."\" name=\"item_unit".$item_list[$i]['id']."\" class=\"form-control\" aria-label=\"Text input with checkbox\">    
                                            <script>                                    
                                                setInterval(() => {
                                                    var checked".$item_list[$i]['id']." = document.getElementById(\"limititem".$item_list[$i]['id']."\").checked;
                                                    if(checked".$item_list[$i]['id']."){
                                                        $(\"#limititem_unit".$item_list[$i]['id']."\").attr('disabled', false);
                                                    }else{
                                                        $(\"#limititem_unit".$item_list[$i]['id']."\").attr('disabled', true);
                                                    }
                                                }, 100);
                                            </script>";
                                        }else{
                                            echo "
                                            <input type=\"number\" id=\"limititem_unit".$item_list[$i]['id']."\" value=\"".$item_list[$i]['unit']."\" name=\"item_unit".$item_list[$i]['id']."\" class=\"form-control\" aria-label=\"Text input with checkbox\">
                                            <script>
                                            $(\"#limititem".$item_list[$i]['id']."\").attr('checked', true);                                   
                                            setInterval(() => {
                                                var checked".$item_list[$i]['id']." = document.getElementById(\"limititem".$item_list[$i]['id']."\").checked;
                                                if(checked".$item_list[$i]['id']."){
                                                    $(\"#limititem_unit".$item_list[$i]['id']."\").attr('disabled', false);
                                                }else{
                                                    $(\"#limititem_unit".$item_list[$i]['id']."\").attr('disabled', true);
                                                }
                                            }, 100);
                                            </script>
                                            ";
                                        }
                                        echo "
                                    </div>
                                    <div class=\"input-group mb-3\">
                                      <div class=\"input-group-prepend\">
                                        <div class=\"input-group-text\">
                                          <input type=\"checkbox\" name=\"enable\" aria-label=\"啟用商品\" ".enable($item_list[$i]['enable']).">
                                        </div>
                                      </div>
                                      <input type=\"text\" class=\"form-control\" value=\"啟用商品\" aria-label=\"啟用商品\" disabled>
                                    </div>
                                    <div class=\"input-group mb-3\">
                                        <div class=\"input-group-prepend\">
                                            <span class=\"input-group-text\" id=\"inputGroupFileAddon".$item_list[$i]['id']."\">商品圖片</span>
                                        </div>
                                        <div class=\"custom-file\">
                                            <input type=\"file\" title=\"圖片會壓縮\" onchange=\"readFile".$item_list[$i]['id']."(this);\" class=\"custom-file-input\" id=\"inputGroupFile".$item_list[$i]['id']."\" aria-describedby=\"inputGroupFileAddon".$item_list[$i]['id']."\">
                                            <input type=\"hidden\" name=\"images\" id=\"images".$item_list[$i]['id']."\" value=\"".$item_list[$i]['image']."\">
                                            <label class=\"custom-file-label\" for=\"inputGroupFile".$item_list[$i]['id']."\">選擇圖片(建議：144px X 144px)</label>
                                        </div>
                                    </div>
                                    <div style=\"width:100%;height:300px;display:block;margin:auto;\">
                                        <img class=\"input-group mb-3\" id=\"blah".$item_list[$i]['id']."\" src=\"".$item_list[$i]['image']."\" />
                                    </div>
                                    <script>
                                    function readFile".$item_list[$i]['id']."(obj) {
                                        var file = obj.files[0];
                                        //判斷型別是不是圖片 
                                    
                                        var reader = new FileReader();
                                        reader.readAsDataURL(file);
                                        reader.onload = function (e) {
                                            dealImage(this.result, {
                                                width: 144,
                                                height: 144
                                            }, function (base) {
                                                $('#blah".$item_list[$i]['id']."').attr('src', base);
                                                $('#images".$item_list[$i]['id']."').attr('value', base);
                                            });
                                        }
                                    }
                                    </script>
                                </div>
                            <div class=\"modal-footer\">
                                <input type=\"hidden\" class=\"form-control\" placeholder=\"商品ID\" value='".$item_list[$i]['id']."' name='item_id' aria-label=\"item_id\" aria-describedby=\"basic-addon1\">
                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">取消</button>
                                <input type=\"submit\" class=\"btn btn-primary\" name=\"edit\"  value=\"編輯\">
                            </div>
                            </form>
                        </div>
                    </div>
                    ";
                }
            echo '</div>
            ';    
            if(@$module['toast'] === true) {include "router/object_toast.php";}
            echo'
            </body>
            </html>';
        }
    }else{
        echo '你沒有權限查看此頁面';
        header('refresh:1;url="/"');
    }
?>