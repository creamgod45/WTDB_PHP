<?php 
    class conn {
        
        /**
         * MySQL Connect to DataBase
         * 連接到資料庫
         * @access private
         * @return Array
         */
        public function connent(){
            @$mysqli = mysqli_connect("localhost", "root", "", "appmarket");
            if (@$mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
                exit;
            }            
            @mysqli_set_charset($mysqli,"utf8");
            return $mysqli;
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