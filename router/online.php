<?php
	require_once "lib/auth.php";
	require_once "lib/order.php";
	require_once "lib/item.php";
	$auth = new auth();
	$item = new item();
	$order = new order();

	unset($_SESSION['webid_1']);
	$member_list = $auth->GetMember_array();
	session_destroy();
?>
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
    <title>嗨囉!</title>
</head>
<body>
    <div class="container-fluid">
        <ul class="nav nav-tabs mt-2"  id="myTab" role="tablist">
			<li class="nav-item">
			  	<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">所有店家</a>
			</li>
		</ul>
		<div class="tab-content bg-white tab_s" style="height: 90vh !important;" id="myTabContent">
			<div class="tab-pane fade show active p-2" id="home" role="tabpanel" aria-labelledby="home-tab">
				<div class="list-group">
				<?php
					for($i = 1; $i <= $auth->GetNum();$i++){
						echo '
							<a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#info_'.$member_list[$i]['id'].'"><img width="32" height="32" src="'.$member_list[$i]['store_image'].'"> 店家名稱：'.$member_list[$i]['store_name'].'</a>

						';
					}
				?>
				</div>
			</div>
		</div>
		<?php
			for($i = 1; $i <= $auth->GetNum();$i++){
				echo '
				<div class="modal fade" id="info_'.$member_list[$i]['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable" role="document">
					  	<div class="modal-content">
							<div class="modal-header">
						  		<h5 class="modal-title" id="exampleModalLabel">'.$member_list[$i]['store_name'].' 店家資訊</h5>
						  		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
						  		</button>
							</div>
							<div class="modal-body">
								<img width="128px" height="128px" src="'.$member_list[$i]['store_image'].'">
								<hr>
								<div>店家名稱：'.$member_list[$i]['store_name'].'</div>
								<div>店家訂單數量：'.$order->GetNum($member_list[$i]).'</div>
								<div>店家建立時間：'.$member_list[$i]['created_time'].'</div>
								<div>店家所有商品：</div>';
								$item_list = $item->GetItem($member_list[$i]);
								if($item->GetNum($member_list[$i]) === 0){echo '此店家沒有商品';}
								for($x = 1; $x <= $item->GetNum($member_list[$i]);$x++){
									echo '
									<li><img width="32" height="32" src="'.$item_list[$x]['image'].'"> '.$item_list[$x]['name'].' '.$item_list[$x]['price'].' '.item_limit((int)$item_list[$x]['unit']).' </li>
									';
								}
								echo '
							</div>
							<form action="/online_vendor" method="POST" class="modal-footer">
								<input type="hidden" name="vendor" value="'.$member_list[$i]['access_token'].'">
							  	<button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
							  	<button type="submit" class="btn btn-primary">前往店家</button>
							</form>
					  	</div>
					</div>
				</div>
				';
			}
		?>
    </div>
</body>
</html>