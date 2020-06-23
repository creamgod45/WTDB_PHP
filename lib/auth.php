<?php 

    require_once "token.php";
    require_once "logger.php";
    require_once "conn.php";

    /**
     * Auth System v1.0
     * @author CreamGod45
     * @version v1.0
     */
    class auth {

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
         * Class Implements: token
         * ### Generate random token
         * #### 產生隨機令牌
         * @return string
         */
        private function gettoken($sevice_name){
            $token = new token();
            return $token->gettoken($sevice_name);
        }

        /**
         * ### Encode {$Value} to SHA3-512
         * #### 加密字串
         * @param String $Value       加密字串值
         * @param Boolean $Result_array 回傳詳細資料
         * @return String
         */
        private function encode_SHA($Value, $Result_array = false)
        {
            $Value = hash("sha256", $Value, false);
            if($Result_array){
                $old_sort = str_split($Value);
                $sort = "";
                for($i=count($old_sort)-1;$i>=0;$i--){
                    $sort .= $old_sort[$i];
                }
                return array(
                    'lenght' => strlen($Value), 
                    'value' => $Value, 
                    'sort' => $sort
                );
            }else{
                return $Value;
            }
        }

        /**
         * ### Auth system get member data
         * #### 取得會員資料
         * @param string $access_token 存取令牌
         * @return array
         */
        private function GetMember($access_token){
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` WHERE `access_token` = '$access_token'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            if($row != null){
                $data['access_token'] = $row[1];
                $this->send($data, [
                    'service_name' => 'GetMember',
                    'device'       => 'System',
                    'ip'           => '127.0.0.1'
                ]);
                return [
                    'id' => $row[0],
                    'access_token' => $row[1],
                    'store_name' => $row[2],
                    'store_email' => $row[4],
                    'store_code' => $row[5],
                    'store_image' => $row[6],
                    'administrator' => $row[7],
                    'enable' => $row[8],
                    'created_time' => $row[9],
                    'updated_time' => $row[10]
                ];
            }else{
                return false;
            }
            $conn->close();
        }

        /**
         * Ghost usage for view
         * 遊客檢視專用
         * @access public
         * @param String $order_token 指定的令牌
         * @return Mixed
         */
        public function GetOnceMember($access_token){
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` WHERE `access_token` = '$access_token'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            if($row != null){
                return [
                    'id' => $row[0],
                    'access_token' => $row[1],
                    'store_name' => $row[2],
                    'store_email' => $row[4],
                    'store_code' => $row[5],
                    'store_image' => $row[6],
                    'administrator' => $row[7],
                    'enable' => $row[8],
                    'created_time' => $row[9],
                    'updated_time' => $row[10]
                ];
            }else{
                return false;
            }
            $conn->close();
        }

        /**
         * ### Auth system Set member data
         * #### 設定會員資料
         * $option [
         *      'store_name'=>'...',
         *      'store_password'=>'...',
         *      'administrator'=>'...',
         *      'enable'=>'...'
         * ]
         * @param string $access_token 存取令牌
         * @param array $option 陣列設定物件
         * @return string
         */
        public function SetMember($access_token, $option){
            $result = "";
            $i = 0;
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` WHERE `access_token` = '$access_token'";
            if(!empty($option)){
                if($option['store_name'] != null){
                    $store_name = $option['store_name'];
                    $sql = "UPDATE `appmarket_member` SET `store_name`='$store_name' WHERE `access_token` = '$access_token'";
                    if($conn->query($sql)){
                        $result .= 'store_name:' . $option['store_name']. "<br>\n\r";
                        $i++;
                    }else{
                        $result .= 'store_name:' . $conn->error . "<br>\n\r";
                    }
                }
                if($option['store_password'] != null){
                    $store_password = $this->encode_SHA($option['store_password']);
                    $sql = "UPDATE `appmarket_member` SET `store_password`='$store_password' WHERE `access_token` = '$access_token'";
                    if($conn->query($sql)){
                        $result .= 'store_password:'. $option['store_password'] . "<br>\n\r";
                        $i++;
                    }else{
                        $result .= 'store_password:' . $conn->error . "<br>\n\r";
                    }
                }
                if($option['administrator'] != null){
                    $administrator = $option['administrator'];
                    $sql = "UPDATE `appmarket_member` SET `administrator`='$administrator' WHERE `access_token` = '$access_token'";
                    if($conn->query($sql)){
                        $result .= 'administrator:' . $administrator . "<br>\n\r";
                        $i++;
                    }else{
                        $result .= 'administrator:' . $conn->error . "<br>\n\r";
                    }
                }
                if($option['enable'] != null){
                    $enable = $option['enable'];
                    $sql = "UPDATE `appmarket_member` SET `enable`='$enable' WHERE `access_token` = '$access_token'";
                    if($conn->query($sql)){
                        $result .= 'enable:' . $enable . "<br>\n\r";
                        $i++;
                    }else{
                        $result .= 'enable:' . $conn->error . "<br>\n\r";
                    }
                }
            }
            if($i > 0){
                $data['access_token'] = $access_token;
                $this->send($data, [
                    'service_name' => 'SetMember',
                    'device'       => $option['device'],
                    'ip'           => $option['ip']
                ]);
                $result .= "執行" . (string) $i . "次數"; 
                return $result;
            }else{
                return false;
            }
            $conn->close();
        }
        
        /**
         * ### Add new member inser to database
         * #### 建立用戶
         * 
         * @param array $option 用戶基本資料
         * @return boolean
         * 
         * echo $auth->AddMember([
         *     'name' => '台北橋古早小吃',
         *     'password' => '123456789',
         *     'email' => 'email@gmail.com',
         *     'code' => 'user',
         *     'image' => '',
         *     'administrator' => 'true',
         *     'enable' => 'true',
         * ]);
         */
        public function AddMember($option){
            $access_token = md5(floor(time()/2).time()^2);
            $store_name = $option['name'];
            $store_password = $this->encode_SHA($option['password']);
            $store_email = $option['email'];
            $store_code = $option['code'];
            $store_image = $option['image'];
            $administrator = $option['administrator'];
            $enable = $option['enable'];
            $time = date ("Y-m-d H:i:s");
            if($store_image == null){
                $store_image = 'data:image/jpeg;base64,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';
            }
            if($option['name'] != null AND $option['password'] != null AND $option['email'] != null AND $option['code'] != null AND $option['administrator'] != null AND $option['enable'] != null){
                $conn = $this->conn();
                $sql = "INSERT INTO `appmarket_member`(`access_token`, `store_name`, `store_password`, `store_email`, `store_code`, `store_image`, `administrator`, `enable`, `created_time`) 
                        VALUES ('$access_token','$store_name','$store_password','$store_email','$store_code','$store_image','$administrator','$enable','$time')";
                if($conn->query($sql)){
                    $data['access_token'] = $access_token;
                    $this->send($data, [
                        'service_name' => 'AddMember',
                        'device'       => 'System',
                        'ip'           => '127.0.0.1'
                        ]);
                    return '新增會員成功';
                }else{
                    return $conn->error;
                }
                $conn->close();
            }else{
                echo "token：".$access_token."<br>";
                echo "name：".$store_name."<br>";
                echo "password：".$store_password."<br>";
                echo "email：".$store_email."<br>";
                echo "code：".$store_code."<br>";
                echo "adminstrator：".$administrator."<br>";
                echo "enable：".$enable."<br>";
                echo "time：".$time."<br>";
                return false;
            }
        }

        /**
         * ### Auth System Main
         * #### 主要登入系統
         * @param string $username 店家代號
         * @param string $password 店家密碼
         * @param string $ip       店家IP
         */
        public function Auths($username, $password, $device, $ip){
            $password = $this->encode_SHA($password);
            $sql = "SELECT `access_token` FROM `appmarket_member` WHERE `store_code` = '$username' AND `store_password` = '$password' and `enable` = 'true'";
            $conn = $this->conn();
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            if($row != null){
                $GetMember = $this->GetMember($row[0]);
                if($GetMember != false){
                    $_SESSION['Member_Data'] = $GetMember;
                    $this->send($_SESSION['Member_Data'], [
                        'service_name' => 'Login',
                        'device'       => $device,
                        'ip'           => $ip
                    ]);
                    setcookie('expire_date', date ("Y-m-d H-i-s", time()+1800), time()+60 , '/', $_SERVER['SERVER_NAME'], 0, 1);
                    return true;
                }else{
                    return $GetMember;
                }
            }
            $conn->close();
        }

        /**
         * ### Logout system 
         * #### 登出系統
         * @param $GetMember GetMebmer() 陣列
         * @param $ip Getip()
         */
        public function logout($GetMember, $ip){
            $this->send($GetMember, [
                'service_name' => 'Logout',
                'device'       => 'System',
                'ip'           => $ip
            ]);
        }
        
        /**
         * ### Change Password
         * #### 改變密碼
         * @param $Password 原本密碼
         * @param $NewPassword 新密碼
         * @return boolean
         */
        public function changepw($GetMember, $Password, $NewPassword, $option){
            $conn = $this->conn();
            $access_token = $GetMember['access_token'];
            $device = $option[0];
            $ip = $option[1];
            $sql = "SELECT `store_password` FROM `appmarket_member` WHERE `access_token` = '$access_token'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            if($this->encode_SHA($Password) === $row[0]){
                $query = $this->SetMember($access_token, [
                    'store_password' => $NewPassword,
                    'device'       => $device,
                    'ip'           => $ip
                ]);
                if($query){
                    $query = $this->send($GetMember, [
                        'service_name' => 'ChangePassword',
                        'device'       => $device,
                        'ip'           => $ip
                    ]);
                    if($query){
                        return true;
                    }else{
                        return $query;
                    }
                }else{
                    return $query;
                }
            }
            $conn->close();
        }

        /**
         * Get all store info
         * 取得所有店家資訊
         * @access public
         * @return Mixed
         */
        public function GetMember_array(){
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` ORDER BY `id` ASC";
            $result = $conn->query($sql);
            $x = 1;
            $object = array();
            while($row = mysqli_fetch_row($result)){
                $item = array(
                    'id' => $row[0],
                    'access_token' => $row[1],
                    'store_name' => $row[2],
                    'store_email' => $row[4],
                    'store_code' => $row[5],
                    'store_image' => $row[6],
                    'administrator' => $row[7],
                    'enable' => $row[8],
                    'created_time' => $row[9],
                    'updated_time' => $row[10]
                );
                $object[$x] = $item;
                unset($item);
                $x++;
            }
            $conn->close();
            return $object;
        }

        /**
         * Get object all unit
         * 取得資料庫中所有資料總共數量
         * @access public
         * @return Integer
         */
        public function GetNum(){
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` WHERE `enable` = 'true' ORDER BY `id` ASC";
            $result = $conn->query($sql);
            $num = mysqli_num_rows($result);
            $conn->close();
            return  $num;
        }

        /**
         * ### Check Administrator role
         * #### 檢查管理員身分
         * $GetMember GetMember()回傳的陣列
         * @param array $GetMember
         * @return boolean
         */
        public function isMember($GetMember){
            $access_token = $GetMember['access_token'];
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` WHERE `access_token` = '$access_token'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);

            if($GetMember['access_token'] === $row[1] and $GetMember['enable'] === "true"){
                return true;
            }else{
                return false;
            }
            $conn->close();
        }

        /**
         * ### Check Administrator role
         * #### 檢查管理員身分
         * $GetMember GetMember()回傳的陣列
         * @param array $GetMember
         * @return boolean
         */
        public function isAdministrator($GetMember){
            if($this->isMember($GetMember)){
                if($GetMember['administrator'] === "true" and $GetMember['enable'] === "true"){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            $conn->close();
        }

        /**
         * ### Check Password
         * #### 檢查密碼正確性
         * @param string $Password 密碼
         * @param string $access_token 存取令牌
         * @return boolean
         */
        public function isPassword($GetMember, $Password){
            $access_token = $GetMember['access_token'];
            $conn = $this->conn();
            $sql = "SELECT * FROM `appmarket_member` WHERE `access_token` = '$access_token'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_row($result);
            if($row != null){
                if($this->encode_SHA($Password)=== $row[3]){
                    return true;
                }else{
                    return false;
                }
            }else{
                return '無法搜索到 $access_token 這筆資料中匹配值';
            }
            $conn->close();
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