<?php 
    require "lib/auth.php";
    $auth = new auth();
    if(@$auth->isMember($_SESSION['Member_Data'])){
        if(@$auth->isAdministrator($_SESSION['Member_Data'])){
            echo '你已經登入了';
            header('refresh:1;url="/admin/index"');
        }else{
            echo '你沒有權限查看此頁面';
            header('refresh:1;url="/manage"');
        }
    }else{
        if(@$_POST['submit'] == null){
            echo '<!doctype html>
            <html>';
            $title="登入"; include "cms/object_header.php";
            echo'<body class="app">
            <?php include "cms/object_loader.php";?>
            <div class="peers ai-s fxw-nw h-100vh">
                <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv"
                    style="background-image:url(../assets/static/images/bg.jpg)">
                    <div class="pos-a centerXY">
                        <div class="bgc-white bdrs-50p pos-r" style="width:120px;height:120px"><img style="border-radius: 10%;"
                                width="70px" height="70px" class="pos-a centerXY" src="../assets/images/logo.png" alt=""></div>
                    </div>
                </div>
                <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width:320px">
                    <h4 class="fw-400 c-grey-900 mB-40">Login / 登入 <b>後台管理系統</b></h4>
                    <form action="" method="POST">
                        <div class="form-group"><label class="text-normal text-dark">Username / 帳號</label> <input type="text"
                                class="form-control" name="code" placeholder="請輸入帳號" required></div>
                        <div class="form-group"><label class="text-normal text-dark">Password / 密碼</label> <input
                                type="password" name="password" class="form-control" placeholder="請輸入密碼" required></div>
                        <div class="form-group">
                            <div class="peers ai-c jc-sb fxw-nw">
                                <div class="peer"></div>
                                <div class="peer"><input type="submit" name="submit" class="btn btn-primary" value="Login"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script type="text/javascript" src="../assets/js/vendor.js"></script>
            <script type="text/javascript" src="../assets/js/bundle.js"></script>
            </body>
            </html>';
        }else{
            $name = $_POST['code'];
            $password =  $_POST['password'];
            if($name != null and $password != null){
                $auth->Auths($name, $password, GetDevice(), GetIP());
                if(@$_SESSION['Member_Data']){
                    echo '登入成功';
                    header('refresh:1;url="/admin/index"');
                }else{
                    echo '登入失敗(帳號或密碼其中一項錯誤)';
                }
            }else{
                if($name != null){
                    echo '抱歉!!帳號不能為空';
                }
                if($password != null){
                    echo '抱歉!!密碼不能為空';
                }
            }
        }
    }
?>