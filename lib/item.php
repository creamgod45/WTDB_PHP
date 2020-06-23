<?php
    
    require_once "logger.php";
    require_once "conn.php";

    /**
     * Item System v1.3
     */
    class item{

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
        private function send($GetMember, $option){
            $logger = new logX();
            return $logger->send($GetMember, $option);
        }

        /**
         * ### Create Item 
         * #### 建立商品
         * $option['name'];
         * $option['image'];
         * $option['description'];
         * $option['price'];
         * $option['unit'];
         * $option['enable'];
         * $option['device'];
         * $option['ip'];
         * @access public
         * @param $GetMember GetMember() 陣列
         * @param $option 設定陣列
         */
        public function CreateItem($GetMember, $option){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $item_id = $option['id'];
            $item_name = $option['name'];
            $item_image = $option['image'];
            $item_description = $option['description'];
            $item_price = $option['price'];
            $item_unit = $option['unit'];
            $item_enable = $option['enable'];
            $device = $option['device'];
            $ip = $option['ip'];
            $time = date ("Y-m-d H:i:s");
            if($item_image === ""){$item_image = "data:image/jpeg;base64,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";}
            $sql = "INSERT INTO `appmarket_item`(`access_token`, `item_id`, `item_name`, `item_image`, `item_description`, `item_price`, `item_unit`, `item_enable`, `created_time`)VALUES ('$access_token','$item_id' ,'$item_name','$item_image','$item_description','$item_price','$item_unit','$item_enable','$time')";
            if($conn->query($sql)){
                $query = $this->send($GetMember, [ 
                    'service_name' => 'CreateItem',
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
         * ### Get Item Info 
         * #### 取得物品資訊
         * @access public
         * @param array $GetMember GetMember() 陣列
         * @param 
         */
        public function GetItem($GetMember){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $sql = "SELECT * FROM `appmarket_item` WHERE `access_token` = '$access_token' ORDER BY `id` ASC";
            $result = $conn->query($sql);
            $x = 1;
            $object = array();
            while($row = mysqli_fetch_row($result)){
                $item = array(
                    'id' => $row[0],
                    'access_token' => $row[1],
                    'iid' => $row[2],
                    'name' => $row[3],
                    'image' => $row[4],
                    'dec' => $row[5],
                    'price' => $row[6],
                    'unit' => $row[7],
                    'enable' => $row[8],
                    'created_time' => $row[9],
                    'updated_time' => $row[10],
                );
                $object[$x] = $item;
                unset($item);
                $x++;
            }
            return $object;
        }

        /**
         * ### Item Name Translator to item id
         * ### 物品名稱轉譯器
         * @access public
         * @param String $name
         * @return String
         */
        public function item_translator($name){
            $conn = $this->conn();
            $sql = "SELECT `item_id` FROM `appmarket_item` WHERE `item_name` = '$name' and `item_enable` = 'true'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            return $row[0];
        }

        /**
         * ### ghost usage for view
         * #### 遊客檢視專用
         * @param $order_token 指定的令牌
         */
        public function GetOnceItem($id){
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_item` WHERE `item_id` = '$id' and `item_enable` = 'true'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            $num = mysqli_num_rows($result);
            $item = array(
                'id' => $row[0],
                'access_token' => $row[1],
                'iid' => $row[2],
                'name' => $row[3],
                'image' => $row[4],
                'dec' => $row[5],
                'price' => $row[6],
                'unit' => $row[7],
                'enable' => $row[8],
                'created_time' => $row[9],
                'updated_time' => $row[10],
            );
            $ok = 0;
            foreach ($item as $key => $value) {
                if($value != null){
                    $ok++;
                }
            }
            if($ok === 11){
                return $item;
            }else{
                return false;
            }
        }

        /**
         * ### Setting Item info
         * #### 設定物品資訊
         * @access public
         * @param array $GetMember $GetMember GetMember() 陣列
         * @param array $option 設定
         * @return boolean?string
         */
        public function SettingItem($GetMember, $option){
            $access_token = $GetMember['access_token'];
            $conn = $this->conn();

            @list($id, $name, $image, $dec, $price, $unit, $enable, $device, $ip) = $option;
            if($unit === "" or $unit === null){$unit = "-1";}
            if($enable === "on"){$enable = "true";}else{$enable = "false";}

            if($id != null and $name != null and $dec != null and $price != null and $image != null){
                if(filter_var($id, FILTER_VALIDATE_INT) and filter_var($price, FILTER_VALIDATE_INT)){
                    @$sql = "UPDATE `appmarket_item` SET `item_name`        = '$name',
                                                         `item_image`       = '$image',
                                                         `item_description` = '$dec',
                                                         `item_price`       = '$price',
                                                         `item_unit`        = '$unit',
                                                         `item_enable`      = '$enable' WHERE `access_token` = '$access_token' and `id` = '$id'";
                    if($conn->query($sql)){
                        $query = $this->send($GetMember, [
                            'service_name' => 'SettingItem',
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
                }else{
                    if(filter_var($id, FILTER_VALIDATE_INT)){
                        echo 'ID'."<br>";
                    }
                    if(filter_var($price, FILTER_VALIDATE_INT)){
                        echo '價錢'."<br>";
                    }
                    echo "數值不正確";
                    return false;
                }
            }else{
                if($id != null){echo 'ID'."<br>";}
                if($name != null){echo '商品名稱'."<br>";}
                if($image != null){echo '商品圖片'."<br>";}
                if($dec != null){echo '商品說明'."<br>";}
                if($price != null){echo '商品價錢'."<br>";}
                echo "數值為空";
                return false;
            }
        }

        /**
         * ### Item unit OBServer
         * #### 商品數量觀察者
         * @todo 檢查物品數量是否益位
         * @param array $GetMember $GetMember GetMember() 陣列
         * @param array $option 設定
         * @return mixed
         */
        public function unitOBServer($option){
            $id = $option['id'];
            $unit = $option['unit'];
            $item = $this->GetOnceItem($id);
            if($item['unit'] == "-1"){
                return true;
            }elseif($item['unit'] == "0" && $option['method'] == false){
                return false;
            }else{
                if(@$option['method']){
                    return true;
                }else{
                    if($item['unit'] >= $unit){
                        return true;
                    }else{
                        return 2;
                    }
                }    
            }
        }

        /**
         * Item Scheduler Controller
         * 控制物品排程控制器
         * @param Array $GetMember auth::GetMember() 會員資料
         * @param Array $option 設定陣列
         * @return Mixed
         */
        public function Scheduler_Controller($GetMember, $option){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $id = $option['id'];
            $unit = $option['unit'];
            $item = $this->GetOnceItem($id);
            if(@$option['method']){
                // 回復商品
                if($item['unit'] != "-1"){
                    $unit = $item['unit'] + $unit;
                }else{
                    $unit = "-1";
                }
            }else{
                // 減少商品
                if($item['unit'] != "-1"){
                    $unit = $item['unit'] - $unit;
                }else{
                    $unit = "-1";
                }
            }            
            @$sql = "UPDATE `appmarket_item` SET `item_unit` = '$unit' WHERE `access_token` = '$access_token' and `item_id` = '$id'";
            if($conn->query($sql)){
                return true;
            }else{
                return $conn->error;
            }
        }

        /**
         * ### Remove Item 
         * #### 刪除物品
         * @access public
         * @param array $GetMember $GetMember GetMember() 陣列
         * @param array $option 設定
         * @param boolean
         */
        public function RemoveItem($GetMember, $option){
            $access_token = $GetMember['access_token'];
            $conn = $this->conn();
            @list($id, $device, $ip) = $option;
            $sql = "DELETE FROM `appmarket_item` WHERE `access_token` = '$access_token' AND `id` = '$id'";
            if($conn->query($sql)){
                $query = $this->send($GetMember, [
                    'service_name' => 'RemoveItem',
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
         * ### GetItem() get object unit
         * #### 取得 GetItem() 陣列中的所有數量
         * @access public
         * @param array $GetMember GetMember() 陣列
         * @return integer
         */
        public function GetNum($GetMember){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $sql = "SELECT * FROM `appmarket_item` WHERE `access_token` = '$access_token' ORDER BY `id` ASC";
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