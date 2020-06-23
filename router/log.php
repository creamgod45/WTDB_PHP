<?php
    require_once "lib/auth.php";
    require_once "lib/logger.php";
    $auth = new auth();
    $logx = new logX();
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
            <link rel="stylesheet" type="text/css" href="../assets/DataTables/datatables.min.css"/>
            <script type="text/javascript" src="../assets/js/core.js"></script>
            <script type="text/javascript" src="../assets/DataTables/datatables.min.js"></script>
            <script type="text/javascript" src="../assets/js/util.js"></script>
            <title>嗨囉! '.$_SESSION['Member_Data']['store_name'].'</title>
        </head>
        <script>
        var oedd = 0;
        $(document).ready(function() {                 
            const table = $(\'#example\').DataTable();
        });
        </script>
        <body>';
        if(@$module['menu'] === true) {include "router/object_menu2.html";}
        echo '
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">廠商代號</li>
                    <li class="breadcrumb-item"><a href="/vendor">'.$_SESSION['Member_Data']['store_code'].'</a></li>
                    <li class="breadcrumb-item active" aria-current="page">操作紀錄</li>
                </ol>
            </nav>
            <table id="example" class="table table-hover table_log">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">操作說明</th>
                        <th scope="col">裝置名稱</th>
                        <th scope="col">IP 位置</th>
                        <th scope="col">建立時間</th>
                    </tr>
                </thead>
                <tbody>';
                $log_list = $logx->GetLog($_SESSION['Member_Data']);
                for($i = 1; $i<=$logx->GetNum($_SESSION['Member_Data']);$i++){
                    echo '
                    <tr>
                        <th></th>
                        <td>'.log_de($log_list[$i]['activity_content']).'</td>
                        <td>'.$log_list[$i]['device'].'</td>
                        <td>'.$log_list[$i]['ip'].'</td>
                        <td>'.$log_list[$i]['created_time'].'</td>
                    </tr>';
                }
                    echo'
                </tbody>
            </table>
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