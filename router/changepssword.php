<?php
    require "lib/auth.php";
    $auth = new auth();
    if(@$auth->isMember($_SESSION['Member_Data']) and @$_POST['submit'] != null){
        $oldpw = $_POST['oldpassword'];
        $pw = $_POST['password'];
        $repw = $_POST['repassword'];
        if($pw === $repw){
            if(@$auth->changepw($_SESSION['Member_Data'], $oldpw, $pw, [GetDevice(),GetIP()])){
                echo '密碼更改成功';
                header('refresh:1;url="/vendor/logout"');
            }else{
                echo '密碼更改失敗';
                header('refresh:1;url="/"');
            }
        }
    }elseif(@$auth->isMember($_SESSION['Member_Data'])){
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
                    <li class="breadcrumb-item active" aria-current="page">更改密碼</li>
                </ol>
            </nav>
            <div class="row">
                <form action="" method="POST" class="col vendor_info">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">原本密碼</span>
                        </div>
                        <input type="password" class="form-control" name="oldpassword" placeholder="Password"
                            aria-label="password" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">新的密碼</span>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="Password"
                            aria-label="password" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">重複密碼</span>
                        </div>
                        <input type="password" class="form-control" name="repassword" placeholder="Password"
                            aria-label="password" aria-describedby="basic-addon1" required>
                        <div class="input-group-append">
                            <input class="btn btn-outline-secondary" name="submit" type="submit" value="更改密碼">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </body>
        </html>';
    }else{
        echo '你沒有權限查看此頁面';
        header('refresh:1;url="/"');
    }
?>