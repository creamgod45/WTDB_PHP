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
        $title = "儀錶板"; include "cms/object_header.php";
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
                        <div class="row gap-20 masonry pos-r">
                            <div class="masonry-sizer col-md-6"></div>
                            <div class="masonry-item w-100">
                                <div class="row gap-20">
                                    <div class="col-md-3">
                                        <div class="layers bd bgc-white p-20">
                                            <div class="layer w-100 mB-10">
                                                <h6 class="lh-1">總共訂單數量</h6>
                                            </div>
                                            <div class="layer w-100">
                                                <div class="peers ai-sb fxw-nw">
                                                    <div class="peer peer-greed"><span id="sparklinedash"></span></div>
                                                    <div class="peer">
                                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">'.$order->GetNum($_SESSION['Member_Data']).'</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="layers bd bgc-white p-20">
                                            <div class="layer w-100 mB-10">
                                                <h6 class="lh-1">總共商品數量</h6>
                                            </div>
                                            <div class="layer w-100">
                                                <div class="peers ai-sb fxw-nw">
                                                    <div class="peer peer-greed"><span id="sparklinedash2"></span></div>
                                                    <div class="peer">
                                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">'.$item->GetNum($_SESSION['Member_Data']).'</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="layers bd bgc-white p-20">
                                            <div class="layer w-100 mB-10">
                                                <h6 class="lh-1">總共會員數量</h6>
                                            </div>
                                            <div class="layer w-100">
                                                <div class="peers ai-sb fxw-nw">
                                                    <div class="peer peer-greed"><span id="sparklinedash3"></span></div>
                                                    <div class="peer">
                                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">'.$auth->GetNum().'</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="layers bd bgc-white p-20">
                                            <div class="layer w-100 mB-10">
                                                <h6 class="lh-1">總共操作紀錄</h6>
                                            </div>
                                            <div class="layer w-100">
                                                <div class="peers ai-sb fxw-nw">
                                                    <div class="peer peer-greed"><span id="sparklinedash4"></span></div>
                                                    <div class="peer">
                                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">'.$logger->GetNum($_SESSION['Member_Data']).'</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="masonry-item w-100">
                                <div class="bd bgc-white">
                                    <div class="layers">
                                        <div class="layer w-100 p-20" style="padding-bottom: unset !important;">
                                            <h4 class="lh-1">接受訂單流量</h4>
                                        </div>
                                        <div class="layer w-100 p-20">
                                            <table id="dataTable" class="table table-hover table-bordered" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>令牌</th>
                                                        <th>商店名稱</th>
                                                        <th>價錢</th>
                                                        <th>裝置</th>
                                                        <th>IP 位置</th>
                                                        <th>建立時間</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>令牌</th>
                                                        <th>商店名稱</th>
                                                        <th>價錢</th>
                                                        <th>裝置</th>
                                                        <th>IP 位置</th>
                                                        <th>建立時間</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>';
                                                    $order_list = $order->GetOrder($_SESSION['Member_Data']);
                                                    for($i = 1; $i<=$order->GetNum($_SESSION['Member_Data']);$i++){
                                                        echo '
                                                        <tr>
                                                            <td>'.$order_list[$i]['order_token'].'</td>
                                                            <td>'.OrderSplitInfo($order_list[$i]['order_content']).'</td>
                                                            <td>$'.$order_list[$i]['order_price'].'</td>
                                                            <td>'.$order_list[$i]['device'].'</td>
                                                            <td>'.$order_list[$i]['ip'].'</td>
                                                            <td>'.$order_list[$i]['created_time'].'</td>
                                                        </tr>
                                                        ';
                                                    }
                                                    echo '
                                                </tbody>
                                            </table>
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

?>