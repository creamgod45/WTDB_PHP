<?php 
/* function conn(){
    @$mysqli = mysqli_connect("localhost", "root", "", "appmarket"); 
    //                         主機位置(IP)  帳號  密碼 選擇的資料庫
    if (@$mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
        exit;
    }            
    @mysqli_set_charset($mysqli,"utf8");
    return $mysqli;
}
$conn = conn(); // 建立連接
$conn = "SELECT * FROM `appmarket_item` WHERE `item_id` = '$id' and `item_enable` = 'true'"; // 資料庫指令
$result = $conn->query($sql);   // 執行指令 並 把結果帶入變數
$row = mysqli_fetch_row($result); // 透過變數 快速陣列化
$num = mysqli_num_rows($result);  // 透過變數 快速計算資料數量 */
echo base64_decode('CiBnb3RvIGhEUUF2OyBZTGdhXzogJGNvbm4gPSBjb25uKCk7IGdvdG8gYkUyMkw7IGxUb0ZGOiAkcm93ID0gbXlzcWxpX2ZldGNoX3JvdygkcmVzdWx0KTsgZ290byBCeTdDcjsgYkUyMkw6ICRjb25uID0gIlx4NTNceDQ1XDExNFx4NDVcMTAzXHg1NFx4MjBceDJhXDQwXHg0NlwxMjJcMTE3XHg0ZFx4MjBceDYwXDE0MVwxNjBcMTYwXDE1NVx4NjFceDcyXDE1M1wxNDVceDc0XHg1Zlx4NjlceDc0XDE0NVwxNTVceDYwXDQwXDEyN1wxMTBcMTA1XHg1MlwxMDVceDIwXHg2MFwxNTFcMTY0XDE0NVx4NmRceDVmXHg2OVx4NjRcMTQwXHgyMFx4M2RceDIwXDQ3eyRpZH1cNDdcNDBceDYxXDE1NlwxNDRcNDBcMTQwXDE1MVwxNjRcMTQ1XHg2ZFx4NWZcMTQ1XDE1NlwxNDFcMTQyXHg2Y1wxNDVcMTQwXDQwXDc1XDQwXHgyN1x4NzRceDcyXDE2NVx4NjVceDI3IjsgZ290byBJRW0zODsgaERRQXY6IGZ1bmN0aW9uIGNvbm4oKSB7IEAoJG15c3FsaSA9IG15c3FsaV9jb25uZWN0KCJceDZjXDE1N1wxNDNceDYxXDE1NFwxNTBceDZmXHg3M1x4NzQiLCAiXHg3MlwxNTdceDZmXDE2NCIsICcnLCAiXHg2MVwxNjBceDcwXDE1NVx4NjFceDcyXDE1M1x4NjVceDc0IikpOyBpZiAoQCRteXNxbGktPmNvbm5lY3RfZXJyb3IpIHsgZGllKCJceDQzXHg2ZlwxNTZcMTU2XHg2NVx4NjNcMTY0XDE1MVx4NmZcMTU2XHgyMFwxNDZcMTQxXDE1MVx4NmNceDY1XHg2NFw3Mlx4MjAiIC4gJG15c3FsaS0+Y29ubmVjdF9lcnJvcik7IGRpZTsgfSBAbXlzcWxpX3NldF9jaGFyc2V0KCRteXNxbGksICJcMTY1XDE2NFx4NjZceDM4Iik7IHJldHVybiAkbXlzcWxpOyB9IGdvdG8gWUxnYV87IElFbTM4OiAkcmVzdWx0ID0gJGNvbm4tPnF1ZXJ5KCRzcWwpOyBnb3RvIGxUb0ZGOyBCeTdDcjogJG51bSA9IG15c3FsaV9udW1fcm93cygkcmVzdWx0');