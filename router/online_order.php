<?php
	require_once "lib/auth.php";
	require_once "lib/order.php";
	require_once "lib/item.php";
	$auth = new auth();
	$item = new item();
    $order = new order();

    function OrderSplitInfos($str){
		$String = "";
		$tmp_object = explode("/", $str);
		$Num = count($tmp_object)-1; // 0 = ""
		for($i=1;$i<=$Num;$i++){
			$String .= str_replace(":", " &times; ", "<li>".$tmp_object[$i])."</li>";
		}
		return $String;
	}
    
    if($_SESSION['cache_success']){
        $nickname = $_SESSION['cache_nick'];
        $email = $_SESSION['cache_email'];
        $phone = $_SESSION['cache_number'];
        $orderlist = $_SESSION['cache_orderinfo'];
        $price = $_SESSION['cache_price'];
        $total = count($orderlist);
        $execute = 0;
        foreach ($orderlist as $key => $value) {
            $result = $item->unitOBServer(['id'=>$key, 'unit'=>$value]);
            switch ($result) {
                case true:
                    $item->Scheduler_Controller(['access_token' => $_SESSION['cahce_vendor']], ['id'=>$key, 'unit'=>$value]);
                    $execute++;
                break;
            }
        }
        if($execute === $total and @$_SESSION['webid_3'] == null){
            $_SESSION['webid_3'] = time();
            // 成功
            $content = "";
            $total_money = 0;
            foreach ($orderlist as $key => $value) {
                $item_result = $item->GetOnceItem($key);
                $content .= "/".$item_result['name'].":".$value;
                $total_money += $item_result['price'] * $value;
            }
            $content = $_SESSION['cache_nick'] . ":" . $_SESSION['cache_email'] . ":" . $_SESSION['cache_number'] . ":" . $content;
            $error = false;
            $pas = GetRandoom(5);
            $query = $order->CreateOrder(['access_token' => $_SESSION['cahce_vendor']], [
                'content' => $content,
                'price' => $total_money,
                'device' => GetDevice(),
                'ip' => GetIP(),
                'password' => $pas
            ]);
            if($query){
                $query = $order->SetOrder(['access_token' => $_SESSION['cahce_vendor']], [
                    'token' => $_SESSION['cache_token'],
                    'status' => 0,
                    'device' => GetDevice(),
                    'ip' => GetIP()
                ]);
                if($query){
                    $item = $order->GetOnceOrder($_SESSION['cache_token']);
                    $_SESSION['cache_password'] = $pas;
                    $_SESSION['cache_item'] = $item;
                }else{
                    die($query);
                }
            }else{
                die($query);
            }
        }elseif($_SESSION['webid_3'] != null){
            $pas = $_SESSION['cache_password'];
            $content = "";
            $total_money = 0;
            $query = true;
            foreach ($orderlist as $key => $value) {
                $item_result = $item->GetOnceItem($key);
                $content .= "/".$item_result['name'].":".$value;
                $total_money += $item_result['price'] * $value;
            }
            $error = false;
        }else{
            // 失敗
            $error = true;
        }
    }else{
        header('refresh:1;url="/online"');
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/initialize.min.css">
        <script type="text/javascript" src="../assets/js/core.js"></script>
        <title>建立訂單</title>
    </head>
    <body>
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">填寫資料</li>
                <li class="breadcrumb-item">驗證訂單</li>
                <li class="breadcrumb-item active" aria-current="page">建立訂單</li>
              </ol>
            </nav>
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">訂單明細</h5>
            <?php
                if($query){
                    echo '<div class="alert alert-success" role="alert">建立成功</div>';
                }else {
                    echo '<div class="alert alert-danger" role="alert">建立失敗'.$query.'</div>';
                }
                if(@$error){
                    echo '
                        <div class="card-text">
                            <div class="alert alert-danger" role="alert">
                                偵測到訂單細節與資料庫不符
                            </div>
                        </div>
                    ';
                    header('refresh:1;url="/online"');
                }else {
                    echo '
                        <div class="card-text">
                            <div>餐點內容：'.OrderSplitInfos($content).'</div>
                            <div>餐點金額：$'.$total_money.'</div>
                            <div>訂單密碼：<h1>'.$pas.'</h1></div>
                        </div>
                    ';                    
                }
            ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        setInterval(() => {
            $.getJSON("/order/<?php echo $_SESSION['cache_token']; ?>", function (result) {
                var status = result.verification;
                if (status  === "ok"){document.location.href = "/wait_order";}
                if (result.status　=== "false"){document.location.href = "/cancel_orders";}
            });
        }, 100);
    </script>
    </html>