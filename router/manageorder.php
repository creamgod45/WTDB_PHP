<?php
    require "lib/auth.php";
    require "lib/order.php";
    $auth = new auth();
    $order = new order();
    if(@$auth->isMember($_SESSION['Member_Data'])){
        if(@$_POST['delete_all'] != null){
            $oldrows = explode(",", $_POST['rows']);
            $rows = array();
            for($i=0;$i<=count($oldrows)-1;$i++){
                $rows[$i+1] = $oldrows[$i];
            }
            $query = array();
            for($i=1;$i<=count($rows);$i++){
                $query[$i] = $order->RemoveOrder($_SESSION['Member_Data'], [
                    $rows[$i], GetDevice(), GetIP()
                ]);
                echo '訂單令牌:'.$rows[$i].'刪除完成'."<br>";
            }
            $ok = 0;
            for($i=1;$i<=count($query);$i++){
                if($query[$i] === true){
                    $ok++;
                }
            }
            if($ok === count($query)){
                echo '執行完成';
                header('refresh:1;url="/vendor/order"');
            }else{
                echo '執行失敗';
                var_dump($query);
            }
        }elseif(@$_POST['delete'] != null){
            $query = $order->SetOrder($_SESSION['Member_Data'], [
                'token' => $_POST['order_token'],
                'status' => 1,
                'device' => GetDevice(),
                'ip' => GetIP()
            ]);
            if($query){
                //@todo 修正取消訂單回復商品數量
                require "lib/item.php";
                $item = new item();
                $order_token = $_POST['order_token'];
                $object = $order->GetOnceOrder($order_token);
                $order_list = OrderSplitArray($object['order_content']);
                $order = array();
                foreach ($order_list as $key => $value) {
                    $temp = $item->item_translator($key);
                    $order[$temp] = $value;
                }
                $total = count($order);
                $ok = 0;
                foreach ($order as $key => $value) {
                    $result = $item->unitOBServer(['id'=>$key, 'unit'=>$value]);
                    switch ($result) {
                        case true:
                            $item->Scheduler_Controller($_SESSION['Member_Data'], ['id'=>$key, 'unit'=>$value, 'method'=>true]);
                            $ok++;
                        break;
                    }
                }
                if($ok === $total){
                    echo '取消訂單';
                }else{
                    echo '取消失敗';
                }
                header('refresh:1;url="/vendor/order"');
            }else{
                echo $query;
            }
        }elseif(@$_POST['done'] != null){
            $query = $order->SetOrder($_SESSION['Member_Data'], [
                'token' => $_POST['order_token'],
                'status' => 2,
                'device' => GetDevice(),
                'ip' => GetIP()
            ]);
            if($query){
                echo '完成訂單';
                header('refresh:1;url="/vendor/order"');
            }else{
                echo $query;
            }
        }elseif(@$_POST['verify'] != null){
            $item = $order->GetOnceOrder($_POST['order_token']);
            if($item['order_password'] === $_POST['password']){
                $query = $order->SetOrder($_SESSION['Member_Data'], [
                    'token' => $_POST['order_token'],
                    'status' => 3,
                    'device' => GetDevice(),
                    'ip' => GetIP()
                ]);
                if($query){
                    echo '密碼正確';
                    header('refresh:1;url="/vendor/order"');
                }else{
                    echo $query;
                }
            }else{
                echo '密碼錯誤';
                header('refresh:1;url="/vendor/order"');
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
                <link rel="stylesheet" type="text/css" href="../assets/DataTables/datatables.min.css"/>
                <link rel="stylesheet" type="text/css" href="../assets/css/app_system.css">
                <script type="text/javascript" src="../assets/js/core.js"></script>
                <script type="text/javascript" src="../assets/DataTables/datatables.min.js"></script>
                <script type="text/javascript" src="../assets/js/util.js"></script>
                <title>嗨囉! '.$_SESSION['Member_Data']['store_name'].'</title>
            </head>
            <script>
            var oedd = 0;
            $(document).ready(function() {                 
                const table = $(\'#example\').DataTable();
                $(".dt-buttons").css({\'width\':\'auto\',\'display\':\'-webkit-box\'});
                $(".dt-button").css({\'min-width\':\'auto\',\'margin\':\'8px 8px 8px 0px\',\'text-overflow\':\'clip\'});
                $(".dataTables_filter").css({\'margin\':\'8px\'});
            });
            </script>
            <body>';
            if(@$module['menu'] === true) {include "router/object_menu2.html";}
            echo '
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">廠商代號</li>
                        <li class="breadcrumb-item"><a href="/vendor">'.$_SESSION['Member_Data']['store_code'].'</a></li>
                        <li class="breadcrumb-item active" aria-current="page">管理訂單</li>
                    </ol>
                </nav>
                <table id="example" class="table table-hover table_order table-bordered">
                    <thead class="thead-dark valign-middle">
                        <tr>
                            <th valign="middle" scope="col"></th>
                            <th valign="middle" scope="col">訂單編號</th>
                            <th valign="middle" scope="col">商品</th>
                            <th valign="middle" scope="col">價錢</th>
                            <th valign="middle" scope="col">驗證</th>
                            <th valign="middle" scope="col">狀態</th>
                            <th valign="middle" scope="col">裝置</th>
                            <th valign="middle" scope="col">IP</th>
                            <th valign="middle" scope="col">啟用</th>
                            <th valign="middle" scope="col">建立</th>
                            <th valign="middle" scope="col">更新</th>
                            <th valign="middle" class="btn_action" scope="col">動作</th>
                        </tr>
                    </thead>
                    <tbody class="valign-middle">';
                        $order_list = $order->GetOrder($_SESSION['Member_Data']);
                        for($i = 1; $i<=$order->GetNum($_SESSION['Member_Data']);$i++){
                            echo '
                            <tr>
                                <td></td>
                                <td>'.$order_list[$i]['order_token'].'</td>
                                <td>'.OrderSplitInfo($order_list[$i]['order_content']).'</td>
                                <td>$'.$order_list[$i]['order_price'].'</td>
                                <td>'.order_verification($order_list[$i]['verification']).'</td>
                                <td>'.order_status($order_list[$i]['status']).'</td>
                                <td>'.$order_list[$i]['device'].'</td>
                                <td>'.$order_list[$i]['ip'].'</td>
                                <td>'.item_enable($order_list[$i]['enable']).'</td>
                                <td>'.$order_list[$i]['created_time'].'</td>
                                <td>'.$order_list[$i]['updated_time'].'</td>
                                <td class="text-center btn_actions">
                                    <div class="btn-group" role="group" aria-label="Basic example">';
                                        if($order_list[$i]['verification'] === "true"){
                                            echo '<button type="button" data-toggle="modal" data-target="#verify_'.$order_list[$i]['id'].'" class="btn btn-primary">驗證</button>';
                                        }elseif($order_list[$i]['verification'] === "ok" && $order_list[$i]['status'] === "wait"){
                                            echo '<button type="button" data-toggle="modal" data-target="#command_'.$order_list[$i]['id'].'" class="btn btn-primary">操作</button>';
                                        }else{
                                            echo '無';
                                        }
                                        echo '
                                    </div>
                                </td>
                            </tr>
                            ';
                        }
                        echo '
                    </tbody>
                </table>';
                
                for($i = 1; $i<=$order->GetNum($_SESSION['Member_Data']);$i++){
                    if($order_list[$i]['verification'] === "true"){
                        echo "
                        <div class=\"modal fade\" id=\"verify_".$order_list[$i]['id']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">
                            <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">
                                <form action=\"\" method=\"POST\" class=\"modal-content\">
                                    <div class=\"modal-header\">
                                        <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">驗證訂單</h5>
                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                            <span aria-hidden=\"true\">&times;</span>
                                        </button>
                                    </div>
                                    <div class=\"modal-body\">
                                        <div class=\"alert alert-warning\" role=\"alert\">
                                            <i class=\"fas fa-exclamation-circle\"></i>&nbsp;操作送出後無法回復資料
                                        </div>
                                        <div>訂單編號：".$order_list[$i]["order_token"]."</div>
                                        <div>商品：".OrderSplitInfo($order_list[$i]["order_content"])."</div>
                                        <div>價錢：$".$order_list[$i]["order_price"]."</div>
                                        <div>驗證：".order_verification($order_list[$i]["verification"])."</div>
                                        <div>狀態：".order_status($order_list[$i]["status"])."</div>
                                        <div>裝置：".$order_list[$i]["device"]."</div>
                                        <div>IP：".$order_list[$i]["ip"]."</div>
                                        <div>啟用：".item_enable($order_list[$i]["enable"])."</div>
                                        <div>建立：".$order_list[$i]["created_time"]."</div>
                                        <div>更新：".$order_list[$i]["updated_time"]."</div>
                                        <div class=\"input-group mb-3\">
                                          <div class=\"input-group-prepend\">
                                            <span class=\"input-group-text\" id=\"basic-addon1\">訂單密碼</span>
                                          </div>
                                          <input type=\"text\" class=\"form-control\" name=\"password\" placeholder=\"訂單密碼\" aria-label=\"訂單密碼\">
                                        </div>
                                    </div>
                                <div class=\"modal-footer\">
                                    <input type=\"hidden\" class=\"form-control\" value='".$order_list[$i]["order_token"]."' name='order_token'>
                                    <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">取消</button>
                                    <input type=\"submit\" class=\"btn btn-success\" name=\"verify\"  value=\"驗證訂單\">
                                </div>
                                </form>
                            </div>
                        </div>
                        ";
                    }
                }
                for($i = 1; $i <= $order->GetNum($_SESSION['Member_Data']);$i++){
                    echo "
                    <div class=\"modal fade\" id=\"command_".$order_list[$i]['id']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalScrollableTitle\" aria-hidden=\"true\">
                        <div class=\"modal-dialog modal-dialog-scrollable\" role=\"document\">
                            <form action=\"\" method=\"POST\" class=\"modal-content\">
                                <div class=\"modal-header\">
                                    <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">操作訂單</h5>
                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                </div>
                                <div class=\"modal-body\">
                                    <div class=\"alert alert-warning\" role=\"alert\">
                                        <i class=\"fas fa-exclamation-circle\"></i>&nbsp;操作送出後無法回復資料
                                    </div>
                                    <div>訂單編號：".$order_list[$i]["order_token"]."</div>
                                    <div>訂單密碼：".$order_list[$i]["order_password"]."</div>
                                    <div>商品：".OrderSplitInfo($order_list[$i]["order_content"])."</div>
                                    <div>價錢：$".$order_list[$i]["order_price"]."</div>
                                    <div>驗證：".order_verification($order_list[$i]["verification"])."</div>
                                    <div>狀態：".order_status($order_list[$i]["status"])."</div>
                                    <div>裝置：".$order_list[$i]["device"]."</div>
                                    <div>IP：".$order_list[$i]["ip"]."</div>
                                    <div>啟用：".item_enable($order_list[$i]["enable"])."</div>
                                    <div>建立：".$order_list[$i]["created_time"]."</div>
                                    <div>更新：".$order_list[$i]["updated_time"]."</div>
                                </div>
                            <div class=\"modal-footer\">
                                <input type=\"hidden\" class=\"form-control\" value='".$order_list[$i]["order_token"]."' name='order_token'>
                                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">取消</button>
                                <input type=\"submit\" class=\"btn btn-danger\" name=\"delete\"  value=\"取消訂單\">
                                <input type=\"submit\" class=\"btn btn-success\" name=\"done\"  value=\"完成訂單\">
                            </div>
                            </form>
                        </div>
                    </div>
                    ";
                }
                echo'
            </div>';
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