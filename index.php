<?php 
    // Config 
    date_default_timezone_set("Asia/Taipei");
    session_start();

    // Loader
    include "lib/encrypt.php";
    include "lib/router.php";
    include "lib/plugins.php";

    // Main
    switch (@router(1)) {
        case '':
        case 'index':
            include "router/index.php";
        break;
        case 'manage':
            $module['menu'] = true;
            $module['toast'] = true;
            include "router/manage.php";
        break;
        case 'order':
            include "router/order.php";
        break;
        case 'item':
            include "router/item.php";
        break;
        case 'online':
            include "router/online.php";
        break;
        case 'online_vendor':
            include "router/online_vendor.php";
        break;
        case 'online_verify':
            include "router/online_verify.php";
        break;
        case 'online_order':
            include "router/online_order.php";
        break;
        case 'order_done':
            echo '<h1>訂單確認</h1>';
            @unlink('.'.$_SESSION['cache_qrcode_path']);
            unset($_SESSION['cache_content']);
            unset($_SESSION['cache_qrcode_path']);
            unset($_SESSION['cache_total_money']);
            unset($_SESSION['cache_token']);
            unset($_SESSION['webid']);
            header('refresh:0;url="/vendor/order"');
        break;
        case 'cancel_order':
            echo '<h1>取消訂單</h1>';
            require_once "lib/order.php";
            require_once "lib/item.php";
            $order = new order();
            $item = new item();
            $query = $order->SetOrder($_SESSION['Member_Data'], [
                'token' => $_SESSION['cache_token'],
                'status' => 1,
                'device' => GetDevice(),
                'ip' => GetIP()
            ]);
            $orderlist = $_SESSION["cache_keycontent"];
            $order_item = OrderSplitArray($orderlist);
            $total = count($order_item);
            $ok = 0;
            foreach ($order_item as $key => $value) {
                $result = $item->unitOBServer(['id'=>$key, 'unit'=>$value, 'method'=>true]);
                var_dump($result);
                switch ($result) {
                    case true:
                        $item->Scheduler_Controller($_SESSION['Member_Data'], ['id'=>$key, 'unit'=>$value, 'method' => true]);
                        $ok++;
                    break;
                }
            }
            if($ok === $total){
                @unlink('.'.$_SESSION['cache_qrcode_path']);
                unset($_SESSION['cache_qrcode_path']);
                unset($_SESSION['cache_content']);
                unset($_SESSION['cache_total_money']);
                unset($_SESSION['cache_token']);
                unset($_SESSION['cache_keycontent']);
                unset($_SESSION['webid']);
                header('refresh:1;url="/vendor/order"');
            }
        break;
        case 'cancel_orders':
            // 顧客
            require_once "lib/order.php";
            $order = new order();
            @$query = $order->SetOrder($_SESSION['cache_item'], [
                'token' => $_SESSION['cache_token'],
                'status' => 1,
                'device' => GetDevice(),
                'ip' => GetIP()
            ]);
            if($query){
                echo '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /><title>{"status":"cancel"}</title>';
                echo '<h1>取消訂單</h1>';
                unset($_SESSION['cache_item']);
                unset($_SESSION['cache_token']);
                unset($_SESSION['cache_keycontent']);
            }
            session_destroy();
        break;
        case "finish":  
            echo '            
            <head>
                <meta charset="UTF-8">
                <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
                <link rel="stylesheet" type="text/css" href="../assets/css/app_system.css">
                <title>{"status":"finish"}</title>
            </head>
            <div class="container">
                <div class="card" style="width: 18rem;display:table;margin:auto;">
                    <div class="card-body">
                        <h3>訂單完成</h3>
                        <p class="card-text">
                            識別碼：'.$_SESSION['cache_item']['order_token'].'<br>
                            餐點內容：<br>'.OrderSplitInfo($_SESSION['cache_item']['order_content']).'
                            價錢：$'.$_SESSION['cache_item']['order_price'].'<br>
                        </p>
                    </div>
                </div>
            </div>';
            unset($_SESSION['cache_keycontent']);
            session_destroy();
        break;
        case 'wait':
            include 'router/wait.php';
        break;
        case 'wait_order':
            include "router/wait_order.php";
        break;
        case 'token':
            switch (@router(2)) {
                case '':
                    include "cms/404s.php";
                break;
                case 'null':
                    echo '歡迎使用無線二維條碼取餐APP<br>你可以點擊上方的按鈕新增訂單';
                break;
                default:
                    $token = router(2);
                    include "router/token.php";
                break;
            }
        break;
        case 'vendor':
            $module['menu'] = true;
            $module['toast'] = true;
            switch (@router(2)) {
                case '':
                    header('refresh:0;url="/vendor/index"');
                break;
                case 'index':
                    include "router/vendor.php";
                break;
                case 'item':
                    include "router/manageitem.php";
                break;
                case 'order':
                    include "router/manageorder.php";
                break;
                case 'changepassword':
                    include "router/changepssword.php";
                break;
                case 'message':
                    include "router/message.php";
                break;
                case 'log':
                    include "router/log.php";
                break;
                case 'logout':
                    require "lib/auth.php";
                    $auth = new auth();
                    $auth->logout($_SESSION['Member_Data'], GetIP());
                    echo '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />';
                    echo '登出';
                    session_destroy();
                    header('refresh:0;url="/"');
                break;
                default: header('refresh:0;url="../vendor"'); break;
            }
        break;
        case 'admin':
            switch (@router(2)) {
                case '':
                    header('refresh:0;url="/admin/index"');
                break;
                case 'index':
                    include "cms/index.php";
                break;
                case 'login':
                    include "cms/signin.php";
                break;
                case 'order':
                    include "cms/order.php";
                break;
                case 'item':
                    include "cms/item.php";
                break;
                case 'log':
                    include "cms/log.php";
                break;
                case 'member':
                    include "cms/member.php";
                break;
                case 'logout':
                    echo '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />';
                    require "lib/auth.php";
                    $auth = new auth();
                    $auth->logout($_SESSION['Member_Data'], GetIP());
                    echo '登出';
                    session_destroy();
                    header('refresh:0;url="/"');
                break;
                default: 
                    include "cms/404.php";
                break;
            }
        break;
        default:
            $title = "404 未找到網頁文件";
            include "cms/404s.php";
        break;
    }
?>