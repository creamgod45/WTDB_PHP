<?php 

if(@$_SESSION['webid_1']){unset($_SESSION['webid_1']);}

// PHP Mailer Config
$FORM = "無線二維條碼取餐APP";
$SUBJECT = "線上訂餐驗證信件";

if(@$_SESSION['cache_token'] != null && $_SESSION['cache_nick'] != null && $_SESSION['cache_email'] != null && $_SESSION['cache_number'] != null){
    $token = $_SESSION['cache_token'];
    $nickname = $_SESSION['cache_nick'];
    $email = $_SESSION['cache_email'];
    $number = $_SESSION['cache_number'];
}else{
    @$token = GetRandoom(5);
    @$nickname = $_POST['nick'];
    @$email = $_POST['email'];
    @$number = $_POST['number'];
}

$num = 1;
$phone = preg_replace('/[^0-9]/', '', $number);

$verify[1] = filter_var($email, FILTER_VALIDATE_EMAIL);
$verify[2] = strlen($phone) === 10;
$verify[3] = $number != null;
$verify[4] = $email != null;
$verify[5] = $nickname != null;

for ($i=1; $i <= 5; $i++) { 
    if($verify[$i] === true){
        $num++;
    }
}

if(@$_SESSION['webid_2'] == null && $num === 5){
    $_SESSION['webid_2'] = time();
    $_SESSION['cache_token'] = $token;
    $_SESSION['cache_nick'] = $nickname;
    $_SESSION['cache_email'] = $email;
    $_SESSION['cache_number'] = $number;
    $HTML = '
    <div id="card_frame" style="padding:20px;background: white;box-shadow: 0 5px 5px -3px rgba(0,0,0,.2), 0 8px 10px 1px rgba(0,0,0,.14), 0 3px 14px 2px rgba(0,0,0,.12); width: 350px;height: 100%;display: table;margin: auto;padding: 16px;border: 3px solid rgb(190, 2, 2);border-radius: 5px;">
        <div id="card_title" style="font-weight:bold;font-size: 22px;">訂單驗證信件</div>
        <p id="card_content" style="background: gray;color: white;padding: 8px;border-radius: 5px;">訂單驗證碼：'.$token.'</p>
        <small style="color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;position: relative;text-align: center;padding: .75rem 1.25rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: .25rem;box-sizing: border-box;display: block;">注意!本信件由系統自動發送<br>如果任何問題請至店家詢問(請勿回覆此信件發送人)。
    </small>    
    </div>
    ';
    $ALTHTML = "訂單驗證碼：'.$token.'\n注意!本信件由系統自動發送\n如果任何問題請至店家詢問(請勿回覆此信件發送人)。";
    include "mailer.php";
    header('refresh:0;url="/online_verify"');
}elseif(@$_POST['again'] != null && @$_SESSION['webid_2'] != null && $num === 5){
    $HTML = '
    <div id="card_frame" style="padding:20px;background: white;box-shadow: 0 5px 5px -3px rgba(0,0,0,.2), 0 8px 10px 1px rgba(0,0,0,.14), 0 3px 14px 2px rgba(0,0,0,.12); width: 350px;height: 100%;display: table;margin: auto;padding: 16px;border: 3px solid rgb(190, 2, 2);border-radius: 5px;">
        <div id="card_title" style="font-weight:bold;font-size: 22px;">訂單驗證信件</div>
        <p id="card_content" style="background: gray;color: white;padding: 8px;border-radius: 5px;">訂單驗證碼：'.$token.'</p>
        <small style="color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;position: relative;text-align: center;padding: .75rem 1.25rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: .25rem;box-sizing: border-box;display: block;">注意!本信件由系統自動發送<br>如果任何問題請至店家詢問(請勿回覆此信件發送人)。
    </small>    
    </div>
    ';
    $ALTHTML = "訂單驗證碼：'.$token.'\n注意!本信件由系統自動發送\n如果任何問題請至店家詢問(請勿回覆此信件發送人)。";
    include "mailer.php";
    header('refresh:0;url="/online_verify"');
}elseif(@$_SESSION['webid_2'] != null && @$_POST['submit'] != null && @$_POST['verify_code'] != null){
    if($_POST['verify_code'] == $_SESSION['cache_token']){
        echo '驗證成功';
        $_SESSION['cache_success'] = true;
        unset($_SESSION['webid_2']);
        unset($_SESSION['cache_token']);
        header('refresh:1;url="/online_order"');
    }else{
        echo '驗證失敗';
        header('refresh:0;url="/online_verify"');
    }
}elseif(@$_SESSION['cache_success'] != true){
    echo '
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
        <title>驗證信件</title>
    </head>
    <body>
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">選購餐點</li>
                <li class="breadcrumb-item">填寫資料</li>
                <li class="breadcrumb-item active" aria-current="page">驗證訂單</li>
              </ol>
            </nav>
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">驗證訂單</h5>
                <div class="card-text" style="display:flex;">
                    <form action="" method="post">
                        <div class="form-group row">
                          <label for="staticEmail" class="col-sm-2 col-form-label">電子信箱</label>
                          <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="'.$_SESSION['cache_email'].'">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputPassword" class="col-sm-2 col-form-label">驗證碼</label>
                          <div class="col-sm-10 input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">驗證碼</span>
                            </div>
                            <input type="text" class="form-control" name="verify_code" id="inputPassword" required>
                            <div class="input-group-append">
                                <input type="submit" class="btn btn-success" name="submit" value="驗證">
                            </div>
                          </div>
                        </div>
                    </form>
                </div>
                <form action="" method="post" style="display:block;margin:auto;">
                    <input type="submit" class="btn btn-secondary" name="again" value="再次發送">
                </form>
              </div>
            </div>
        </div>
    </body>
    </html>
    ';
}else{
    header('refresh:1;url="/online_order"');
}
?>