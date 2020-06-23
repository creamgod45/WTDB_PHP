<?php

    $item = $_SESSION['cache_item'];
    if($item){
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../assets/css/core.css">
            <script type="text/javascript" src="../assets/js/core.js"></script>
            <title>{"status":"wait_order"}</title>
        </head>
        <style>
        .lds-roller {
          display: inline-block;
          position: relative;
          width: 80px;
          height: 80px;
        }
        .lds-roller div {
          animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
          transform-origin: 40px 40px;
        }
        .lds-roller div:after {
          content: " ";
          display: block;
          position: absolute;
          width: 7px;
          height: 7px;
          border-radius: 50%;
          background: #fcf;
          margin: -4px 0 0 -4px;
        }
        .lds-roller div:nth-child(1) {
          animation-delay: -0.036s;
        }
        .lds-roller div:nth-child(1):after {
          top: 63px;
          left: 63px;
        }
        .lds-roller div:nth-child(2) {
          animation-delay: -0.072s;
        }
        .lds-roller div:nth-child(2):after {
          top: 68px;
          left: 56px;
        }
        .lds-roller div:nth-child(3) {
          animation-delay: -0.108s;
        }
        .lds-roller div:nth-child(3):after {
          top: 71px;
          left: 48px;
        }
        .lds-roller div:nth-child(4) {
          animation-delay: -0.144s;
        }
        .lds-roller div:nth-child(4):after {
          top: 72px;
          left: 40px;
        }
        .lds-roller div:nth-child(5) {
          animation-delay: -0.18s;
        }
        .lds-roller div:nth-child(5):after {
          top: 71px;
          left: 32px;
        }
        .lds-roller div:nth-child(6) {
          animation-delay: -0.216s;
        }
        .lds-roller div:nth-child(6):after {
          top: 68px;
          left: 24px;
        }
        .lds-roller div:nth-child(7) {
          animation-delay: -0.252s;
        }
        .lds-roller div:nth-child(7):after {
          top: 63px;
          left: 17px;
        }
        .lds-roller div:nth-child(8) {
          animation-delay: -0.288s;
        }
        .lds-roller div:nth-child(8):after {
          top: 56px;
          left: 12px;
        }
        @keyframes lds-roller {
          0% {
            transform: rotate(0deg);
          }
          100% {
            transform: rotate(360deg);
          }
        }      
        </style>
        <body>
            <div class="container">
                <center><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></center>
                <center>等待餐點製作中</center>
            </div>
        </body>
        <script>
            setInterval(() => {
                $.getJSON("/order/'.$item['order_token'].'", function (result) {
                    var status = result.status;
                    if (status　=== "true" ){document.location.href = "/finish";}
                    if (status　=== "false"){document.location.href = "/cancel_orders";}
                });
            }, 1000);
        </script>
        </html>
        ';
    }else{
        //header('refresh:1;url="/token/null"');
    }
?>