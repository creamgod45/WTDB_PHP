# 無線二維條碼取餐APP
### 一、怎麼使用 無線二維條碼取餐APP ?😂
- 安裝本軟體指定的 [APP 程式](https://appmarket.zeitfrei.xyz/appmarket.v2.2.apk)
- 使用XAMPP架設網站系統
- 請部屬到外部伺服器。
- 帳號密碼皆為admin
### 二、如何部屬專案?😁
- 版本限制
    - PHP Version : 7.0 ↑
    - Android OS Version : 7.0 ↑
    - MySQL Database Version : 無限制 ↑
        - host:localhost
        - username:root
        - password:(null)
        - database_name:appmarket
    - Android Studio Version : 3.5 ↑'
- 點擊  ```releases``` 下載最新版本 [v2.2]()
- 安裝必要套件
    ```
    composer install 
    ```
- 建立資料庫 與 設定 `/lib/conn.php` 文件
    ```php 
    public function connent(){
        @$mysqli = mysqli_connect("資料庫位置", "帳號", "密碼", "資料庫名稱");
        if (@$mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
            exit;
        }            
        @mysqli_set_charset($mysqli,"utf8");
        return $mysqli;
    }
    ```
- 匯入 SQL `appmarket.sql`
### 三、專案進度😎
- 前端版面 × (100%)
- 後端設計 × (100%)
### 四、更新紀錄📜
### v2.2 UPDATE 2020/04/08 10:52:00
- 優化 檔案內容
- 修正 `index.php` 訂單完成介面
- 移除 無用資料
### v2.1 UPDATE 2020/03/01 18:00:00
- 新增 `assets/js/item.php` 取得指令店家的物品以堆疊方式建立變數
- 新增 `assets/js/app.php` 發布 `v3.0` 餐點系統
- 新增 `assets/js/online.php` 發布 `v3.0` 餐點系統
- 更新 `assets/js/app.php` 修正 `v3.1` 點餐數量限制錯誤 修正重複的程式碼
- 更新 `assets/js/online.php` 修正 `v3.1` 點餐數量限制錯誤 修正重複的程式碼
- 更新 `assets/js/app.js` 修正 `v2.0` 先前版本請求AJAX檔案所產生的非同步延遲
- 移除 `assets/js/app.js` 重新撰寫架構改良為 `assets/js/app.php`
- 新增 `router/online` 發布 線上訂購頁面
- 新增 `router/online_vendor` 發布 線上訂購頁面
- 新增 `router/online_verify` 發布 線上訂購頁面
- 新增 `router/online_order` 發布 線上訂購頁面
- 更新 `router/token.php` 修正判斷
- 更新 `router/wait.php` 修正判斷
- 更新 `router/wait_order.php` 修正 JS
- 更新 `router/index.php` 帳號密碼 判斷修正
- 更新 `router/manage.php` 訂單欄位改必填項 修復 介面顯示問題
- 更新 `router/manageorder.php` 訂單取消後將會回復商品數量 
- 更新 `lib/order.php` 新增 QRCODE GENERATE 修正 設定訂單 於版本 `v1.2.0` 
- 更新 `lib/item.php` 新增 商品數量觀察者、控制物品排程控制器、物品轉譯器 於 `v1.3` 移除 `v1.1` 物品控制器
- 更新 `index.php` 修正 訂單完成與訂單取消的變數修正、訂單取消時未能顯示取消驗證 新增 訂單取消還原物品數量
- 更新 `phpqrcode` 生成 QRCODE 二維條碼 取代 `v1.0` API 生成
### v2.0 UPDATE 2020/01/01 00:00:00
- 新增 會員系統
- 新增 商品系統
- 新增 紀錄系統
- 更新 訂餐系統
- 更新 版面優化
- 更新 修正網頁架構
### v1.0 UPDATE NONE
- 尚無任何更新紀錄說明
### 五、聯絡開發者📞
- Email:[fuyin1054@gmail.com](mailto:fuyin1054@gmail.com)
