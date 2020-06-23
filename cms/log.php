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
        $title = "管理紀錄"; include "cms/object_header.php";
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
                            <h4 class="c-grey-900 mT-10 mB-30">操作紀錄</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                                        <table id="dataTable" class="table table-striped table-bordered" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>來源</th>
                                                    <th>操作說明</th>
                                                    <th>裝置名稱</th>
                                                    <th>IP 位置</th>
                                                    <th>建立時間</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>來源</th>
                                                    <th>操作說明</th>
                                                    <th>裝置名稱</th>
                                                    <th>IP 位置</th>
                                                    <th>建立時間</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>';
                                            $member_list = $auth->GetMember_array();
                                            for ($i=1; $i <= count($member_list); $i++) { 
                                                $logger_list = $logger->GetLog($member_list[$i]);
                                                for ($y=1; $y <= count($logger_list); $y++) {
                                                    echo '
                                                    <tr>
                                                        <th>'.@$member_list[$i]['store_name'].'</th>
                                                        <th>'.@log_de($logger_list[$y]['activity_content']).'</th>
                                                        <th>'.@$logger_list[$y]['device'].'</th>
                                                        <th>'.@$logger_list[$y]['ip'].'</th>
                                                        <th>'.@$logger_list[$y]['created_time'].'</th>
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