<?php
    require_once "lib/auth.php";
    require_once "lib/item.php";
    $auth = new auth();
    $item = new item();

    if(@$auth->isAdministrator($_SESSION['Member_Data'])){
        if(@$_POST['edit'] != null){
            $access_token = $_POST['access_token'];
            if(@$_POST['enable']==="on"){$enable = "true";}else{$enable = "false";}
            if(@$_POST['admin']==="on"){$admin = "true";}else{$admin = "false";}
            @$query = $auth->SetMember($access_token,[
                'administrator' => "$admin",
                'enable' => $enable,
                'device' => GetDevice(),
                'ip' => GetIP()
            ]);
            if($query){
                echo $query;
                header('refresh:1;url="/admin/member"');
            }else{
                echo '新增失敗';
                header('refresh:1;url="/admin/member"');
            }
        }elseif(@$_POST['remove'] != null){
            $access_token = $_POST['access_token'];
            $auth->RemoveMember($access_token, [
                'device' => GetDevice(),
                'ip' => GetIP()
            ]);
        }elseif(@$_POST['create'] != null){
            if(@$_POST['enable']==="on"){$enable = "true";}else{$enable = "false";}
            if(@$_POST['admin']==="on"){$admin = "true";}else{$admin = "false";}
            if($_POST['store_password'] === $_POST['store_repassword']){
                $query = $auth->AddMember([
                    'name' => $_POST['store_name'],
                    'code' => $_POST['store_username'],
                    'password' => $_POST['store_password'],
                    'email' => $_POST['store_email'],
                    'administrator' => $admin,
                    'enable' => $enable,
                ]);
                if($query){
                    echo "<h1>新增成功</h1>";
                    header('refresh:1;url="/admin/member"');
                }else{
                    echo "<h1>新增失敗</h1>";
                    header('refresh:1;url="/admin/member"');
                }
            }
        }else{
            echo '
            <!doctype html>
            <html>';
            $title = "管理會員"; include "cms/object_header.php";
            echo '
            <body class="app">';
                include "cms/object_loader.php";
                echo '
                <div>';
                    include "cms/object_menu.php";
                    include "cms/object_navbar.php";
                    echo '
                    <main class="main-content bgc-grey-100">
                        <div id="mainContent">
                            <div class="container-fluid">
                                <h4 class="c-grey-900 mT-10 mB-30">會員資料</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                                            <div class="c-grey-900 mB-20">
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create">建立會員</button>
                                            </div>
                                            <table id="example" class="table table-striped table-bordered" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>圖片</th>
                                                        <th>授權令牌</th>
                                                        <th>商店名稱</th>
                                                        <th>商店信箱</th>
                                                        <th width="8%">管理員</th>
                                                        <th width="8%">啟用</th>
                                                        <th>建立時間</th>
                                                        <th>更新時間</th>
                                                        <th width="100%" style="display:flex">操作</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                ';
                                                $item_list = $auth->GetMember_array();
                                                for($i = 1; $i <= $auth->GetNum($_SESSION['Member_Data']);$i++){
                                                    echo '
                                                    <tr>
                                                        <th><img width="32px" src="'.$item_list[$i]['store_image'].'"></th>
                                                        <th>'.$item_list[$i]['access_token'].'</th>
                                                        <th>'.$item_list[$i]['store_name'].'</th>
                                                        <th>'.$item_list[$i]['store_email'].'</th>
                                                        <th>'.item_enable($item_list[$i]['administrator']).'</th>
                                                        <th>'.item_enable($item_list[$i]['enable']).'</th>
                                                        <th>'.$item_list[$i]['created_time'].'</th>
                                                        <th>'.$item_list[$i]['updated_time'].'</th>
                                                        <th width="100%" style="display:flex">
                                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_'.$item_list[$i]['id'].'">編輯</button>
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#remove_'.$item_list[$i]['id'].'">刪除</button>
                                                        </th>
                                                    </tr>
                                                    ';
                                                }
                                                echo '
                                                </tbody>
                                            </table>
                                            ';
                                            $item_list = $auth->GetMember_array();
                                            for($i = 1; $i <= $auth->GetNum($_SESSION['Member_Data']);$i++){
                                                echo '
                                                <div class="modal fade" id="edit_'.$item_list[$i]['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                        <form action="" method="post" class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalScrollableTitle">編輯 <font color="red"><b>'.$item_list[$i]['store_name'].'</b></font> 會員資料</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend input-group-text">
                                                                        <input name="admin" type="checkbox" '.enable($item_list[$i]['administrator']).'>
                                                                        <span>管理員</span>
                                                                    </div>
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            <input name="enable" type="checkbox" '.enable($item_list[$i]['enable']).'>
                                                                            <span>啟用</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                                                <input type="hidden" name="access_token" value="'.$item_list[$i]['access_token'].'">
                                                                <input type="submit" name="edit" class="btn btn-primary" value="編輯">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                ';
                                            }
                                            $item_list = $auth->GetMember_array();
                                            for($i = 1; $i <= $auth->GetNum($_SESSION['Member_Data']);$i++){
                                                echo '
                                                <div class="modal fade" id="remove_'.$item_list[$i]['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                        <form action="" method="post" class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalScrollableTitle">刪除 <font color="red"><b>'.$item_list[$i]['store_name'].'</b></font> 會員資料</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-danger" role="alert">你確定要刪除 '.$item_list[$i]['store_name'].'?</div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                                                <input type="hidden" name="access_token" value="'.$item_list[$i]['access_token'].'">
                                                                <input type="submit" name="remove" class="btn btn-primary" value="確認">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                ';
                                            }
                                            echo '
                                            <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <form action="" method="post" class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalScrollableTitle">新增會員資料</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="store_name">商店名稱</span>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder="請輸入商店名稱" name="store_name" aria-describedby="store_name">
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="store_username">商店帳號</span>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder="請輸入商店帳號" name="store_username" aria-describedby="store_username">
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="store_password">商店密碼</span>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder="請輸入商店密碼" name="store_password" aria-describedby="store_password">
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="store_repassword">重複密碼</span>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder="請輸入重複密碼" name="store_repassword" aria-describedby="store_repassword">
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="store_email">電子郵件</span>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder="請輸入電子郵件" name="store_email" aria-describedby="store_email">
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend input-group-text">
                                                                    <input name="admin" type="checkbox">
                                                                    <span>管理員</span>
                                                                </div>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <input name="enable" type="checkbox">
                                                                        <span>啟用</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                                            <input type="submit" name="create" class="btn btn-primary" value="新增">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>';
                    include "cms/object_footer.php";
                    echo '
            </body>
            </html>
            ';
        }
    }

?>