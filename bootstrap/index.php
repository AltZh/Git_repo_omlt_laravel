<?php
	//ini_set('display_startup_errors',1);
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	//echo phpinfo();
	//exit;
	date_default_timezone_set('Asia/Almaty');
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	//$_SESSION['user_id'];
	//$_SESSION['user_login'];
	//$_SESSION['user_group'];
	include_once('./_php/db_con.php');

	//die(var_dump($_SESSION));

	if(isset($_SESSION['user']) && (int)$_SESSION['user']['id'] != 0){
		
		$_CONFIG['save_new_meal_if_not_found'] = 1;
		$_CONFIG['work_hour_from'] = 11;
		$_CONFIG['work_hour_to'] = 23;
		
		function formatPhone($phone){
			if(strlen($phone) == 11){
				$code = substr($phone, 1, 3);
				$part1 = substr($phone, 4, 3);
				$part2 = substr($phone, 7, 2);
				$part3 = substr($phone, 9, 2);
				$result = "".$code."-".$part1."-".$part2."-".$part3;
			} else {
				$result = $phone;
			}
			return $result;
		}
		
		if(isset($_GET['logout'])){
			session_destroy();
			header('Location: ./');
			exit;
		}

		if(isset($_GET['sector']) && $_GET['sector'] == 'order_details'){
			include_once('./_php/db_con.php');
			$orderID = (int)$_GET['id'];
			$sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `id` = '".$orderID."'");
			$fetch = mysql_fetch_assoc($sql);

			include_once('./_templates/order_details.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'staff'){
			include_once('./_php/db_con.php');
			if(isset($_POST['update_staff'])){
				$id = (int)$_POST['id'];
				$group = $_POST['group'];
				$name = $_POST['name'];
				$phone = $_POST['phone'];
				$password = md5($_POST['password']);
				$telegram_chat_id = $_POST['telegram_chat_id'];
				$status = $_POST['status'];
				$group = $_POST['group'];
				$access_level = $_POST['access_level'];
				if($status == '-1'){
					mysql_query("DELETE FROM `staff_data` WHERE `id` = '".$id."' LIMIT 1");
				} else {
					mysql_query("UPDATE `staff_data` SET `name`='".$name."',`phone`='".$phone."',`password`='".$password."',`telegram_chat_id`='".$telegram_chat_id."',
							`status`='".$status."',`group`='".$group."',`access_level`='".$access_level."' WHERE `id` = '".$id."'");
				}
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['add_new_staff'])){
				$group = $_POST['group'];
				$name = $_POST['name'];
				$password = md5($_POST['password']);
				$telegram_chat_id = $_POST['telegram_chat_id'];
				$status = $_POST['status'];
				$group = $_POST['group'];
				$access_level = $_POST['access_level'];
				mysql_query("INSERT INTO `staff_data`(`name`,`phone`,`password`,`telegram_chat_id`,`status`,`group`,`access_level`) VALUES (
					'".$name."','".$phone."','".$password."','".$telegram_chat_id."','".$status."','".$group."','".$access_level."')");
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			include_once('./_templates/staff.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'tickets'){
			include_once('./_php/db_con.php');
			if(isset($_POST['update_ticket'])){
				$ticket_id = $_POST['ticket_id'];
				$status = $_POST['status'];
				$conclusion = $_POST['conclusion'];

				mysql_query("UPDATE `users_tickets_data` SET `status` = '".$status."', `conclusion`='".$conclusion."' WHERE `id` = '".$ticket_id."' LIMIT 1");
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['open_ticket'])){
				$user_phone = $_POST['user_phone'];
				$ticket_to = $_POST['ticket_to'];
				$topic = $_POST['topic'];
				$description = $_POST['description'];

				mysql_query("INSERT INTO `users_tickets_data`(`user_phone`,`ticket_to`,`topic`,`description`,`time`,`status`,`conclusion`)
							VALUES ('".$user_phone."','".$ticket_to."','".$topic."','".$description."','".time()."',0,'')")or die(mysql_error());
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			include_once('./_templates/tickets.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'map'){
			include_once('./_php/db_con.php');
			include_once('./_templates/map.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'address'){
			include_once('./_php/db_con.php');

			if(isset($_GET['update_coords'])){
				$sqlU = mysql_query("UPDATE `users_addrs` SET `coordinates` = '".$_GET['new_coords']."' WHERE `street_name_RU` = '".$_GET['street']."' AND `house` = '".$_GET['house']."' AND `coordinates` = ''") or die(mysql_error());
				echo "координаты обновлены на адресе ".$_GET['street'].", ".$_GET['house']. " - вхождений найдено ".mysql_affected_rows();
				exit;
			}
			if(isset($_POST['save_item'])){
				$id = $_POST['id'];
				$address  = $_POST['address'];
				$city_name_RU  = $_POST['city_name_RU'];
				$zipcode  = $_POST['zipcode'];
				$street_name_RU  = $_POST['street_name_RU'];
				$house = $_POST['house'];
				$appartment = $_POST['appartment'];
				$podiezd = $_POST['podiezd'];
				$podiezd_code = $_POST['podiezd_code'];
				$coordinates = $_POST['coordinates'];
				$floor = $_POST['floor'];
				$comment = $_POST['comment'];
				$domofon = $_POST['domofon'];
				if($_POST['action']== 'delete'){
					mysql_query("DELETE FROM `users_addrs` WHERE `id` = '".$id."'");
				} else {
					mysql_query("UPDATE `users_addrs` SET `address`='".$address."',`city_name_RU`='".$city_name_RU."',`zipcode`='".$zipcode."',`street_name_RU`='".$street_name_RU."',`house`='".$house."',`appartment`='".$appartment."',`podiezd`='".$podiezd."',`podiezd_code`='".$podiezd_code."',`coordinates`='".$coordinates."',`floor`='".$floor."',`comment`='".$comment."',`domofon`='".$domofon."' WHERE `id` = '".$id."'");
				}
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
			include_once('./_templates/address.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'prices'){
			include_once('./_php/db_con.php');

			if(isset($_POST['save_meal'])){
				$id = $_POST['id'];
				$name  = $_POST['name'];
				$descr = nl2br(strip_tags($_POST['description']));
				$recipe = nl2br(strip_tags($_POST['recipe']));
				$price  = $_POST['price'];
				$cat  = $_POST['cat'];
				$last_update  = time();
				$meal_image  = $_POST['meal_image'];
				$meal_weight  = $_POST['meal_weight'];
				$admin_comment  = $_POST['admin_comment'];
				$status  = $_POST['status'];
				$show  = $_POST['show'];
				$type  = $_POST['type'];
				$parent_id  = $_POST['parent_id'];
				if($_POST['action']== 'delete'){
					mysql_query("DELETE FROM `meals_data` WHERE `id` = '".$id."'");
				} else {
					$typeSql = mysql_query("SELECT * FROM `meal_types` WHERE `type` = '".$type."'");
					if(mysql_num_rows($typeSql) == 0){
						mysql_query("INSERT INTO `meal_types` (`type`) VALUES ('".$type."')");
					}

					mysql_query("UPDATE `meals_data` SET `parent_id`='".$parent_id."',`recipe`='".$recipe."',`last_update`='".$last_update."',`admin_comment`='".$admin_comment."',`show`='".$show."',`status`='".$status."',`meal_image`='".$meal_image."',`meal_weight`='".$meal_weight."',`cat`='".$cat."',`name`='".$name."',`type`='".$type."',`price`='".$price."',`description`='".$descr."' WHERE `id` = '".$id."'") or die(mysql_error());

					$eventlogmsg = "Блюдо ".$name." изменена информация";
					mysql_query("INSERT INTO `events_log_data`(`time`,`status`,`text`) VALUES('".time()."','0','".$eventlogmsg."')");
				}
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['add_meal'])){
				$name  = $_POST['name'];
				mysql_query("INSERT INTO `meals_data`(`name`) VALUES ('".$name."')") or die(mysql_error());
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}

			include_once('./_templates/prices.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'purchases'){
			include_once('./_php/db_con.php');
			if(isset($_POST['add_new_purchase'])){
				$date = str_replace(',','.',strip_tags($_POST['date']));
				$purchase = strip_tags($_POST['purchase']);
				$amount = strip_tags($_POST['amount']);
				$price = strip_tags($_POST['price']);
				$summ = strip_tags($_POST['summ']);
				mysql_query("INSERT INTO `purchases_data` (`date`,`datetime`,`purchase`,`amount`,`price`,`summ`) VALUES ('".$date."','".time()."','".$purchase."','".$amount."','".$price."','".$summ."')") or die(mysql_error());
				header('Location: '.$_SERVER['HTTP_REFERER'].'');
				exit;
			}
			include_once('./_templates/purchases.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'clients'){
			if(isset($_POST['add_comment'])){
				$uPhone = $_POST['user_phone'];
				$comment = $_POST['comment'];
				mysql_query("UPDATE `users_data` SET `comment` = '".$comment."' WHERE `user_phone` = '".$uPhone."'");
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['delete_order'])){
				$row_id = $_POST['delete_order'];
				mysql_query("DELETE FROM `turn_orders_data` WHERE `id` = '".$row_id."' LIMIT 1");
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['delete_addr'])){
				$row_id = $_POST['row_id'];
				mysql_query("DELETE FROM `users_addrs` WHERE `id` = '".$row_id."' LIMIT 1");
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			include_once('./_templates/clients.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'turn_history'){
			include_once('./_templates/turn_history.php');
		} else
		if(isset($_GET['sector']) && $_GET['sector'] == 'turn_history_global'){
			include_once('./_templates/turn_history_global.php');
		} else
		{
			$sqlCouriers = mysql_query("SELECT `name` FROM `staff_data` WHERE `group` = 'courier' AND `status` = '1' ORDER BY `last_seen` DESC") or die(mysql_error());
			while($couriersFetch = mysql_fetch_assoc($sqlCouriers)){ $couriers[]=$couriersFetch['name'];}

			if(isset($_GET['get_users_latest_order'])){
				$user_phone = $_GET['get_users_latest_order'];
				$user_address = $_GET['user_address'];
				$sqlOrders = mysql_query("SELECT * FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `address` = '".$user_address."' ORDER BY `time` DESC LIMIT 1");
				if(mysql_num_rows($sqlOrders) > 0){
					$ordersFetch = mysql_fetch_assoc($sqlOrders);
					$status = "<span style='color: green'>успешным</span>"; if($ordersFetch['status'] == 2){$status = "<span style='color: red'>отмененным</span>";}
					$sqlOrders2 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '1'");
					$sqlOrders22 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '1' AND `time`>".(time()-(60*60*24*30)));
					$sqlOrders3 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '2'");
					echo "<div style='font-size: 12px; padding-top:5px'>
							<b>Предыдущий заказ был [".$status."][".countTime($ordersFetch['time'],time())."][".date('d.m.Y H:i',$ordersFetch['time'])."]:</b>
							Всего успешных заказов:".mysql_num_rows($sqlOrders22)." [".mysql_num_rows($sqlOrders2)."]
							Отмененных заказов:".mysql_num_rows($sqlOrders3)."
							<br/>
							".nl2br($ordersFetch['order_details'])."
							<br/>
							Итого: ".($ordersFetch['summ']);
							if($ordersFetch['cash'] > 0){
								echo " - сдача с ".($ordersFetch['cash'])."";
							}
							echo
							"<br/>
							<a href='./?sector=clients&phone=".$user_phone."' target='_blank'>история заказов</a>
							</div>";
				} else {
					$sqlOrders = mysql_query("SELECT * FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' ORDER BY `time` DESC LIMIT 1");
					if(mysql_num_rows($sqlOrders) > 0){
						$ordersFetch = mysql_fetch_assoc($sqlOrders);
						$status = "<span style='color: green'>успешным</span>"; if($ordersFetch['status'] == 2){$status = "<span style='color: red'>отмененным</span>";}
						$sqlOrders2 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '1'");
						$sqlOrders3 = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND  `status` = '2'");
						echo "<div style='font-size: 12px; padding-top:5px'>
								<b>Предыдущий заказ [".$status."][".date('d.m.Y H:i',$ordersFetch['time'])."]:</b>
								Всего успешных заказов:".mysql_num_rows($sqlOrders2)."
								Отмененных заказов:".mysql_num_rows($sqlOrders3)."
								<br/>
								".nl2br($ordersFetch['order_details'])."
								<br/>
								Итого: ".($ordersFetch['summ'])."
								<br/>
								<a href='./?sector=clients&phone=".$user_phone."' target='_blank'>история заказов</a>
								</div>";
					}
				}
				$sqlOrders = mysql_query("SELECT AVG(`summ`),SUM(`summ`),COUNT(`summ`) FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."'");
				$ordersFetch = mysql_fetch_assoc($sqlOrders);
				$sqlOrders = mysql_query("SELECT AVG(`summ`),SUM(`summ`),COUNT(`summ`) FROM `turn_orders_data` WHERE `user_phone` = '".$user_phone."' AND `time`>".(time()-(60*60*24*30)));
				$ordersFetch2 = mysql_fetch_assoc($sqlOrders);
				$sqlOrdersU  = mysql_query("SELECT * FROM `users_data` WHERE `user_phone` = '".$user_phone."'");
				if(mysql_num_rows($sqlOrdersU) > 0){
					$ordersFetchU = mysql_fetch_assoc($sqlOrdersU);
					if($ordersFetchU['last_seen'] < time() - 60 * 60 * 24 * 60 || $ordersFetchU['last_seen'] == 0){
						//mysql_query("UPDATE `users_data` SET `user_bonus` = 0 WHERE `user_phone` = '".$user_phone."'");
						$ordersFetchU['user_bonus'] = 0;
					}
					echo "<div style='font-size: 12px; padding-top:5px'>
						Средний чек за 30 дней: ".floor($ordersFetch2['AVG(`summ`)'])."
						/ Средний чек за все время: ".floor($ordersFetch['AVG(`summ`)'])."</div>";
					if($ordersFetchU['comment'] != ''){
						echo "<div style='font-size: 12px; padding-top:5px; color: red'>".$ordersFetchU['comment']."</div>";
					}
				}
				exit;
			}
			if(isset($_GET['get_user_by_phone'])){
				$user_phone = strip_tags($_GET['get_user_by_phone']);
				$sql = mysql_query("SELECT * FROM `users_data` WHERE `user_phone` LIKE '%".$user_phone."%' OR `comment` LIKE '%".$user_phone."%' LIMIT 5");
				if(mysql_num_rows($sql)>0){
					while($fetch = mysql_fetch_assoc($sql)){
						$sql2 = mysql_query("SELECT * FROM `users_addrs` WHERE `user_id` = '".$fetch['id']."'");
						echo "<div class='col-50'><div style='padding: 3px;     line-height: 18px;'>".$fetch['user_phone']."<br/>
								<span style='font-size: 9px'>".$fetch['comment']."</span>
								</div></div><div class='col-50'>";
						while($fetch2 = mysql_fetch_assoc($sql2)){
							$shipment = ''; if($fetch2['shipment'] == 1){$shipment = ' (доставка)';}
							$addressStr = '';
							if(!empty($fetch2['street_name_RU'])){$addressStr .= $fetch2['street_name_RU'].',';}
							if(!empty($fetch2['house'])){$addressStr .= ' '.$fetch2['house'];}
							if(!empty($fetch2['appartment'])){$addressStr .= '-'.$fetch2['appartment'];}
							if(!empty($fetch2['podiezd'])){$addressStr .= ' п'.$fetch2['podiezd'];}
							if(!empty($fetch2['floor'])){$addressStr .= ' э'.$fetch2['floor'];}
							if(!empty($fetch2['podiezd_code'])){$addressStr .= ' код'.$fetch2['podiezd_code'];}
							if($fetch2['domofon']==0){$addressStr .= '*';}
							if(!empty($fetch2['comment'])){$comment = $fetch2['comment'];} else { if(!empty($fetch2['address'])){$comment = $fetch2['address'];} }
							/*
							echo "<div class='phone_tooltip' style='cursor: pointer'
									data=".$fetch['user_phone']."
									data-address='".$addressStr."'
									data-address-street='".$fetch2['street_name_RU']."'
									data-address-house='".$fetch2['house']."'
									data-address-appartment='".$fetch2['appartment']."'
									data-address-podiezd='".$fetch2['podiezd']."'
									data-address-floor='".$fetch2['floor']."'
									data-address-podiezd_code='".$fetch2['podiezd_code']."'
									data-address-domofon='".$fetch2['domofon']."'
									data-address-comment=''
									>
									".$addressStr.$shipment."</div>";
							*/
							echo "<div class='phone_tooltip' style='cursor: pointer'
									data=".$fetch['user_phone']."
									data-address='".$addressStr."'
									data-address-street='".$fetch2['street_name_RU']."'
									data-address-house='".$fetch2['house']."'
									data-address-appartment='".$fetch2['appartment']."'
									data-address-podiezd='".$fetch2['podiezd']."'
									data-address-floor='".$fetch2['floor']."'
									data-address-podiezd_code='".$fetch2['podiezd_code']."'
									data-address-domofon='".$fetch2['domofon']."'
									data-address-comment='".$comment."'
									>
									".$addressStr." ".$comment.$shipment."</div>";
						}
						echo "</div>";
					}
					$sql = mysql_query("SELECT * FROM `users_data` WHERE `user_phone` LIKE '%".$user_phone."%'");
					echo "<div>Всего <b>".mysql_num_rows($sql)."</b> результатов</div>";
					echo
					"<script>
						$(function(){
							$('.phone_tooltip').click(function(){
								$('input[name=client_phone]').val($(this).attr('data'));
								$('input[name=client_address_street]').val($(this).attr('data-address-street'));
								$('input[name=client_address_house]').val($(this).attr('data-address-house'));
								$('input[name=client_address_appartment]').val($(this).attr('data-address-appartment'));
								$('input[name=client_address_podiezd]').val($(this).attr('data-address-podiezd'));
								$('input[name=client_address_floor]').val($(this).attr('data-address-floor'));
								$('input[name=client_address_podeizd_code]').val($(this).attr('data-address-podiezd_code'));
								$('input[name=client_address_domofon]').val($(this).attr('data-address-domofon'));
								$('input[name=client_address]').val($(this).attr('data-address-comment'));
								$('.phone_tooltip_wrapper').css('display','none')
								var up = $(this).attr('data');
								var ua = $(this).attr('data-address');
								$.get('./?get_users_latest_order='+up+'&user_address='+ua, function(data){
										$('.user_info_tooltip').css('display','block').html(data);
								});
							});
						});
					</script>";
				} else {
					echo "Заказывает впервые";
				}
				exit;
			}
			if(isset($_GET['get_order_details'])){
				$order_details = trim(strip_tags($_GET['get_order_details']));
				$sql = mysql_query("SELECT `id`,`name`,`price`,`type`,`status`,`show` FROM `meals_data` WHERE `name` LIKE '%".$order_details."%' ORDER BY `status` DESC");
				if(mysql_num_rows($sql)>0){
					while($fetch = mysql_fetch_assoc($sql)){
						if($fetch['status'] == 0 || $fetch['show'] == 0){
							echo "<span class='order_tooltip' style='cursor: pointer' data-id='".$fetch['type']."' data='".$fetch['name']."' data-price='".$fetch['price']."'>".$fetch['name']." ".$fetch['price']."</span><br/>";
						} else {
							echo "<span class='order_tooltip' style='cursor: pointer; color: red' data-id='".$fetch['type']."' data='".$fetch['name']."' data-price='".$fetch['price']."'>".$fetch['name']." ".$fetch['price']."</span><br/>";
						}
					}
					echo
						"<script>
							$(function(){
								$('.order_tooltip').click(function(){
									$('input[name=order_meal]').val('');
									var itemPrice = isNaN($(this).attr('data-price')) ? 0 : parseInt($(this).attr('data-price'))
									$('input[name=summ]').val(parseInt($('input[name=summ]').val()) + itemPrice);
									$('.new_order_summ').html(parseInt($('input[name=summ]').val()));
									$('.price-total-numb-textarea').html('Итого: '+$('input[name=summ]').val()+'. Без сдачи сможете приготовить?');
									var bonusAmount = parseInt($('.bonus_amount').val())/100;
									if(itemPrice>0 && $('.hasbonus').prop('checked') == true){ $('.bonus').val(parseInt($('input[name=summ]').val())*bonusAmount);} else { $('.bonus').val(0);}
									if( $(this).attr('data-id') == 'P'){ $('input[name=summP]').val(parseInt( $('input[name=summP]').val()) + itemPrice);}
									if( $(this).attr('data-id') == 'S'){ $('input[name=summS]').val(parseInt( $('input[name=summS]').val()) + itemPrice);}
									if( $(this).attr('data-id') == 'K'){ $('input[name=summK]').val(parseInt( $('input[name=summK]').val()) + itemPrice);}
									if( $(this).attr('data-id') == 'E'){ $('input[name=summE]').val(parseInt( $('input[name=summE]').val()) + itemPrice);}
									$('.order_details_tooltip_wrapper').css('display','none');
									if($('.order_items').val()){
										$('.order_items').val( $('.order_items').val() + '\\n' + $(this).attr('data')+'(1)['+$(this).attr('data-price')+']{'+$(this).attr('data-id')+'}');
									} else {
										$('.order_items').val($(this).attr('data')+'(1)['+$(this).attr('data-price')+']{'+$(this).attr('data-id')+'}');
									}
								});
							});
						</script>";
				} else {
					echo "<b>По запросу <i>".$order_details."</i> ничего не найдено</b>";
				}
				exit;
			}
			if(isset($_POST['change_closed_order'])){
				$order_id = $_POST['order_id'];
				$courier = $_POST['order_courier'];
				$order_comment = $_POST['order_comment'];
				mysql_query("UPDATE `turn_orders_data` SET `courier` = '".$courier."',`comment` = '".$order_comment."' WHERE `id` = '".$order_id."'") or die(mysql_error());
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['close_order'])){
				$user_phone = $_POST['user_phone'];
				$address = $_POST['address'];
				$order_details = $_POST['order_details'];
				$summ = $_POST['summ'];
				$cash = $_POST['cash'];
				if($cash<=$summ){$cash = 0;}
				$order_id = $_POST['order_id'];
				$order_time = $_POST['order_time'];
				$time_spent = time() - $order_time;
				$order_comment = $_POST['order_comment'];
				$order_courier = $_POST['order_courier'];
				$order_status = $_POST['order_status'];
				$client_source = $_POST['client_source'];
				
				$lines = preg_split('/\n+/', trim($order_details));
				$order_details = implode("\n", $lines);

				$details = explode("\n",$order_details);
				$summ_cats = array();
				for($i=0;$i<count($details);$i++){
					preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$details[$i],$matches);
					if($matches[4] != ''){
						if(array_key_exists($matches[4],$summ_cats)){
							$summ_cats[$matches[4]] += ((int)$matches[2] * (int)$matches[3]);
						} else {
							$summ_cats[$matches[4]] = ((int)$matches[2] * (int)$matches[3]);
						}
					}
				}
				$summ_cats = json_encode($summ_cats);

				$courierSql = mysql_query("SELECT `id` FROM `staff_data` WHERE `name`='".$order_courier."' AND `group`='courier'");
				if(mysql_num_rows($courierSql)==0 && $order_courier != ''){mysql_query("INSERT INTO `staff_data`(`name`,`group`,`last_seen`,`status`) VALUES('".$order_courier."','courier','".time()."',1)") or die(mysql_error());} else {
					mysql_query("UPDATE `staff_data` SET `last_seen`='".time()."' WHERE `name`='".$order_courier."' AND `group` = 'courier' LIMIT 1");
				}

				mysql_query("UPDATE `turn_orders_data` SET `operator_id`='".$_SESSION['user_id']."',`cash`='".$cash."',`date_year` = '".date('Y', $order_time)."', `date_day` = '".date('z', $order_time)."', `date_hour` = '".date('H', $order_time)."', `date_week` = '".date('W', $order_time)."', `date_day_of_week` = '".date('N', $order_time)."', `order_details` = '".$order_details."',`summ_cats` = '".$summ_cats."',`summ` = '".$summ."',`address` = '".$address."',`user_phone` = '".$user_phone."',`comment` = '".$order_comment."',`courier` = '".$order_courier."',`time_closed` = '".time()."', `time_spent` = '".$time_spent."',`status` = '".$order_status."',`is_editable`=0 WHERE `id` = '".$order_id."'");

				//манипуляции с бонусами клиента
				$sqlu = mysql_query("SELECT `summ`,`user_phone`,`bonus` FROM `turn_orders_data` WHERE `id` = '".$order_id."'"); $fetchu = mysql_fetch_assoc($sqlu);
				if($order_status == 1){
					$bonus = $fetchu['bonus'];
					mysql_query("UPDATE `users_data` SET `last_seen` = '".time()."' WHERE `user_phone` = '".$fetchu['user_phone']."'");
				} else
				if($order_status == 2){
					$bonus = $fetchu['bonus'];
					mysql_query("UPDATE `users_data` SET `last_seen` = '".time()."',`user_bonus` = (`user_bonus` - '".$bonus."') WHERE `user_phone` = '".$fetchu['user_phone']."'");
				}
				header('Location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}
			if(isset($_POST['open_order'])){
				$order_items = strip_tags($_POST['order_items']);
				$details = explode("\n",$_POST['order_items']);
				$summ_cats = array();
				for($i=0;$i<count($details);$i++){
					preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$details[$i],$matches);
					if(array_key_exists($matches[4],$summ_cats)){
						$summ_cats[$matches[4]] += ((int)$matches[2] * (int)$matches[3]);
					} else {
						$summ_cats[$matches[4]] = ((int)$matches[2] * (int)$matches[3]);
					}
					//существует ли такой тип блюд в базе если нет то добавить
					$mealtypeSql = mysql_query("SELECT `id` FROM `meal_types` WHERE `type` = '".$matches[4]."' LIMIT 1");
					if(mysql_num_rows($mealsSql) == 0 && $_CONFIG['save_new_meal_if_not_found'] == 1){
						mysql_query("INSERT INTO `meal_types`(`type`) VALUES ('".$matches[4]."')");
					}
					
					//существует ли такое блюдо в базе - если нет то добавить новое
					$mealsSql = mysql_query("SELECT `id` FROM `meals_data` WHERE `name` = '".$matches[1]."' LIMIT 1");
					if(mysql_num_rows($mealsSql) == 0 && $_CONFIG['save_new_meal_if_not_found'] == 1){
						if((int)$matches[3] > 0 ){$price = $matches[3]/$matches[2];} else {$price = $matches[3];}
						mysql_query("INSERT INTO `meals_data`(`name`,`type`,`price`) VALUES ('".$matches[1]."','".$matches[4]."','".$price."')");
					} else {
						mysql_query("UPDATE `meals_data` SET `popularity` = (`popularity` + 1) WHERE `name` = '".$matches[1]."' LIMIT 1");
					}
				}
				$summ_cats = json_encode($summ_cats);

				$time = strtotime(str_replace('сейчас',date('H:i'),str_replace('сегодня',date('d.m.Y'),str_replace(',','',$_POST['time']))));
				$comment = strip_tags($_POST['comment']);
				$user_phone = strip_tags($_POST['client_phone']);
				$city_name_RU = "Павлодар";
				$zipcode = "14000";
				$street_name_RU = strip_tags($_POST['client_address_street']);
				$house = strip_tags($_POST['client_address_house']);
				$appartment = strip_tags($_POST['client_address_appartment']);
				$podiezd = strip_tags($_POST['client_address_podiezd']);
				$floor = strip_tags($_POST['client_address_floor']);
				$podiezd_code = strip_tags($_POST['client_address_podiezd_code']);
				$domofon = strip_tags($_POST['client_address_domofon']);
				$address_comment = strip_tags($_POST['client_address']);

				$addressStr = '';
				if(!empty($street_name_RU)){$addressStr .= $street_name_RU.',';}
				if(!empty($house)){$addressStr .= ' '.$house;}
				if(!empty($appartment)){$addressStr .= '-'.$appartment;}
				if(!empty($podiezd)){$addressStr .= ' п'.$podiezd;}
				if(!empty($floor)){$addressStr .= ' э'.$floor;}
				if(!empty($podiezd_code)){$addressStr .= ' код'.$podiezd_code;}
				if($domofon==1){$addressStr .= '*';}
				if(!empty($address_comment)){$addressStr .= ' '.$address_comment;}

				$courier = strip_tags($_POST['courier']);
				$summ = (int)$_POST['summ'];

				$cash = (int)$_POST['cash'];
				$client_discount = (int)$_POST['client_discount'];
				$client_discount_comment = strip_tags($_POST['client_discount_comment']);
				
				$coordinates = $_POST['coordinates'];
				$userSql = mysql_query("SELECT * FROM `users_data` WHERE `user_phone` = '".$user_phone."'");
				if(mysql_num_rows($userSql)>0){
					$userFetch = mysql_fetch_assoc($userSql);
					$addrSql = mysql_query("SELECT * FROM `users_addrs` WHERE `user_id` = '".$userFetch['id']."' AND `street_name_RU` = '".$street_name_RU."' AND `house` = '".$house."' AND `appartment` = '".$appartment."'");
					if($street_name_RU != 'самовывоз'){
						if(mysql_num_rows($addrSql)<1){
							mysql_query("INSERT INTO `users_addrs` (`user_id`,`city_name_RU`,`zipcode`,`street_name_RU`,`house`,`appartment`,`podiezd`,`floor`,`podiezd_code`,`domofon`,`comment`,`last_seen`,`coordinates`) VALUES
							('".$userFetch['id']."','".$city_name_RU."','".$zipcode."','".$street_name_RU."','".$house."','".$appartment."','".$podiezd."','".$floor."','".$podiezd_code."','".$domofon."','".$address_comment."','".time()."','".$coordinates."')") or die(mysql_error());
						} else {
							if($coordinates != ''){
								while($addrsFetch = mysql_fetch_assoc($addrsSql)){
									if($addrsFetch['coordinates']==''){
										mysql_query("UPDATE `users_addrs` SET `coordinates`='".$coordinates."' WHERE `id` = '".$addrsFetch['id']."'");
									}
								}
							}
							mysql_query("UPDATE `users_addrs` SET `last_seen`='".time()."',`comment`='".$address_comment."',`domofon`='".$domofon."',`podiezd_code`='".$podiezd_code."',`floor`='".$floor."',`podiezd`='".$podiezd."' WHERE `user_id` = '".$userFetch['id']."' AND `street_name_RU` = '".$street_name_RU."' AND `house` = '".$house."' AND `appartment` = '".$appartment."'");

						}
					}
				} else {
					mysql_query("INSERT INTO `users_data` (`user_phone`,`time_registered`,`last_seen`) VALUES ('".$user_phone."','".time()."','".time()."')") or die(mysql_error());
					$newUserId = mysql_insert_id();
					mysql_query("INSERT INTO `users_addrs` (`user_id`,`city_name_RU`,`zipcode`,`street_name_RU`,`house`,`appartment`,`podiezd`,`floor`,`podiezd_code`,`domofon`,`comment`) VALUES
						('".$newUserId."','".$city_name_RU."','".$zipcode."','".$street_name_RU."','".$house."','".$appartment."','".$podiezd."','".$floor."','".$podiezd_code."','".$domofon."','".$address_comment."')") or die(mysql_error());
				}
				$status = 0;$time_closed=0;
				mysql_query("INSERT INTO `turn_orders_data`(`operator_id`, `date`, `time`, `time_closed`, `date_year`, `date_day`, `date_hour`, `date_week`, `date_day_of_week`, `user_phone`, `address`, `order_details`, `summ`, `summ_cats`, `cash`, `discount`, `discount_comment`, `bonus`, `bonus_to_spend`, `comment`, `courier`, `status`, `source`) VALUES ('".$_SESSION['user_id']."','".date('d.m.Y',$time)."',
				'".$time."','".$time_closed."','".date('Y')."','".date('z')."','".date('H')."','".date('W')."','".date('N')."','".$user_phone."','".$addressStr."','".$order_items."','".$summ."','".$summ_cats."','".$cash."','".$client_discount."','".$client_discount_comment."','".$bonus."','".$bonus_to_spend."','".$comment."','".$courier."','".$status."','".$client_source."')") or die(mysql_error());
				
				/*
				include_once('./_apps/omelettebot/omelettebot.php');

				$botMsg = "<b>Новый заказ ".$addressStr."</b>";
				sendMessage($botMsg,'505043983'); //alt.zh
				$courSql = mysql_query("SELECT `telegram_chat_id` FROM `staff_data` RIGHT JOIN `staff_work_days_data` ON `staff_work_days_data`.`staff_id` = `staff_data`.`id` WHERE `group` = 'courier' AND `date_z` = '".date('z')."' AND `date_year`='".date('Y')."'");
				while($courFetch = mysql_fetch_assoc($courSql)){
					sendMessage($botMsg, $courFetch['telegram_chat_id']);
				}
				*/
				header('Location: '.$_SERVER['HTTP_REFERER'].'');
				exit;
			}

			include_once('./_templates/turn_orders.php');
		}
	} else
	{
		if(isset($_POST['login'])){
			$ulogin = $_POST['user_login'];
			$upass = md5($_POST['user_password']);

			$sql = mysql_query("SELECT * FROM `staff_data` WHERE (`login` = '".$ulogin."') AND `password` = '".$upass."'");
			if(mysql_num_rows($sql) == 1){
				$fetch = mysql_fetch_assoc($sql);
				
				$time = time();
				//регистрируем во сколько был совершен вход
				mysql_query("UPDATE `staff_data` SET `last_seen` = '".$time."' WHERE `id` = '".$fetch['id']."'");
				//фиксируем информацию о пользователе в сессию
				$_SESSION['user']['id'] = $fetch['id'];
				$_SESSION['user']['login'] = $fetch['login'];
				$_SESSION['user']['name'] = $fetch['name'];
				$_SESSION['user']['group'] = $fetch['group'];
				$_SESSION['user']['status'] = $fetch['status'];
				$_SESSION['user']['access_level'] = $fetch['access_level'];
				$_SESSION['user']['last_seen'] = $time;

			} else {
				$_SESSION['auth_error'] = 'Такой пользователь не существует';
			}
			header('Location:./');
			exit;
		}
		include_once('./_templates/login.php');
	}

	function countTime($from,$to){
		$d = $to - $from;
		if($d<60){
			$result = sprintf('%02d',$d)." сек.";
		} else
		if($d>=60 && $d<3600){
			$result = sprintf('%02d',floor($d/60))." мин.";
		} else
		if($d>=3600 && $d< 24*3600){
			$result = sprintf('%02d',(floor($d/3600))).":" . sprintf('%02d',((floor(($d - floor($d/3600)*3600)/60)))) . "";
		} else
		if($d>=24*3600 && $d<30*24*3600){
			$result = floor($d/(24*3600))." дн.";
		} else
		if($d>=30*24*3600){
			$result = floor($d/(30*24*3600))." мес. " .(floor(($d - floor($d/86400)*86400)/30))." дн.";
		}
		return $result;
	}
?>
