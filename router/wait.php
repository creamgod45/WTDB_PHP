<?php 
    require "lib/order.php";
    $order = new order();
    if(!isset($_SESSION['webids'])){
        $_SESSION['webids'] = time();
        $order->SetOrder($_SESSION['cache_item'], [
            'token' => $_SESSION['cache_token'],
            'status' => 0,
            'device' => GetDevice(),
            'ip' => GetIP()
        ]);
    }
    $token = $_SESSION['cache_token'];
    $item = $order->GetOnceOrder($token);
    if($item && $item['verification'] != "ok"){
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
            <link rel="stylesheet" type="text/css" href="../assets/css/app_system.css">
            <script type="text/javascript" src="../assets/js/core.js"></script>
            <title>{"status":"wait"}</title>
        </head>
        <body>
            <div class="container">
                <div class="card" style="width: 18rem;display:table;margin:auto;">
                    <div class="card-body">
                        <h5 class="card-title">餐點內容</h5>
                        <p class="card-text">
                            '.OrderSplitInfo($item['order_content']).'
                            價錢：$'.$item['order_price'].'<br>
                            訂單密碼：<h2 class="pt">'.$item['order_password'].'</h2><font color="red">(請把訂單密碼告訴店員)</font>
                        </p>
                    </div>
                </div>
            </div>
        </body>
        <script>
            setInterval(() => {
                $.getJSON("/order/'.$_SESSION['cache_token'].'", function (result) {
                    var status = result.verification;
                    if (status  === "ok"){document.location.href = "/wait_order";}
                    if (status　=== "false"){document.location.href = "/cancel_orders";}
                });
            }, 100);
        </script>
        </html>
        ';
    }
?>