<?php
	require_once "lib/auth.php";
	require_once "lib/order.php";
	require_once "lib/item.php";
	
	$auth = new auth();
	$item = new item();
	$order = new order();

	function OrderSplitInfos($str){
		$String = "";
		$tmp_object = explode("/", $str);
		$Num = count($tmp_object)-1; // 0 = ""
		for($i=1;$i<=$Num;$i++){
			$String .= str_replace(":", " &times; ", "<li>".$tmp_object[$i])."</li>";
		}
		return $String;
	}

	@$vendor = $_POST['vendor'];
	$member_list = $auth->GetOnceMember($vendor);
	$_SESSION['cache_js_member'] = $member_list;
	if(@$_SESSION['webid_1'] == null && @$_POST['order'] != null){
		$_SESSION['webid_1'] = time();
		$content = "";
		$total_money = 0;
		$orderlist = $_POST['orderlist'];
        $order_item = OrderSplitArray($orderlist);
        foreach ($order_item as $key => $value) {
            $item_result = $item->GetOnceItem($key);
            $content .= "/".$item_result['name'].":".$value;
            $total_money += $item_result['price'] * $value;
		}
		$_SESSION['cache_orderinfo'] = $order_item;
		$_SESSION['cache_price'] = $total_money;
		echo '
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta http-equiv="X-UA-Compatible" content="ie=edge">
			<link rel="stylesheet" type="text/css" href="../assets/css/initialize.min.css">
			<link rel="stylesheet" type="text/css" href="../assets/css/core.css">
			<script type="text/javascript" src="../assets/js/core.js"></script>
			<title>嗨囉!</title>
		</head>
		<body>
			<div class="container-fluid">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/online">選購餐點</a></li>
					<li class="breadcrumb-item active" aria-current="page">填寫資料</li>
				  </ol>
				</nav>
				<div class="card">
					  <form action="/online_verify" method="POST" class="card-body" style="overflow:auto;height:80vh;">
							<h5 class="card-title">訂單資料</h5>
							<div class="card-text">商品價錢：$'.$total_money.'</div>
							<div class="card-text">商品內容：<br>'.OrderSplitInfos($content).'</div><hr>
							<div class="form-group">
							  	<label for="exampleInput1">稱呼</label>
							  	<input type="text" class="form-control" name="nick" id="exampleInput1" maxlength="10" aria-describedby="emailHelp" required>
							  	<small id="emailHelp" class="form-text text-muted">此欄為必填。取餐時會詢問稱呼正確即可取餐。</small>
							</div>
							<div class="form-group">
							  	<label for="exampleInput2">電子信箱</label>
							  	<input type="email" class="form-control" name="email" id="exampleInput2" maxlength="255" aria-describedby="emailHelp" required>
							  	<small id="emailHelp" class="form-text text-muted">此欄為必填。我們交會寄送驗證碼信件到你的帳戶。</small>
							</div>
							<div class="form-group">
							  	<label for="exampleInput3">手機號碼</label>
							  	<input type="number" class="form-control" name="number" id="exampleInput3" aria-describedby="emailHelp" required>
							  	<small id="emailHelp" class="form-text text-muted">此欄為必填。如果聯絡不上你將會撥打電話。</small>
							</div>
							<div class="alert alert-danger" role="alert">
								請注意送出資料後必須驗證，否則10分鐘後將被取消訂單。
							</div>
							<input type="submit" class="card-link btn btn-outline-success" value="驗證訂單">
					  </form>
				</div>
			</div>
		</body>';
	}elseif($vendor != null){
		$_SESSION['cahce_vendor'] = $vendor;
		echo '
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta http-equiv="X-UA-Compatible" content="ie=edge">
			<link rel="stylesheet" type="text/css" href="../assets/css/core.css">
			<link rel="stylesheet" type="text/css" href="../assets/css/initialize.min.css">
			<link rel="stylesheet" type="text/css" href="../assets/css/online.css">
			<script type="text/javascript" src="../assets/js/core.js"></script>
			<script type="text/javascript" src="../assets/js/online.php"></script>
			<title>嗨囉!</title>
		</head>
		<body>
			<div class="container-fluid">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">選購餐點</li>
				  </ol>
				</nav>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						  <a class="nav-link" href="/online"><i class="fas fa-reply"></i></a>
					</li>
					<li class="nav-item">
						  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">店家</a>
					</li>
					<li class="nav-item">
						  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">餐點</a>
					</li>
					<li class="nav-item">
						  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">購物車 <span id="display_unit" class="badge badge-danger">0</span></a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active p-3 bg-white tab_s" id="home" role="tabpanel" aria-labelledby="home-tab">
						<img width="100%" src="'.$member_list['store_image'].'">
						<hr>
						<div>店家名稱：'.$member_list['store_name'].'</div>
						<div>店家訂單數量：'.$order->GetNum($member_list).'</div>
						<div>店家建立時間：'.$member_list['created_time'].'</div>
						<div>店家所有商品：</div><ul>';
						$item_list = $item->GetItem($member_list);
						if($item->GetNum($member_list) === 0){echo '此店家沒有商品';}
						for($x = 1; $x <= $item->GetNum($member_list);$x++){
							echo '
							<li><img width="32" height="32" src="'.$item_list[$x]['image'].'"> '.$item_list[$x]['name'].' $'.$item_list[$x]['price'].' '.item_limit((int)$item_list[$x]['unit']).' </li>
							';
						}
						echo'</ul>
					</div>
					  <div class="tab-pane fade p-2 bg-white tab_" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						<div class="list-group">';
							if($item->GetNum($member_list) === 0){echo '此店家沒有商品';}
							for($x = 1; $x <= $item->GetNum($member_list);$x++){
								echo '
								<a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#exampleModal">
									<img width="100%" src="'.$item_list[$x]['image'].'">
									<div>商品名稱：'.$item_list[$x]['name'].'</div>
									<div>商品價錢：$'.$item_list[$x]['price'].'</div>
									<div>商品數量：'.item_limit((int)$item_list[$x]['unit']).'</div>
									<div class="btn-group" style="display:flex;" role="group" aria-label="Basic example">
										<button id="add_'.$item_list[$x]['iid'].'" onclick="add_order(\''.$item_list[$x]['iid'].'\');" type="button" class="btn btn-outline-primary mj">添加</button>
										<button id="less_'.$item_list[$x]['iid'].'" onclick="less_order(\''.$item_list[$x]['iid'].'\');" type="button" class="btn btn-outline-danger mj">減少</button>
									</div>
								</a>
								';
							}
							echo'
						</div>
					</div>
					<div class="tab-pane fade p-3 bg-white tab_s" id="contact" role="tabpanel" aria-labelledby="contact-tab">
						<table class="table">
							<thead class="thead-dark">
							  <tr>
								<th scope="col"></th>
								<th scope="col">商品名稱</th>
								<th scope="col">數量</th>
								<th scope="col">價錢</th>
							  </tr>
							</thead>
							<tbody class="order_item">
							  <tr>
								<th scope="row"></th>
								<td>總共</td>
								<td id="count_unit">0</td>
								<td id="count_money">$0</td>
							  </tr>
							</tbody>
						</table>
						<div class="alert alert-warning" role="alert">網站將需要紀錄你的IP位置以保障網站能正常運作。</div>
						<form action="" method="post">
							<input type="hidden" id="orderlist" name="orderlist" required>
							<input type="submit" name="order" class="btn btn-success btn-lg btn-block" value="送出餐點">
							<button type="button" onclick="location.reload();" class="btn btn-danger btn-lg btn-block">重置</button>
						</form>
					</div>
				</div>
			</div>
		</body>
		</html>
		';
	}else{
		header('refresh:0;url="/online"');
	}
?>