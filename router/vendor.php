<?php
    require_once "lib/auth.php";
    require_once "lib/order.php";
    $auth = new auth();
    $order = new order();
    if(@$auth->isMember($_SESSION['Member_Data'])){
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
            <script type="text/javascript" src="../assets/js/core.js"></script>
            <script type="text/javascript" src="../assets/js/util.js"></script>
            <title>嗨囉! '.$_SESSION['Member_Data']['store_name'].'</title>
        </head>
        <body>';
        if(@$module['menu'] === true) {include "router/object_menu2.html";}
        echo '
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">廠商代號</li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="/vendor">'.$_SESSION['Member_Data']['store_code'].'</a></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-sm vendor_logo" style="margin-top: 32px;">
                    <div class="img_border">
                        <img style="border-radius: 5px;" width="256px" height="256px" src="../assets/images/logo.png">
                    </div>
                </div>
                <div class="col vendor_info">
                    <div class="btn-group" style="padding: 32px 0px 32px 0px;display: table; margin:auto;" role="group" aria-label="Basic example">
                        <a class="btn btn-secondary" href="/vendor/order"><i class="fas fa-tasks"></i>&nbsp;管理訂單</a>
                        <a class="btn btn-secondary" href="/vendor/item"><i class="fas fa-tasks"></i>&nbsp;管理商品</a>
                        <a class="btn btn-secondary" href="/vendor/log"><i class="fas fa-history"></i>&nbsp;登入紀錄</a>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">店家名稱</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="basic-addon1" value="'.$_SESSION['Member_Data']['store_name'].'" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">店家代號</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="basic-addon1" value="'.$_SESSION['Member_Data']['store_code'].'" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">店家密碼</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="basic-addon1" value="********" disabled>
                        <div class="input-group-append">
                        <a class="btn btn-outline-secondary" href="/vendor/changepassword">更改密碼</a>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">訂單數量</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="basic-addon1" value="'.$order->GetNum($_SESSION['Member_Data']).'" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">建立時間</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="basic-addon1" value="'.$_SESSION['Member_Data']['created_time'].'" disabled>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">更新時間</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="basic-addon1" value="'.$_SESSION['Member_Data']['updated_time'].'" disabled>
                    </div>
                </div>
            </div>
        </div>';
        if(@$module['toast'] === true) {include "router/object_toast.php";}
        echo '
        </body>
        </html>';
    }else{
        echo '你沒有權限查看此頁面';
        header('refresh:1;url="/"');
    }
?>