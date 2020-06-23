<?php 
    
    require_once "logger.php";
    require_once "token.php";
    require_once "conn.php";

    /**
     * 訂單系統 order system v1.2.0
     * @author creamgod45 <creamgod45@yun.sk>
     * @copyright 2020 無限二維條碼取餐APP
     * @since 1.0.0
     * @version 1.2.0
     */
    class order {

        /**
         * MySQL Connect to DataBase
         * 連接到資料庫
         * @access private
         * @return Array
         */
        private function conn(){
            $conn = new conn();
            return $conn->connent();
        }

        /**
         * Class Implements: logX
         * record event to database 
         * 記錄所有事件進入資料庫
         * - $option[service_name]
         *      - Login
         *      - Logout
         *      - ChangePassword
         *      - AddMember
         *      - GetMember
         *      - SetMember
         *      - CreateItem
         *      - SettingItem
         *      - RemoveItem
         *      - CreateOrder
         *      - SetOrder::verify
         *      - SetOrder::create
         *      - SetOrder::cancel
         *      - SetOrder::finish
         *      - RemoveOrder
         * @access private
         * @param Array $GetMember auth::GetMember() 會員資料
         * @param Array $option 設定陣列
         * @return Mixed
         */
        private function send($GetMember, $option){
            $logger = new logX();
            return $logger->send($GetMember, $option);
        }

        /**
         * Class Implements: token
         * Generate random token
         * 產生隨機令牌
         * @access private
         * @param String $service_name
         * @return Mixed
         */
        private function gettoken($service_name){
            $token = new token();
            return $token->gettoken($service_name);
        }

        /**
         * Create Order
         * 建立訂單
         * @access public
         * @param Array $GetMember auth::GetMember() 會員資料
         * @param Array $option 設定陣列
         * @return Mixed
         */
        public function CreateOrder($GetMember, $option){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $order_token = $this->gettoken("CreateOrder");
            $content = $option['content'];
            $price = $option['price'];
            $device = $option['device'];
            $ip = $option['ip'];
            $order_password = $option['password'];
            $time = date ("Y-m-d H:i:s");
            $sql = "INSERT INTO `appmarket_order`(`access_token`, `order_token`,`order_password`, `order_content`, `order_price`, `order_verification`, `order_status`, `order_ip`, `order_device`, `enable`, `created_time`)VALUES ('$access_token','$order_token','$order_password','$content','$price','false','wait','$ip','$device','true','$time')";
            if($access_token === ""){return false;}
            $_SESSION['cache_token'] = $order_token;
            if($conn->query($sql)){
                $query = $this->send($GetMember, [
                    'service_name' => 'CreateOrder',
                    'device' => $device,
                    'ip' => $ip
                ]);
                if($query){
                    return true;
                }else{
                    return $query;
                }
            }else{
                return $conn->error;
            }
        }

        /**
         * Store system usage for manage
         * 店家管理系統專用
         * @access public
         * @param Array $GetMember auth::GetMember() 會員資料
         * @return Mixed
         */
        public function GetOrder($GetMember){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $sql = "SELECT * FROM `appmarket_order` WHERE `access_token` = '$access_token' ORDER BY `id` DESC";
            $result = $conn->query($sql);
            $x = 1;
            $object = array();
            while($row = mysqli_fetch_row($result)){
                $item = array(
                    'id' => $row[0],
                    'access_token' => $row[1],
                    'order_token' => $row[2],
                    'order_password' => $row[3],
                    'order_content' => $row[4],
                    'order_price' => $row[5],
                    'verification' => $row[6],
                    'status' => $row[7],
                    'ip' => $row[8],
                    'device' => $row[9],
                    'enable' => $row[10],
                    'created_time' => $row[11],
                    'updated_time' => $row[12],
                );
                $object[$x] = $item;
                unset($item);
                $x++;
            }
            return $object;
        }
        
        /**
         * Ghost usage for view
         * 遊客檢視專用
         * @access public
         * @param String $order_token 指定的令牌
         * @return Mixed
         */
        public function GetOnceOrder($order_token){
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_order` WHERE `order_token` = '$order_token' and `enable` = 'true'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            $item = array(
                'id' => $row[0],
                'access_token' => $row[1],
                'order_token' => $row[2],
                'order_password' => $row[3],
                'order_content' => $row[4],
                'order_price' => $row[5],
                'verification' => $row[6],
                'status' => $row[7],
                'ip' => $row[8],
                'device' => $row[9],
                'enable' => $row[10],
                'created_time' => $row[11],
                'updated_time' => $row[12],
            );
            $ok = 0;
            foreach ($item as $key => $value) {
                if($value != null){
                    $ok++;
                }
            }
            if($ok === 13){
                return $item;
            }else{
                return false;
            }
        }

        /**
         * Generate QRcode 
         * 生成二維條碼
         * @access public
         * @param String $order_password 訂單密碼
         * @return Mixed
         */
        public function qrcode($order_password){
            include "phpqrcode/qrlib.php";
            $conn = $this->conn();
            $sql = "SELECT `order_token` FROM `appmarket_order` WHERE `order_password` = '$order_password'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            $filename = 'phpqrcode/temp/'.$row[0]."_".time().'.png';
            QRcode::png($row[0], $filename, 'H', 5, 2);   
            return '/phpqrcode/temp/'.basename($filename);
        }
        
        /**
         * Set Order
         * 設定訂單
         * @access public
         * @param Array $GetMember auth::GetMember() 會員資料
         * @param Array $option 設定陣列
         * @return Mixed
         */
        public function SetOrder($GetMember, $option){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $order_token = $option['token'];
            $status = $option['status'];
            $device = $option['device'];
            $ip = $option['ip'];
            if($status === 0){
                // 確認訂單
                $sql = "UPDATE `appmarket_order` SET `order_verification` = 'true' WHERE `order_token` = '$order_token' and `access_token` = '$access_token'";
                if($conn->query($sql)){
                    $query = $this->send($GetMember, [
                        'service_name' => 'SetOrder::verify',
                        'device' => $device,
                        'ip' => $ip,
                    ]);
                    if($query){
                        return true;
                    }else{
                        return $query;
                    }
                }else{
                    return $conn->error;
                }
            }elseif($status === 1){
                // 取消訂單
                $sql = "UPDATE `appmarket_order` SET `order_verification`='cance',`order_status`='false' WHERE `order_token` = '$order_token' and `access_token` = '$access_token'";
                if($conn->query($sql)){
                    $query = $this->send($GetMember, [
                        'service_name' => 'SetOrder::cancel',
                        'device' => $device,
                        'ip' => $ip,
                    ]);
                    if($query){
                        return true;
                    }else{
                        return $conn;
                    }
                }else{
                    return $conn->error;
                }
            }elseif($status === 2){
                // 完成訂單
                $sql = "UPDATE `appmarket_order` SET `order_status` = 'true' WHERE `order_token` = '$order_token' and `access_token` = '$access_token'";
                if($conn->query($sql)){
                    $query = $this->send($GetMember, [
                        'service_name' => 'SetOrder::finish',
                        'device' => $device,
                        'ip' => $ip,
                    ]);
                    if($query){
                        return true;
                    }else{
                        return $query;
                    }
                }else{
                    return $conn->error;
                }
            }elseif($status === 3){
                // 驗證訂單
                $sql = "UPDATE `appmarket_order` SET `order_verification` = 'ok' WHERE `order_token` = '$order_token' and `access_token` = '$access_token'";
                if($conn->query($sql)){
                    $query = $this->send($GetMember, [
                        'service_name' => 'SetOrder::create',
                        'device' => $device,
                        'ip' => $ip,
                    ]);
                    if($query){
                        return true;
                    }else{
                        return $query;
                    }
                }else{
                    return $conn->error;
                }
            }
        }

        /**
         * Get object all unit
         * 取得資料庫中所有資料總共數量
         * @access public
         * @param Array $GetMember auth::GetMember() 會員資料
         * @return Mixed
         */
        public function GetNum($GetMember){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $sql = "SELECT * FROM `appmarket_order` WHERE `access_token` = '$access_token' ORDER BY `id` ASC";
            $result = $conn->query($sql);
            $num = mysqli_num_rows($result);
            return  $num;
        }

        /**
         * Remove Order
         * 刪除訂單
         * @access public
         * @param Array $GetMember auth::GetMember() 會員資料
         * @param Array $option 設定陣列
         * @return Mixed
         */
        public function RemoveOrder($GetMember,$option){
            $access_token = $GetMember['access_token'];
            $conn = $this->conn();
            @list($id, $device, $ip) = $option;
            $sql = "DELETE FROM `appmarket_order` WHERE `access_token` = '$access_token' AND `order_token` = '$id'";
            if($conn->query($sql)){
                $query = $this->send($GetMember, [
                    'service_name' => 'RemoveOrder',
                    'device' => $device,
                    'ip' => $ip,
                ]);
                if($query){
                    return true;
                }else{
                    return $query;
                }
            }else{
                return $conn->error;
            }
        }
        
        /**
         * ### @package: 套件所屬 ORDER
         * ### Test plugins loading success
         * #### 測試套件載入成功 
         */
        public function test(){
            return true;
        }
    }