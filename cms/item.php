<?php
    require_once "lib/auth.php";
    require_once "lib/item.php";
    require_once "lib/order.php";
    require_once "lib/logger.php";

    $auth = new auth();
    $item = new item();
    $order = new order();
    $logger = new logX();

    if(@$auth->isAdministrator($_SESSION['Member_Data'])){
        echo '
        <!doctype html>
        <html>';
        $title = "管理商品"; include "cms/object_header.php";
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
                            <h4 class="c-grey-900 mT-10 mB-30">商品資料</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                                        <table id="dataTable" class="table table-striped table-bordered" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>來源</th>
                                                    <th>圖片</th>
                                                    <th>商品名稱</th>
                                                    <th>商品簡介</th>
                                                    <th>商品價錢</th>
                                                    <th>商品數量</th>
                                                    <th>商品啟用</th>
                                                    <th>建立時間</th>
                                                    <th>更新時間</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>來源</th>
                                                    <th>圖片</th>
                                                    <th>商品名稱</th>
                                                    <th>商品簡介</th>
                                                    <th>商品價錢</th>
                                                    <th>商品數量</th>
                                                    <th>商品啟用</th>
                                                    <th>建立時間</th>
                                                    <th>更新時間</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>';                                                
                                            $member_list = $auth->GetMember_array();
                                            for ($i=1; $i <= count($member_list); $i++) { 
                                                $item_list = $item->GetItem($member_list[$i]);
                                                for ($y=1; $y <= count($item_list); $y++) {
                                                    echo '
                                                    <tr>
                                                        <th>'.@$member_list[$i]['store_name'].'</th>
                                                        <th><img width="32" src="'.$item_list[$y]['image'].'"></th>
                                                        <th>'.$item_list[$y]['name'].'</th>
                                                        <th>'.$item_list[$y]['dec'].'</th>
                                                        <th>'.$item_list[$y]['price'].'</th>
                                                        <th>'.item_limit((int)$item_list[$y]['unit']).'</th>
                                                        <th>'.item_enable($item_list[$y]['enable']).'</th>
                                                        <th>'.$item_list[$y]['created_time'].'</th>
                                                        <th>'.$item_list[$y]['updated_time'].'</th>
                                                    </tr>
                                                    ';
                                                }
                                            }
                                            echo '
                                            </tbody>
                                        </table>
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

?>