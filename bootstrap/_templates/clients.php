<?php
	/*
	$ssql = mysql_query("SELECT * FROM `purchases_data`");
	while($ffetch = mysql_fetch_assoc($ssql)){
		echo $ffetch['date'];
		list($day,$month,$year) = explode('.',$ffetch['date']);
		echo " - ".strtotime($ffetch['date']);
		echo " - ".strtotime($month."/".$day."/".$year." 00:00:00");
		echo " - ".date('d.m.Y H:i:s', strtotime($month."/".$day."/".$year." 00:00:00"));
		echo " - ".date('d.m.Y H:i:s', strtotime($ffetch['date']))."<br/>";
		//mysql_query("UPDATE `purchases_data` SET `datetime`='".strtotime($ffetch['date'])."' WHERE `id`='".$ffetch['id']."'");
	}
	exit;
	*/
?>
<html>
	<head>
		<link rel='stylesheet' href='./_css/style.css'></link>
		<script src='./_js/jquery-latest.js'></script>
		<script>
			$(function(){
				$('.calc').keyup(function(){
					$('.amount').val($('.amount').val().replace(',','.'));
					var amount = parseFloat($('.amount').val().replace(',','.'));
					var price = parseFloat($('.price').val());
					var summ = amount*price;
					$('.summ').val(summ);
				});
			});
		</script>
		<style>

			.panel{}
			.row.header{background: whitesmoke; font-weight: bold; border-bottom: 2px solid #aaa}
			.row.footer{background: whitesmoke; font-weight: bold; border-top: 2px solid #aaa}
			.row.marked div:nth-child(even) {background: #aaa}
			.row{font-size: 12px;}
			.row:nth-child(odd){background: #F9F9F9}
			.row div div{padding: 10px}
			.row.header div div{padding: 10px}
		</style>
	</head>
	<body>
		<div class='page col-100'>
			<div class='nav col-15' style='background: #191919; color: white; height: 100%'>
				<?php include('./_templates/inc/nav_left.php'); ?>
			</div>
			<div class='nav col-85' style=''>
				<header  style='background: black; height: 30px'>
					<div class='col-50'></div>
					<div class='col-10' align=right></div>
					<div class='col-10' align=right></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><?php echo $_SESSION['user_phone'];?></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><a href='./?logout'>Выйти</a></div></div>
				</header>
				<div class='content'>
					<!--div class='footer' style='margin-bottom: 30px;'>
						<h2>Поиск по номеру</h2>
						<form method='get'>
							<div class='col-15'><input type='text' name='phone' class='inpt col-100' placeholder='Номер' value='<?php echo $_GET['phone'];?>'/></div>
							<div class='col-5'><input type='submit' name='sector' value='clients' class='btn btn-default col-100'/></div>
						</form>
					</div-->
					<h1>Заказы</h1>
					<div class='panel'>
					<?php
						if(isset($_GET['phone'])){
							$user_phone = $_GET['phone'];
							$sqlUser = mysql_query("SELECT * FROM `users_data` WHERE `user_phone` = '".$user_phone."'");
							$uFetch = mysql_fetch_assoc($sqlUser);
							echo $uFetch['comment']."<br/>";
							echo
							"<form method='post'>
								<input type='hidden' class='inpt' name='user_phone' value='".$uFetch['user_phone']."'/>
								<input type='text' class='inpt' name='comment'/>
								<input type='submit' class='inpt' name='add_comment'/>
							</form>";
							$sqlAddrs = mysql_query("SELECT * FROM `users_addrs` WHERE `user_id` = '".$uFetch['id']."'");
							if(mysql_num_rows($sqlAddrs)>1){
								while($addrsFetch = mysql_fetch_assoc($sqlAddrs)){
									$addressStr = '';
									if(!empty($addrsFetch['street_name_RU'])){$addressStr .= $addrsFetch['street_name_RU'].',';}
									if(!empty($addrsFetch['house'])){$addressStr .= ' '.$addrsFetch['house'];}
									if(!empty($addrsFetch['appartment'])){$addressStr .= '-'.$addrsFetch['appartment'];}
									if(!empty($addrsFetch['podiezd'])){$addressStr .= ' п'.$addrsFetch['podiezd'];}
									if(!empty($addrsFetch['floor'])){$addressStr .= ' э'.$addrsFetch['floor'];}
									if(!empty($addrsFetch['podiezd_code'])){$addressStr .= ' код'.$addrsFetch['podiezd_code'];}
									if($addrsFetch['domofon']==0){$addressStr .= '*';}
									echo "<div style='padding: 10px 0'>".$addressStr."<form method=post><input type='hidden' name='row_id' value='".$addrsFetch['id']."'/><input type='submit' name='delete_addr' value='удалить'/></form></div>";
								}
							} else {
								$addrsFetch = mysql_fetch_assoc($sqlAddrs);
								echo "<div style='padding: 10px 0'>".$addrsFetch['address']."</div>";
							}
							$sqlOrders = mysql_query("SELECT * FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' ORDER BY `time` DESC");
							if(mysql_num_rows($sqlOrders) > 0){
								$sqlOrders2 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '1'");
								$sqlOrders3 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '2'");
								$sqlOrders4 = mysql_query("SELECT AVG(`summ`) FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '1'");
								$fetch4 = mysql_fetch_assoc($sqlOrders4);
								echo "<div style='font-size: 24px; padding-top:5px'>Всего успешных заказов:".mysql_num_rows($sqlOrders2)."</div>";
								echo "<div style='font-size: 24px; padding-top:5px'>Отмененных заказов:".mysql_num_rows($sqlOrders3)."</div>";
								echo "<div style='font-size: 12px; padding-top:10px'>Средний чек:".floor($fetch4['AVG(`summ`)'])."</div>";
								echo "<div style='font-size: 12px; padding-top:10px'>Бонусов:".$uFetch['user_bonus']." Активны до:".date('d.m.Y',$uFetch['user_bonus_expires'])."</div>";
								echo "<div class='row header'>
										<div class='col-10'><div>Дата</div></div>
										<div class='col-10'><div>Адрес</div></div>
										<div class='col-20'><div>Заказ</div></div>
										<div class='col-10'><div>Курьер</div></div>
										<div class='col-5'><div align='right'>Бонус</div></div>
										<div class='col-5'><div align='right'>Сумма</div></div>
										<div class='col-5'><div align='right'>Нал.</div></div>
										<div class='col-5'><div align='right'>Скидка</div></div>
										<div class='col-5'><div align='right'>Статус</div></div>
									</div>";
								while($ordersFetch = mysql_fetch_assoc($sqlOrders))
								{
									$status = "<span style='color: green'>успешный</span>"; if($ordersFetch['status'] == 2){$status = "<span style='color: red'>отменен</span>";}
									if($ordersFetch['time'] > time() - ( 60 * 60 * 24 * 10)){$color = 'green';} else
									if(time() - ( 60 * 60 * 24 * 10) > $ordersFetch['time'] && $ordersFetch['time'] > time() - ( 60 * 60 * 24 * 30)){$color = 'orange';} else
									{$color = 'gray';}
									echo "<div class='row'>
											<div class='col-10'>
												<div style='color: ".$color."'>".date('d.m.Y',$ordersFetch['time'])."<br/>".date('H:i',$ordersFetch['time'])." - ".date('H:i',$ordersFetch['time_closed'])."</div>
												<div>S1-".$ordersFetch['id']."</div>
											</div>
											<div class='col-10'><div>".$ordersFetch['address']." ".$ordersFetch['user_phone']."</div></div>
											<div class='col-20'><div>".nl2br($ordersFetch['order_details'])."</div></div>
											<div class='col-10'><div>".$ordersFetch['courier']."</div></div>
											<div class='col-5'><div align='right'>".$ordersFetch['bonus']."</div></div>
											<div class='col-5'><div align='right'>".$ordersFetch['summ']."</div></div>
											<div class='col-5'><div align='right'>".$ordersFetch['cash']."</div></div>
											<div class='col-5'><div align='right'>".$ordersFetch['discount']."</div></div>
											<div class='col-5'><div align='right'>".$status."</div></div>
											<div class='col-5'><div align='right'><form method='post'><input type='hidden' name='delete_order' value='".$ordersFetch['id']."'/><input type='submit' value='удалить заказ'/></form></div></div>
										</div>";
								}
							}
						} else
						if(isset($_GET['address'])){
							$address = $_GET['address'];
							$sqlOrders = mysql_query("SELECT * FROM `turn_orders_data` WHERE `address` LIKE '%".$address."%' ORDER BY `time` DESC");
							if(mysql_num_rows($sqlOrders) > 0){
								echo "<div class='row header'>
										<div class='col-10'><div>Дата</div></div>
										<div class='col-10'><div>Адрес</div></div>
										<div class='col-20'><div>Заказ</div></div>
										<div class='col-10'><div>Бонус</div></div>
										<div class='col-10'><div align='right'>Сумма</div></div>
										<div class='col-10'><div align='right'>Статус</div></div>
									</div>";
								while($ordersFetch = mysql_fetch_assoc($sqlOrders))
								{
									$status = "<span style='color: green'>успешный</span>"; if($ordersFetch['status'] == 2){$status = "<span style='color: red'>отменен</span>";}
									if($ordersFetch['time'] > time() - ( 60 * 60 * 24 * 10)){$color = 'green';} else
									if(time() - ( 60 * 60 * 24 * 10) > $ordersFetch['time'] && $ordersFetch['time'] > time() - ( 60 * 60 * 24 * 30)){$color = 'orange';} else
									{$color = 'gray';}
									echo "<div class='row'>
											<div class='col-10'><div style='color: ".$color."'>".date('d.m.Y',$ordersFetch['time'])."<br/>".date('H:i',$ordersFetch['time'])." - ".date('H:i',$ordersFetch['time_closed'])."</div></div>
											<div class='col-10'><div>".$ordersFetch['address']." ".$ordersFetch['user_phone']."</div></div>
											<div class='col-20'><div>".nl2br($ordersFetch['order_details'])."</div></div>
											<div class='col-10'><div>".$ordersFetch['bonus']."</div></div>
											<div class='col-10'><div align='right'>".$ordersFetch['summ']."</div></div>
											<div class='col-10'><div align='right'>".$status."</div></div>
										</div>";
								}
							}
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
