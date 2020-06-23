<?php

    require_once "conn.php";

    /**
     * token v1.0
     * @author CreamGod45
     * @version v1.0
     */
    class token{

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
         * Generate random token
         * 產生隨機令牌
         * @return string
         */
        public function gettoken($service_name){
            $conn = $this->conn();
            $token = md5(floor(time()/2).time()^2);
            $time = date ("Y-m-d H:i:s");
            $sql = "INSERT INTO `appmarket_token`(`token`, `service_name`, `created_time`) VALUES ('$token','$service_name','$time')";
            $conn->query($sql);
            return $token;
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