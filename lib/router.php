<?php 

    /**
     * 分流系統
     * $layer        層數
     * @param Integer $layer  層數
     * @return String
     */
    function router($layer=1){
        $url = $_SERVER['REQUEST_URI'];
        $REQUEST = explode("/", $url);
        $REQUEST = $REQUEST[$layer];
        return $REQUEST;
    }