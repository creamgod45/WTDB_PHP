<?php 

    require_once "conn.php";

    /**
     * Logger System v1.0
     * @author CreamGod45
     * @version v1.0
     */
    class logX {

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
         * ### record event to database 
         * #### 記錄所有事件進入資料庫
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
         * @param $GetMember 會員資料
         * @param $option 設定陣列
         * @return array
         */
        public function send($GetMember, $option, $enable = false){
            $conn = $this->conn();
            $token = $GetMember['access_token'];
            $service_name = $option['service_name'];
            $device = $option['device'];
            $ip = $option['ip'];
            $time = date ("Y-m-d H:i:s");
            $sql = "INSERT INTO `appmarket_log`(`access_token`, `activity_content`, `store_device`, `ip`, `created_time`) VALUES ('$token','$service_name','$device','$ip','$time')";
            if($conn->query($sql)){
                return true;
            }else if($enable){
                return $conn->error;
            }else{
                return false;
            }
        }

        /**
         * ### Store system usage for manage
         * #### 店家管理系統專用
         * @param $GetMember GetMember() 陣列
         */
        public function GetLog($GetMember){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $sql = "SELECT * FROM `appmarket_log` WHERE `access_token` = '$access_token' ORDER BY `id` DESC";
            $result = $conn->query($sql);
            $x = 1;
            $object = array();
            while($row = mysqli_fetch_row($result)){
                $item = array(
                    'id' => $row[0],
                    'access_token' => $row[1],
                    'activity_content' => $row[2],
                    'device' => $row[3],
                    'ip' => $row[4],
                    'created_time' => $row[5],
                    'updated_time' => $row[6],
                );
                $object[$x] = $item;
                unset($item);
                $x++;
            }
            return $object;
        }

        /**
         * ### GetItem() get object unit
         * #### 取得 GetItem() 陣列中的所有數量
         * @access public
         * @param array $GetMember GetMember() 陣列
         * @return integer
         */
        public function GetNum($GetMember){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $sql = "SELECT * FROM `appmarket_log` WHERE `access_token` = '$access_token' ORDER BY `id` DESC";
            $result = $conn->query($sql);
            $num = mysqli_num_rows($result);
            return  $num;
        }
        
        /**
         * ### @package: 套件所屬 AUTH 
         * ### Test plugins loading success
         * #### 測試套件載入成功 
         */
        public function test(){
            return true;
        }
    }
?>