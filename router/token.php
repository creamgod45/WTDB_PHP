<?php 
    require "lib/order.php";
    $order = new order();
    $item = $order->GetOnceOrder($token);
    if($item && $item['verification'] === "false"){
        $_SESSION['cache_token'] = $token;
        $_SESSION['cache_item'] = $item;
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
            <script type="text/javascript" src="../assets/js/core.js"></script>
            <title>{"status":"verify"}</title>
        </head>
        <body>
            <div class="container">
                <div class="card" style="width: 18rem;display:table;margin:auto;">
                    <div class="card-body">
                        <h5 class="card-title">餐點內容</h5>
                        <p class="card-text">
                            '.OrderSplitInfo($item['order_content']).'
                            價錢：$'.$item['order_price'].'
                        </p>
                        <a href="/wait" class="btn btn-primary">確認訂單</a>
                        <a href="/cancel_orders" class="btn btn-primary">取消訂單</a>
                    </div>
                </div>
            </div>
        </body>
        <script>
            setInterval(() => {
                $.getJSON("/order/'.$token.'", function (result) {
                    var verification = result.verification;
                    if (verification　=== "true"){document.location.href = "/order_done";}
                    var status = result.status;
                    if (status　=== "false"){document.location.href = "/cancel_order";}
                });
            }, 1000);
        </script>
        </html>
        ';
    }elseif($item['verification'] === "cance"){
        header('refresh:0;url="/cancel_orders"');
    }else{
        // 防止回來
        header('refresh:0;url="/wait"');
    }
?>