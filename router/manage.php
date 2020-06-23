<?php
    require "lib/auth.php";
    require "lib/item.php";
    require "lib/order.php";
    $auth = new auth();
    $item = new item();
    $order = new order();
    if(@$auth->isMember($_SESSION['Member_Data']) and @$_POST['submit'] != null){
        if(@$_SESSION['webid'] === null and $_POST['order'] != ""){
            $_SESSION['webid'] = time();
            $content = "";
            $total_money = 0;
            $orderlist = $_POST['order'];
            $order_item = OrderSplitArray($orderlist);
            $total = count($order_item);
            $ok = 0;
            foreach ($order_item as $key => $value) {
                $result = $item->unitOBServer(['id'=>$key, 'unit'=>$value]);
                switch ($result) {
                    case true:
                        $item->Scheduler_Controller($_SESSION['Member_Data'], ['id'=>$key, 'unit'=>$value]);
                        $ok++;
                    break;
                }
            }
            if($ok === $total){
                foreach ($order_item as $key => $value) {
                    $item_total = $value;
                    $item_result = $item->GetOnceItem($key);
                    $content .= "/".$item_result['name'].":".$value;
                    $total_money += $item_result['price'] * $value;
                }
                $pas = GetRandoom(5);
                $query = $order->CreateOrder($_SESSION['Member_Data'], [
                    'content' => $content,
                    'price' => $total_money,
                    'device' => GetDevice(),
                    'ip' => GetIP(),
                    'password' => $pas
                ]);
                if($query){
                    $_SESSION['cache_qrcode_path'] = $order->qrcode($pas);
                    $_SESSION['cache_content'] = $content;
                    $_SESSION['cache_keycontent'] = $orderlist;
                    $_SESSION['cache_total_money'] = $total_money;
                    echo $_SESSION['cache_token'];
                    echo '
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
                        <script type="text/javascript" src="../assets/js/core.js"></script>
                        <title>QRCODE</title>
                    </head>
                    <body>
                        <div class="container">
                            <div class="card" style="width: 18rem;display:table;margin:auto;">
                                <img src="'.$_SESSION['cache_qrcode_path'].'" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">餐點內容</h5>
                                    <p class="card-text">'.OrderSplitInfo($_SESSION['cache_content'])."價錢：$".$_SESSION['cache_total_money'].'</p>
                                    <a href="/cancel_order" class="btn btn-primary">取消訂單</a>
                                </div>
                            </div>
                        </div>
                    </body>
                    <script>
                        setInterval(() => {
                            $.getJSON("/order/'.$_SESSION['cache_token'].'", function (result) {
                                var verification = result.verification;
                                if (verification　=== "true"){document.location.href = "/order_done";}
                                var status = result.status;
                                if (status　=== "false"){document.location.href = "/cancel_order";}
                            });
                        }, 1000);
                    </script>
                    </html>
                    ';
                }else{
                    echo "建立失敗";
                    echo $query;
                }
            }
        }elseif(@$_SESSION['webid'] != null){
            order:
            echo $_SESSION['cache_token'];
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
                <script type="text/javascript" src="../assets/js/core.js"></script>
                <title>QRCODE</title>
            </head>
            <body>
                <div class="container">
                    <div class="card" style="width: 18rem;display:table;margin:auto;">
                        <img src="'.$_SESSION['cache_qrcode_path'].'" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">餐點內容</h5>
                            <p class="card-text">'.OrderSplitInfo($_SESSION['cache_content'])."價錢：$".$_SESSION['cache_total_money'].'</p>
                            <a href="/cancel_order" class="btn btn-primary">取消訂單</a>
                        </div>
                    </div>
                </div>
            </body>
            <script>
                setInterval(() => {
                    $.getJSON("/order/'.$_SESSION['cache_token'].'", function (result) {
                        var verification = result.verification;
                        if (verification　=== "true"){document.location.href = "/order_done";}
                        var status = result.status;
                        if (status　=== "false"){document.location.href = "/cancel_order";}
                    });
                }, 1000);
            </script>
            </html>
            ';
        }
    }elseif(@$auth->isMember($_SESSION['Member_Data'])){
        if(isset($_SESSION['webid'])){goto order;}
        $_SESSION['cache_js_member'] = $_SESSION['Member_Data'];
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
            <link rel="stylesheet" type="text/css" href="../assets/css/app_system.css">
            <script type="text/javascript" src="../assets/js/core.js"></script>
            <script type="text/javascript" src="../assets/js/util.js"></script>
            <script type="text/javascript" async src="../assets/js/app.php"></script>
            <title>嗨囉! '.$_SESSION['Member_Data']['store_name'].'</title>
        </head>
        <body>';
        if(@$module['menu'] === true) {include "router/object_menu2.html";}
        echo '
        <div class="container">
            <div class="row">
                <div class="col-sm l">
                    <div class="item_list">';
                        $item_list = $item->GetItem($_SESSION['Member_Data']);
                        if($item->GetNum($_SESSION['Member_Data']) === 0){
                            echo '<h1 class="mt-0 mj">目前沒有任何商品</h1>';
                        }else{
                            for($i = 1; $i <= $item->GetNum($_SESSION['Member_Data']);$i++){
                                if((int)$item_list[$i]['unit'] != 0){
                                    echo '
                                    <div class="item" class="media position-relative"> <img class="mr-3" height="144px" width="144px" src="'.$item_list[$i]['image'].'">
                                        <div class="media-body">
                                            <h5 class="mt-0 mj">'.$item_list[$i]['name'].' <kbd>$'.$item_list[$i]['price'].'</kbd> <small>'.item_limit((int)$item_list[$i]['unit']).'</small></h5>
                                            <p class="JQellipsis">'.$item_list[$i]['dec'].'</p>
                                            <div class="btn-group float-right" role="group" aria-label="Basic example">
                                                <button id="add_'.$item_list[$i]['iid'].'" type="button" class="btn btn-outline-primary mj" onclick="add_order(\''.$item_list[$i]['iid'].'\')">添加</button>
                                                <button id="less_'.$item_list[$i]['iid'].'" type="button" class="btn btn-outline-danger mj" onclick="less_order(\''.$item_list[$i]['iid'].'\')">減少</button>
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                }
                            }                            
                        }
                    echo '</div>
                </div>
                <div class="col-sm r">
                    <div
                        style="border-bottom-right-radius: 20px;border-bottom-left-radius: 20px;border-top-right-radius: 20px;border-top-left-radius: 20px;box-shadow: 0 6px 7px -4px rgba(0,0,0,.2), 0 11px 15px 1px rgba(0,0,0,.14), 0 4px 20px 3px rgba(0,0,0,.12);">
                        <div class="row" style="display: flex;">
                            <div class="col-sm">
                                <div id="title_text_vhor_left" class="float-left mj">
                                    <div id="main">
                                        <div class="h4"><strong><i class="fas fa-receipt"></i>&nbsp;發票明細</strong></div
                                            class="h4">
                                        <div>
                                            <p class="sui"><i class="far fa-clock"></i>&nbsp;2020/01/12</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="title_text_vhor_right" class="float-right mj">
                                    <div id="main" class="pt h5">#'.floor(rand(1,time()) * 0.01).'</div>
                                </div>
                            </div>
                        </div>
                        <div class="tables">
                            <table class="table table-borderless table-hover" style="border-bottom: dashed 2px gray;">
                                <thead style="border-bottom: dashed 2px gray;">
                                    <tr>
                                        <th class="times" scope="col"></th>
                                        <th class="frist" scope="col">產品</th>
                                        <th class="second" scope="col">數量</th>
                                        <th class="third" scope="col">價錢</th>
                                    </tr>
                                </thead>
                                <tbody class="order_item">
                                    <tr id="total" class="table-info" style="border-top: solid 2px gray;">
                                        <td class="times"></td>
                                        <td class="frist">Total</td>
                                        <td id="count_unit" class="second">0</td>
                                        <td id="count_money" class="third">$0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="footer">
                            <div id="main" style="padding-bottom: 72px;">
                                <form action="" method="post" onsubmit="return orderchecker();">
                                    <input type="hidden" id="orderlist" name="order">
                                    <input type="submit" name="submit"
                                        class="float-right mj btn btn-outline-success" style="margin-left:8px;" value="結帳訂單">
                                    <button type="reset" class="float-right mj btn btn-outline-dark" onclick="location.reload();">重置</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        if(@$module['toast'] === true) {include "router/object_toast.php";}
        echo'
        </body>
        </html>';
    }else{
        echo '你沒有權限查看此頁面';
        header('refresh:1;url="/"');
    }
?>