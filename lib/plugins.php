<?PHP
function GetIP(){
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
     $cip = $_SERVER["HTTP_CLIENT_IP"];
    }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
     $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }elseif(!empty($_SERVER["REMOTE_ADDR"])){
     $cip = $_SERVER["REMOTE_ADDR"];
    }else{
     $cip = "無法取得IP位址！";
    }
    if($cip === "::1"){
        $cip = '127.0.0.1';
    }
    return $cip;
}

function GetDevice(){
    $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
    if(stripos($_SERVER['HTTP_USER_AGENT'],"Android") && stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
            $Android = true;
    }else if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")){
            $Android = false;
            $AndroidTablet = true;
    }else{
            $Android = false;
            $AndroidTablet = false;
    }
    $webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $BlackBerry = stripos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $RimTablet= stripos($_SERVER['HTTP_USER_AGENT'],"RIM Tablet");

    if( $iPod || $iPhone ){
        return 'iPhone';
    }else if($iPad){
        return 'iPad';
    }else if($Android){
        return 'Android';
    }else if($AndroidTablet){
        return 'AndroidTablet';
    }else if($webOS){
        return 'webOS';
    }else if($BlackBerry){
        return 'BlackBerry';
    }else if($RimTablet){
        return 'RimTablet';
    }else{
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform  = "Unknown OS Platform";
        $os_array     = array(
                              '/windows nt 10/i'      =>  'Windows 10',
                              '/windows nt 6.3/i'     =>  'Windows 8.1',
                              '/windows nt 6.2/i'     =>  'Windows 8',
                              '/windows nt 6.1/i'     =>  'Windows 7',
                              '/windows nt 6.0/i'     =>  'Windows Vista',
                              '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                              '/windows nt 5.1/i'     =>  'Windows XP',
                              '/windows xp/i'         =>  'Windows XP',
                              '/windows nt 5.0/i'     =>  'Windows 2000',
                              '/windows me/i'         =>  'Windows ME',
                              '/win98/i'              =>  'Windows 98',
                              '/win95/i'              =>  'Windows 95',
                              '/win16/i'              =>  'Windows 3.11',
                              '/macintosh|mac os x/i' =>  'Mac OS X',
                              '/mac_powerpc/i'        =>  'Mac OS 9',
                              '/linux/i'              =>  'Linux',
                              '/ubuntu/i'             =>  'Ubuntu',
                              '/iphone/i'             =>  'iPhone',
                              '/ipod/i'               =>  'iPod',
                              '/ipad/i'               =>  'iPad',
                              '/android/i'            =>  'Android',
                              '/blackberry/i'         =>  'BlackBerry',
                              '/webos/i'              =>  'Mobile'
                        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;

        return $os_platform;
    }
}
function item_limit($limit){
    if($limit === -1){
        return "不限制";
    }else{
        return $limit;
    }
}

function item_enable($enable){
    if($enable === "true"){
        return "<span class=\"badge badge-pill badge-success\">啟用</span>";
    }else{
        return "<span class=\"badge badge-pill badge-danger\">關閉</span>";
    }
}

/**
 * ### Count Object unit and Match at the Array
 * #### 計算物件數量和匹配進入陣列
 * @param integer $total
 * @param array
 */
function split_dec($total){
    $x = $total / 10;
    for ($i=0; $i < floor($x); $i++) { 
        $object_stack[$i] = 10;
    }
    if((($total / 10) - floor($x)) > 0){
        $object_stack[floor($x)] = ((($total / 10) - floor($x)) * 10);
    }
    $y = array();
    for($i=1;$i<=count($object_stack);$i++){
        $y[$i] = $object_stack[$i-1];
    }
    unset($object_stack);
    for($i=1;$i<=count($y);$i++){
        $object_stack[$i] = $y[$i];
    }
    return $object_stack;
}

function enable($enable){
    if($enable === "true"){
        return "checked";
    }
}

function order_status($value){
    if($value === "wait"){
        return "等待餐點";
    }elseif ($value === "true") {
        return "餐點完成";
    }elseif ($value === "false") {
        return "餐點取消";
    }else{
        return $value;
    }
}

function order_verification($value){
    if($value === "true"){
        return "確認訂單";
    }elseif($value === "ok"){
        return "完成驗證";
    }elseif($value === "cance"){
        return "取消驗證";
    }elseif($value === "false"){
        return "等待中";
    }else{
        return $value;
    }
}

function unicodeDecode($unicode_str){
    $json = '{"str":"'.$unicode_str.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}

function GetRandoom($length = 10){
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function OrderSplitInfo($str){
    $String = "";
    $tmp_object = explode("/", $str);
    $Num = count($tmp_object)-1; // 0 = ""
    for($i=1;$i<=$Num;$i++){
        $String .= str_replace(":", " &times; ", $tmp_object[$i])."<br>";
    }
    return $String;
}
function OrderSplitArray($str){
    $object = array();

    $tmp_object = explode("/", $str);
    unset($tmp_object[0]);
    $num = count($tmp_object);

    for ($i=1; $i <= count($tmp_object); $i++) { 
        $tmp2_object = explode(":", $tmp_object[$i]);
        $object[$tmp2_object[0]] = $tmp2_object[1];
    }
    return $object;
}
function log_de($value){
    $service_name = [
        'Login' => '登入系統',
        'Logout' => '登出系統',
        'ChangePassword' => '改變密碼',
        'AddMember' => '新增會員',
        'GetMember' => '取得會員',
        'SetMember' => '設定會員',
        'CreateItem' => '建立商品',
        'SettingItem' => '設定商品',
        'RemoveItem' => '刪除商品',
        'CreateOrder' => '建立訂單',
        'SetOrder::verify' => '確認訂單',
        'SetOrder::create' => '驗證訂單',
        'SetOrder::cancel' => '取消訂單',
        'SetOrder::finish' => '完成訂單',
        'RemoveOrder' => '刪除訂單'
    ];
    if($service_name[$value] != null){return $service_name[$value];}
}
?>