<?php require "lib/auth.php";$auth=new auth();if(@$auth->isMember($_SESSION['Member_Data'])){echo '你已經登入了';header('refresh:1;url="/manage"');}else{if(@$_POST['submit']==null){echo '<!DOCTYPE html><html lang="zh_tw"><head><meta charset="UTF-8"><link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /><title>無線二維條碼取餐APP</title><link rel="stylesheet" type="text/css" href="../assets/css/app.css"></head><body><div class="main_layout"><div class="title mj">無線二維條碼取餐控制器</div><div class="form"><form action="" method="POST"><div class="input_box"><div>商家代號：</div><input id="shopcode" type="text" name="code" placeholder="請輸入商家代號" required></div><div class="input_box"><div>商家密碼：</div><input id="password" type="password" name="password" placeholder="請輸入密碼" required></div><input class="submit mj" type="submit" name="submit" value="登入"><div class="excision"><hr><div class="mj"><span>or</span></div></div><a href="/admin/login" class="mj higher"><i style="vertical-align: middle;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg></i><span>&nbsp;<b>管理員身分</b> 登入後台管理系統</span></a></form></div></div></body></html>';}else{$name=$_POST['code'];$password=$_POST['password'];if($name!=null and $password!=null){$auth->Auths($name,$password,GetDevice(),GetIP());if(@$_SESSION['Member_Data']){echo '<h1>登入成功</h1>';header('refresh:3;url="/manage"');}else{echo '<h1>登入失敗(帳號或密碼其中一項錯誤)</h1>';header('refresh:3;url="/"');}}else{if($name==null){echo '<h1>抱歉!!帳號不能為空</h1>';}if($password==null){echo '<h1>抱歉!!密碼不能為空</h1>';}header('refresh:3;url="/"');}}}