<?php

/**
 * Encode {$Value} to SHA3-512
 * $Value        加密字串值
 * $Result_array 回傳詳細資料
 * @param String $Value       加密字串值
 * @param Boolean $Result_array 回傳詳細資料
 * @return String
 */
function encode_SHA ($Value, $Result_array = false)
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
?>