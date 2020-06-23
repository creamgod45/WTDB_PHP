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
        $title = "管理訂單"; include "cms/object_header.php";
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
                            <h4 class="c-grey-900 mT-10 mB-30">訂單資料</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                                        <table id="dataTable" class="table table-striped table-bordered" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>來源</th>
                                                    <th>商品</th>
                                                    <th>價錢</th>
                                                    <th>驗證</th>
                                                    <th>狀態</th>
                                                    <th>裝置</th>
                                                    <th>IP</th>
                                                    <th>啟用</th>
                                                    <th>建立</th>
                                                    <th>更新</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>來源</th>
                                                    <th>商品</th>
                                                    <th>價錢</th>
                                                    <th>驗證</th>
                                                    <th>狀態</th>
                                                    <th>裝置</th>
                                                    <th>IP</th>
                                                    <th>啟用</th>
                                                    <th>建立時間</th>
                                                    <th>更新時間</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>';
                                            $member_list = $auth->GetMember_array();
                                            for ($i=1; $i <= count($member_list); $i++) {
                                                $order_list = $order->GetOrder($member_list[$i]);
                                                for ($y=1; $y <= count($order_list); $y++) {
                                                    echo '
                                                    <tr>
                                                        <th>'.@$member_list[$i]['store_name'].'</th>
                                                        <th>'.OrderSplitInfo($order_list[$y]['order_content']).'</th>
                                                        <th>$'.$order_list[$y]['order_price'].'</th>
                                                        <th>'.order_verification($order_list[$y]['verification']).'</th>
                                                        <th>'.order_status($order_list[$i]['status']).'</th>
                                                        <th>'.$order_list[$i]['device'].'</th>
                                                        <th>'.$order_list[$i]['ip'].'</th>
                                                        <th>'.item_enable($order_list[$i]['enable']).'</th>
                                                        <th>'.$order_list[$i]['created_time'].'</th>
                                                        <th>'.$order_list[$i]['updated_time'].'</th>
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