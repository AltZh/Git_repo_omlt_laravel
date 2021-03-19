<?php 
	session_start();
	include_once('db_con.php');
	
	$user['id'] = $_SESSION['user_id'];
	$user['group'] = $_SESSION['user_group'];
	$user['access_level'] = $_SESSION['user_access_level'];
	$user['login'] = $_SESSION['user_login'];
	$user['first_name'] = $_SESSION['user_first_name'];
	$user['last_name'] = $_SESSION['user_last_name'];
	$user['telegram_id'] = $_SESSION['telegram_chat_id'];
	$user['branch_id'] = $_SESSION['branch_id'];
	$user['phone'] = $_SESSION['user_phone'];
	$user['status'] = $_SESSION['user_status'];
	$branch_id = $user['branch_id'];
	
	$branch_sql = mysqli_query($con, "SELECT * FROM `branch_data` WHERE `id` = '".$branch_id."'") or die(mysqli_error());
	$branch_fetch = mysqli_fetch_assoc($branch_sql);
	$branch['shift_begins'] = $branch_fetch['shift_begins'];
	$branch['shift_ends'] = $branch_fetch['shift_ends'];
	
	if(isset($_GET['a']) && $_GET['a'] == 'get-user-latest-orders'){
		//пока не используется блок переехал в общий get-user-history
		if(isset($_GET['user_phone'])){
			$user_phone = (int) strip_tags($_GET['user_phone']);
			
			$usql = mysqli_query($con, "SELECT * FROM `user_phones` WHERE `phone_number` = '".$user_phone."' LIMIT 1");
			if(mysqli_num_rows($usql) == 0){
				echo "<div style='color: white; padding: 20px 40px'>";
					echo "<div style='font-size: 16px'>Пользователь не зарегистрирован</div>";
				echo "</div>";
			} else 
			{
				$sql = mysqli_query($con, "SELECT * FROM `order_data` WHERE `user_phone` = '".$user_phone."' ORDER BY `time_created` DESC LIMIT 10");
				
				echo "<div style='color: white; padding: 20px 40px'>";
					echo "<div style='font-size: 16px'>".$user_phone." история</div>";
				echo "</div>";
				echo "<div style='color: white; padding: 10px 40px' >";
					echo "<div>";
					
					if(mysqli_num_rows($sql) > 0){
						while($fetch = mysqli_fetch_assoc($sql)){
							echo "<div style='padding-bottom: 20px'>";
								echo "<div class='col w20'>".$fetch['date']."</div>";
								echo "<div class='col w80'>". nl2br($fetch['order_items'])."</div>";
							echo "</div>";
						}
					} else {
						echo "<div>В истории нет заказов с этим телефоном</div>";
					}
				
					echo "</div>";
				echo "</div>";
			}
			$sql = mysqli_query($con, "SELECT * FROM `_turn_orders_data_aksu` WHERE `user_phone` = '".$user_phone."' ORDER BY `time` DESC LIMIT 10");
			echo "<div style='color: white; padding: 20px 40px'>";
				echo "<div style='font-size: 16px'>".$user_phone." история в старой таблице _turn_orders_data_aksu</div>";
			echo "</div>";
			echo "<div style='color: white; padding: 10px 40px' >";
				echo "<div>";
				
				if(mysqli_num_rows($sql) > 0){
					while($fetch = mysqli_fetch_assoc($sql)){
						echo "<div style='padding-bottom: 20px'>";
							echo "<div class='col w20'>".$fetch['date']."</div>";
							echo "<div class='col w80'>". nl2br($fetch['order_details'])."</div>";
						echo "</div>";
					}
				} else {
					echo "<div>В истории нет заказов с этим телефоном</div>";
				}
			
				echo "</div>";
			echo "</div>";
				
			$sql = mysqli_query($con, "SELECT * FROM `_orders_data_old` WHERE `user_phone` = '".$user_phone."' ORDER BY `time_opened` DESC LIMIT 10");
			echo "<div style='color: white; padding: 20px 40px'>";
				echo "<div style='font-size: 16px'>".$user_phone." история в старой таблице orders_data</div>";
			echo "</div>";
			echo "<div style='color: white; padding: 10px 40px' >";
				echo "<div>";
				
				if(mysqli_num_rows($sql) > 0){
					while($fetch = mysqli_fetch_assoc($sql)){
						echo "<div style='padding-bottom: 20px'>";
							echo "<div class='col w20'>".$fetch['date']."<br/>".$fetch['courier']."</div>";
							echo "<div class='col w20'>". 
									$fetch['address']. "<br/>".
									$fetch['address_details']. "<br/>".
									$fetch['address_comment'].
								"</div>";
							echo "<div class='col w60'>". nl2br($fetch['order_details'])."</div>";
						echo "</div>";
					}
				} else {
					echo "<div>В истории нет заказов с этим телефоном</div>";
				}
			
				echo "</div>";
			echo "</div>";
		}
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'get-user-history'){
		if(isset($_GET['id']) && $_GET['id'] != 0){
			$user_id = (int)$_GET['id'];
			$user_sql = mysqli_query($con, "SELECT * FROM `user_data` WHERE `id` = '".$user_id."' LIMIT 1");
		} else 
		if(isset($_GET['user_phone']) && $_GET['id'] == 0){
			$user_phone = (int)$_GET['user_phone'];
			$user_sql = mysqli_query($con, "SELECT `user_data`.`id`,`first_name`,`last_name`,`birthday`,`user_data`.`time_registered`,`user_data`.`time_lastseen`,`comment`,`login`,`password`,`branch_id`,`registration_source` 
					FROM `user_data` RIGHT JOIN `user_phones` ON `user_data`.`id` = `user_phones`.`user_id` WHERE `phone_number` = '".$user_phone."' LIMIT 1") or die(mysqli_error());
			
		}
		if(mysqli_num_rows($user_sql) > 0){
			$user_fetch = mysqli_fetch_assoc($user_sql);
			$first_name = $user_fetch['first_name'];
			$last_name = $user_fetch['last_name'];
			$birthday =''; if($user_fetch['birthday']){$birthday = date('d.m.Y', (int)$user_fetch['birthday']); }
			$time_registered =''; if($user_fetch['time_registered']){$time_registered = date('d.m.Y', (int)$user_fetch['time_registered']); }
			$time_lastseen =''; if($user_fetch['time_lastseen']){$time_lastseen = date('d.m.Y', (int)$user_fetch['time_lastseen']); }
			$comment = $user_fetch['comment'];
			$login = $user_fetch['login'];
			$password = $user_fetch['password'];
			$branch_id = $user_fetch['branch_id'];
			$registration_source = $user_fetch['registration_source'];
			$user_id = $user_fetch['id'];
			$sex = $user_fetch['sex'];
			$category = $user_fetch['category'];
			mysqli_free_result($user_sql);
			
			echo "<div style='color: white; padding: 20px 40px'>";
				echo "<div style='font-size: 46px'>#".$user_id." клиент</div>";
			echo "</div>";
			echo "<div style='color: white; padding: 10px 40px' >";
				echo "<div class='col w25' >";
					echo "<div style='background: url(https://barfblog.com/wp-content/uploads/2014/11/img-thing.jpeg); background-size: cover; height: 150px; width: 150px'></div>";
				echo "</div>";
				echo "<div class='col w75'>";
					echo "<div>";
						echo "<div class='col w30' >";
							echo "<div style='color: white; padding: 0px 10px 10px 10px' >";
								echo "<input class='user-data-inpt inpt w100' value='".$first_name."' data-id='".$user_id."' data-index='first_name' placeholder='Имя'/>";
							echo "</div>";
						echo "</div>";
						echo "<div class='col w30' >";
							echo "<div style='color: white; padding: 0px 5px 10px 10px' >";
								echo "<input class='user-data-inpt inpt w100' value='".$last_name."' data-id='".$user_id."' data-index='last_name' placeholder='Фамилия'/>";
							echo "</div>";
						echo "</div>";
						echo "<div class='col w20' >";
							echo "<div style='color: white; padding: 0px 5px 10px 10px' >";
								echo "<select class='user-data-slct inpt w100' data-id='".$user_id."' data-index='sex'>";
									echo "<option value=''"; if($sex==''){echo " selected";}echo">не известно</option>";
									echo "<option value='female'"; if($sex=='female'){echo " selected";}echo">женщина</option>";
									echo "<option value='male'"; if($sex=='male'){echo " selected";}echo">мужчина</option>";
								echo "</select>";
							echo "</div>";
						echo "</div>";
						echo "<div class='col w20' >";
							echo "<div style='color: white; padding: 0px 5px 10px 10px' >";
								echo "<select class='user-data-slct inpt w100' data-id='".$user_id."' data-index='category'>";
									echo "<option value=''"; if($category==''){echo " selected";}echo">не известно</option>";
									echo "<option value='мамашка'"; if($category=='мамашка'){echo " selected";}echo">мамашка</option>";
									echo "<option value='работяга'"; if($category=='работяга'){echo " selected";}echo">работяга</option>";
									echo "<option value='семьянин'"; if($category=='семьянин'){echo " selected";}echo">семьянин</option>";
									echo "<option value='студент'"; if($category=='студент'){echo " selected";}echo">студент</option>";
									echo "<option value='другое'"; if($category=='другое'){echo " selected";}echo">другое</option>";
								echo "</select>";
							echo "</div>";
						echo "</div>";
					echo "<div>";
						echo "<div style='color: white; padding: 0px 10px' >";
							echo "<input class='user-data-inpt inpt w100' value='".$comment."' data-id='".$user_id."' data-index='comment' placeholder='Комментарий'/>";
						echo "</div>";
					echo "</div>";
					echo "<div>";
						echo "<div class='col w25' >";
							echo "<div style='color: white; padding: 10px 10px' >";
								echo "<input class='user-data-inpt inpt w100' value='".$registration_source."' data-id='".$user_id."' data-index='registration_source' placeholder='Регистрация'/>";
							echo "</div>";
						echo "</div>";
						echo "<div class='col w25' >";
							echo "<div style='color: white; padding: 10px 10px' >";
								echo "<input class='user-data-inpt inpt w100' value='".$birthday."' data-id='".$user_id."' data-index='birthday' placeholder='Дата рождения'/>";
							echo "</div>";
						echo "</div>";
						echo "<div class='col w25' >";
							echo "<div style='color: white; padding: 10px 10px' >";
								echo "<input class='user-data-inpt inpt w100' value='".$time_registered."' data-id='".$user_id."' data-index='time_registered' placeholder='Дата регистрации'/>";
							echo "</div>";
						echo "</div>";
						echo "<div class='col w25' >";
							echo "<div style='color: white; padding: 10px 0 10px 10px' >";
								echo "<input class='user-data-inpt inpt w100' value='".$time_lastseen."' data-id='".$user_id."' data-index='time_lastseen' placeholder='Последний заказ'/>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			
			echo "<script async >";
					echo 
					"$(function(){						
						$('.user-data-slct').change(function(){
							var thisId = $(this).attr('data-id');
							var thisInfoIndex = $(this).attr('data-index');
							var thisInfoVal  = $(this).children('option:selected').val();
							
							changeUserInfo(thisId, thisInfoIndex, thisInfoVal);
						});						
						$('.user-data-inpt').change(function(){
							var thisId = $(this).attr('data-id');
							var thisInfoIndex = $(this).attr('data-index');
							var thisInfoVal  = $(this).val();
							
							changeUserInfo(thisId, thisInfoIndex, thisInfoVal);
						});
					});";
			echo "</script>";
			
			echo "//life time value";
			$first_order_sql = mysqli_query($con, "SELECT `id`,`time_sent_to_kitchen` FROM `order_data` WHERE `user_id`='".$user_id."' AND `status` = 'Delivered' ORDER BY `time_sent_to_kitchen` ASC LIMIT 1");
			$first_order_fetch = mysqli_fetch_assoc($first_order_sql);
			if(mysqli_num_rows($first_order_sql) > 0){
				echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
					echo "<div class='col w20' style='color: white; padding: 10px 10px 10px 10px' >";
						echo "Первый заказ";
					echo "</div>";
					echo "<input onClick='setWindowHash(\"order:".$first_order_fetch['id']."\")' class='inpt w50' readonly value='".date('d.m.Y H:i',$first_order_fetch['time_sent_to_kitchen'])."'/>";
				echo "</div>";
			}
			$last_order_sql = mysqli_query($con, "SELECT `id`,`time_sent_to_kitchen` FROM `order_data` WHERE `user_id`='".$user_id."' AND `status` = 'Delivered' ORDER BY `time_sent_to_kitchen` DESC LIMIT 1");
			$last_order_fetch = mysqli_fetch_assoc($last_order_sql);
			if(mysqli_num_rows($last_order_sql) > 0){
				echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
					echo "<div class='col w20' style='color: white; padding: 10px 10px 10px 10px' >";
						echo "Последний заказ";
					echo "</div>";
					echo "<input onClick='setWindowHash(\"order:".$last_order_fetch['id']."\")' class='inpt w50' readonly value='".date('d.m.Y H:i',$last_order_fetch['time_sent_to_kitchen'])."'/>";
				echo "</div>";
			}
			$total_orders = mysqli_query($con, "SELECT `id` FROM `order_data` WHERE `user_id`='".$user_id."' AND `status` != 'Declined' AND `status` != 'Cancelled'");
			$total_orders_numb = mysqli_num_rows($total_orders);
			if($total_orders_numb > 0){
				echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
					echo "<div class='col w20' style='color: white; padding: 10px 10px 10px 10px' >";
						echo "Всего заказов";
					echo "</div>";
					echo "<input class='inpt w50' readonly value='".$total_orders_numb."'/>";
				echo "</div>";
			}
			$declined_orders = mysqli_query($con, "SELECT `id` FROM `order_data` WHERE `user_id`='".$user_id."' AND `status` = 'Declined'");
			$declined_orders_numb = mysqli_num_rows($declined_orders);
			if($declined_orders_numb > 0){
				echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
					echo "<div class='col w20' style='color: white; padding: 10px 10px 10px 10px' >";
						echo "Отмененных заказов";
					echo "</div>";
					echo "<input class='inpt w50' readonly value='".$declined_orders_numb."'/>";
				echo "</div>";
			}
			$avg_check_sql = mysqli_query($con, "SELECT AVG(`summ_meals_in_list`) as `avg_check` FROM `order_data` WHERE `user_id`='".$user_id."' AND `status` = 'Delivered'");
			$avg_check_fetch = mysqli_fetch_assoc($avg_check_sql);
			if(mysqli_num_rows($first_order_sql) > 0){
				echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
					echo "<div class='col w20' style='color: white; padding: 10px 10px 10px 10px' >";
						echo "Средний чек";
					echo "</div>";
					echo "<input class='inpt w50' readonly value='".$avg_check_fetch['avg_check']."'/>";
				echo "</div>";
			}
			
			$telegram_sql = mysqli_query($con, "SELECT * FROM `user_telegram` WHERE `user_id` = '".$user_id."'");
			if(mysqli_num_rows($telegram_sql) > 0){
				while($telegram_fetch = mysqli_fetch_assoc($telegram_sql)){
					$row_id = $telegram_fetch['id'];
					$chat_id = $telegram_fetch['chat_id'];
					
					echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
						echo "<input class='user-telegram-inpt inpt w100' readonly value='".$chat_id."' data-id='".$row_id."' data-index='chat_id' placeholder='chat_id'/>";
					echo "</div>";
				}
					
				echo "<script async >";
						echo 
						"$(function(){						
							$('.user-telegram-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeUserSubInfo('telegram', thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
			}
			$instagram_sql = mysqli_query($con, "SELECT * FROM `user_instagram` WHERE `user_id` = '".$user_id."'");
			if(mysqli_num_rows($instagram_sql) > 0){
				while($instagram_fetch = mysqli_fetch_assoc($instagram_sql)){
					$row_id = $instagram_fetch['id'];
					$instagram_login = $instagram_fetch['instagram_login'];
					
					echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
						echo "<input class='user-instagram-inpt inpt w100' value='".$instagram_login."' data-id='".$row_id."' data-index='instagram_login' placeholder='instagram_login'/>";
					echo "</div>";
				}
					echo "<script async >";
							echo 
							"$(function(){						
								$('.user-instagram-inpt').change(function(){
									var thisId = $(this).attr('data-id');
									var thisInfoIndex = $(this).attr('data-index');
									var thisInfoVal  = $(this).val();
									
									changeUserSubInfo('instagram', thisId, thisInfoIndex, thisInfoVal);
								});
							});";
					echo "</script>";
			}
			
			$phones_sql = mysqli_query($con, "SELECT * FROM `user_phones` WHERE `user_id` = '".$user_id."'");
			if(mysqli_num_rows($phones_sql) > 0){
				while($phone_fetch = mysqli_fetch_assoc($phones_sql)){
					$row_id = $phone_fetch['id'];
					$phone_number = $phone_fetch['phone_number'];
					$label = $phone_fetch['label'];
					
					echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
						echo "<input class='user-phone-inpt inpt w70' value='".$phone_number."' data-id='".$row_id."' data-index='phone_number' placeholder='phone_number'/>";
						echo "<input class='user-phone-inpt inpt w30' value='".$label."' data-id='".$row_id."' data-index='label' placeholder='label'/>";
					echo "</div>";
				}
				echo "<script async >";
						echo 
						"$(function(){						
							$('.user-phone-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeUserSubInfo('phones', thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
			}
			
			$fav_meals_sql = mysqli_query($con, "SELECT * FROM `user_fav_meals` WHERE `user_id` = '".$user_id."'");
			if(mysqli_num_rows($fav_meals_sql) > 0){
				while($fav_meals_fetch = mysqli_fetch_assoc($fav_meals_sql)){
					$row_id = $fav_meals_fetch['id'];
					$meal_id = $fav_meals_fetch['meal_id'];
					
					echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
						echo "user_fav_meal <input class='user-fav-meals-inpt inpt w10' value='".$meal_id."' data-id='".$row_id."' data-index='meal_id' placeholder='meal_id'/>";
					echo "</div>";
				}
				echo "<script async >";
						echo 
						"$(function(){						
							$('.user-fav-meals-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeUserSubInfo('fav_meals', thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
			}
			
			$bonuses_sql = mysqli_query($con, "SELECT * FROM `user_bonuses` WHERE `user_id` = '".$user_id."'");
			if(mysqli_num_rows($bonuses_sql) > 0){
				while($bonus_fetch = mysqli_fetch_assoc($bonuses_sql)){
					$row_id = $bonus_fetch['id'];
					$bonuses_amount = $bonus_fetch['bonuses_amount'];
					$bonuses_expire = $bonus_fetch['bonuses_expire'];
					
					echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
						echo "<div class='col w10'>Бонусы:</div>";
						echo "<input class='user-bonuses-inpt inpt w90' value='".$bonuses_amount."' data-id='".$row_id."' data-index='bonuses_amount' placeholder='bonuses_amount'/>";
						echo "<div class='col w10'>Дата сгорания</div>";
						echo "<input class='user-bonuses-inpt inpt w90'  readonly value='".date('d.m.Y H:i', $bonuses_expire)."' data-id='".$row_id."' data-index='bonuses_expire' placeholder='bonuses_expire'/>";
					echo "</div>";
				}
				echo "<script async >";
						echo 
						"$(function(){						
							$('.user-bonuses-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeUserSubInfo('bonuses', thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
			}
			
			$basket_sql = mysqli_query($con, "SELECT * FROM `user_basket_data` WHERE `user_id` = '".$user_id."'");
			if(mysqli_num_rows($basket_sql) > 0){
				while($basket_fetch = mysqli_fetch_assoc($basket_sql)){
					$row_id = $basket_fetch['id'];
					$meal_id = $basket_fetch['meal_id'];
					$time_added = $basket_fetch['time_added'];
					$amount = $basket_fetch['amount'];
					$site_session_id = $basket_fetch['site_session_id'];
					$telegram_chat_id = $basket_fetch['telegram_chat_id'];
					
					echo "<div style='color: white; padding: 10px 10px 10px 10px' >";
						echo "<input class='user-basket-inpt inpt w10' value='".$meal_id."' data-id='".$row_id."' data-index='meal_id' placeholder='meal_id'/>";
						echo "<input class='user-basket-inpt inpt w10' value='".$time_added."' data-id='".$row_id."' data-index='time_added' placeholder='time_added'/>";
						echo "<input class='user-basket-inpt inpt w10' value='".$amount."' data-id='".$row_id."' data-index='amount' placeholder='amount'/>";
						echo "<input class='user-basket-inpt inpt w10' value='".$site_session_id."' data-id='".$row_id."' data-index='site_session_id' placeholder='site_session_id'/>";
						echo "<input class='user-basket-inpt inpt w10' value='".$telegram_chat_id."' data-id='".$row_id."' data-index='telegram_chat_id' placeholder='telegram_chat_id'/>";
					echo "</div>";
				}
				echo "<script async >";
						echo 
						"$(function(){						
							$('.user-basket-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeUserSubInfo('basket', thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
			}
			
			$addrs_sql = mysqli_query($con, "SELECT `user_addrs`.*,`city`,`street`,`house` FROM `user_addrs` 
					RIGHT JOIN `addr_data` ON `user_addrs`.`addr_id` = `addr_data`.`id`
					WHERE `user_id` = '".$user_id."'");
			//`addr_id`, `appartment`, `entrance`, `floor`, `has_intercom`, `label`, `time_added`, `time_lastseen`, `comment`
			if(mysqli_num_rows($addrs_sql) > 0){
				while($addrs_fetch = mysqli_fetch_assoc($addrs_sql)){
					$row_id = $addrs_fetch['id'];
					$appartment = $addrs_fetch['appartment'];
					$entrance = $addrs_fetch['entrance'];
					$floor = $addrs_fetch['floor'];
					$has_intercom = $addrs_fetch['has_intercom'];
					$label = $addrs_fetch['label'];
					$comment = $addrs_fetch['comment'];

					$addr_id = $addrs_fetch['addr_id'];
					$addr_city = $addrs_fetch['city'];
					$addr_street = $addrs_fetch['street'];
					$addr_house = $addrs_fetch['house'];
					
					echo "<div>";
						echo "<div>";
							echo "<div style='color: white; padding: 10px 10px 0 10px' >";
								echo "<div class='addr-data-id' data-id='".$addr_id."'>".$addr_city.", ".$addr_street.", ".$addr_house."</div>";
							echo "</div>";
						echo "</div>";
						echo "<div>";
							echo "<div class='col w10'>";
								echo "<div style='color: white; padding: 0 10px 10px 10px' >";
									echo "<input class='user-addr-inpt inpt w100' value='".$appartment."' data-index='appartment' placeholder='appartment' data-id='".$row_id."'/>";
								echo "</div>";
							echo "</div>";
							echo "<div class='col w10'>";
								echo "<div style='color: white; padding: 0 10px 10px 10px' >";
									echo "<input class='user-addr-inpt inpt w100' value='".$entrance."' data-index='entrance' placeholder='entrance' data-id='".$row_id."'/>";
								echo "</div>";
							echo "</div>";
							echo "<div class='col w10'>";
								echo "<div style='color: white; padding: 0 10px 10px 10px' >";
									echo "<input class='user-addr-inpt inpt w100' value='".$floor."' data-index='floor' placeholder='floor' data-id='".$row_id."'/>";
								echo "</div>";
							echo "</div>";
							echo "<div class='col w10'>";
								echo "<div style='color: white; padding: 0 10px 10px 10px' >";
									echo "<input class='user-addr-inpt inpt w100' value='".$has_intercom."' data-index='has_intercom' placeholder='has_intercom' data-id='".$row_id."'/>";
								echo "</div>";
							echo "</div>";
							echo "<div class='col w10'>";
								echo "<div style='color: white; padding: 0 10px 10px 10px' >";
									echo "<input class='user-addr-inpt inpt w100' value='".$label."' data-index='label' placeholder='label' data-id='".$row_id."'/>";
								echo "</div>";
							echo "</div>";
							echo "<div class='col w40'>";
								echo "<div style='color: white; padding: 0 10px 10px 10px' >";
									echo "<input class='user-addr-inpt inpt w100' value='".$comment."' data-index='comment' placeholder='comment' data-id='".$row_id."'/>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
				echo "<script async >";
						echo 
						"$(function(){
							$('.addr-data-id').click(function(){
								var thisId = $(this).attr('data-id');
								openRightDrawer('show-address-details', {id: thisId});
							});
								
							$('.user-addr-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeUserSubInfo('address', thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
			}
							
			echo "<div style='color: white; padding: 10px 0; font-size: 13px;' >";
				echo "<div style='border-bottom: 1px dashed white'></div>";
				$orders_sql = mysqli_query($con, "SELECT `id`,`date`,`status`,`address_street`,`address_house`,`summ_meals_in_list`,`summ_discounted_meals`,`delivery_cost`,`discount`,`discount_comment`,`beznal`,`cash`,`admin_comment`,`order_items` FROM `order_data` WHERE `user_id` = '".$user_id."' ORDER BY `time_created` DESC LIMIT 50");
				if(mysqli_num_rows($orders_sql) > 0){
					while($order_fetch = mysqli_fetch_assoc($orders_sql)){
						$order_id = $order_fetch['id'];
						$date = $order_fetch['date'];
						$street = $order_fetch['address_street'];
						$house = $order_fetch['address_house'];
						
						$status = $order_fetch['status'];
						$order_items = json_decode($order_fetch['order_items'], true);
						
						$summ_meals_in_list = $order_fetch['summ_meals_in_list'];
						$summ_discounted_meals = $order_fetch['summ_discounted_meals'];
						$delivery_cost = $order_fetch['delivery_cost'];
						$discount = $order_fetch['discount'];
						$discount_comment = $order_fetch['discount_comment'];
						$beznal = $order_fetch['beznal'];
						$cash = $order_fetch['cash'];
						
						$admin_comment = $order_fetch['admin_comment'];
						
						echo "<a class='noa' href='#order:".$order_id."'>";
							echo "<div class='user-history-order-single' style='border-bottom: 1px dashed white'>";
								echo "<div class='col w15'>";
									echo "<div style='padding: 5px'>";
										echo $date;
									echo "</div>";
								echo "</div>";
								echo "<div class='col w25'>";
									echo "<div style='padding: 5px'>";
										echo $street.", ".$house;
									echo "</div>";
								echo "</div>";
								echo "<div class='col w10'>";
									echo "<div style='padding: 5px'>";
										echo $status;
									echo "</div>";
								echo "</div>";
								echo "<div class='col w20'>";
									echo "<div style='padding: 5px'>";
										foreach($order_items as $item){
											echo $item['name'] . " " .  $item['amount'] . "x" .  $item['price'] . "<br/>";
										}
									echo "</div>";
								echo "</div>";
								echo "<div class='col w10' align='right'>";
									echo "<div style='padding: 5px'>";
										echo $summ_meals_in_list."<br/>";
										if($summ_discounted_meals){ echo $summ_discounted_meals."<br/>";}
										if($delivery_cost){ echo $delivery_cost."<br/>";}
										if($discount){ echo $discount.":".$discount_comment."<br/>";}
										if($beznal){ echo $beznal."<br/>";}
										if($cash){ echo $cash."<br/>";}
									echo "</div>";
								echo "</div>";
								echo "<div class='col w20'>";
									echo "<div style='padding: 5px'>";
										echo $admin_comment;
									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</a>";
					}
				}
				
			echo "</div>";
		} else 
		{
			echo "<div style='color: white; padding: 40px'>";
				echo "<div style='font-size: 46px'>Пользователь не найден</div>";
			echo "</div>";
			echo "<div style='color: white; padding: 40px'>";
				echo "<div style='font-size: 18px'><a class='noa add-user-to-database' data-phone='".$user_phone."'>Добавить</a></div>";
			echo "</div>";
			
			echo "<script>";
				echo "$(function(){
					$('.add-user-to-database').click(function(){
						var this_phone = $(this).attr('data-phone');
						addNewUser(this_phone, function(){
							openRightDrawer('get-user-history', {user_phone: '".$user_phone."'});							
						});						
					});
					
				});";
			echo "</script>";
		}
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'show-address-details'){
		if(isset($_GET['id']) && $_GET['id'] > 0){
			$id = (int) strip_tags($_GET['id']);
			
			$addr_sql = mysqli_query($con, "SELECT `id`,`city`,`street`,`house`,`prev_street_name`,`delivery_cost`,`last_update`,`coords`,`label` FROM `addr_data` WHERE `id` = '".$id."' LIMIT 1");
			if(mysqli_num_rows($addr_sql) == 1){
				$addr_fetch = mysqli_fetch_assoc($addr_sql);
				$addr_id = $addr_fetch['id'];
				$city = $addr_fetch['city'];
				$street = $addr_fetch['street'];
				$house = $addr_fetch['house'];
				$coords = $addr_fetch['coords'];
				$prev_street_name = $addr_fetch['prev_street_name'];
				$label = $addr_fetch['label'];
				$delivery_cost = $addr_fetch['delivery_cost'];
				
				$addr_str = $city . ', ' . $street . ', ' . $house;
				mysqli_free_result($addr_sql);
				
				echo "<div style='color: white; padding: 20px 40px'>";
					echo "<div style='font-size: 46px'>".$addr_str."</div>";
				echo "</div>";
				echo "<div style='color: white; padding: 20px 40px' >";
					echo "<input class='addr-inpt inpt w15' value='".$city."' data-id='".$addr_id."' data-index='city' placeholder='city' />";
					echo "<input class='addr-inpt inpt w40' value='".$street."' data-id='".$addr_id."' data-index='street' placeholder='street'/>";
					echo "<input class='addr-inpt inpt w10' value='".$house."' data-id='".$addr_id."' data-index='house' placeholder='house'/>";
				echo "</div>";
				echo "<div style='color: white; padding: 0px 0 10px 40px' >";
					echo "Координаты";
				echo "</div>";
				echo "<div style='color: white; padding: 0 0 20px 40px' >";
					echo "<input class='addr-inpt inpt w50' data-id='".$addr_id."' value='".$coords."' data-index='coords' placeholder='Координаты'/>";
				echo "</div>";
				echo "<div class='col w50'>";
					echo "<div style='color: white; padding: 0 20px' >";
						echo "<div style='color: white; padding: 20px 0 10px 20px' >";
							echo "Предыдущие названия улицы";
						echo "</div>";
						echo "<div style='color: white; padding: 0 0 10px 20px' >";
							echo "<input class='addr-inpt inpt w100' data-id='".$addr_id."' value='".$prev_street_name."' data-index='prev_street_name'/>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<div class='col w25'>";
					echo "<div style='color: white; padding: 20px 0 10px 20px' >";
						echo "Label";
					echo "</div>";
					echo "<div style='color: white; padding: 0 0 10px 20px' >";
						echo "<input class='addr-inpt inpt w100' data-id='".$addr_id."' value='".$label."' data-index='label'/>";
					echo "</div>";
				echo "</div>";
				echo "<div class='col w25'>";
					echo "<div style='color: white; padding: 20px 0 10px 20px' >";
						echo "Стоимость доставки";
					echo "</div>";
					echo "<div style='color: white; padding: 0 0 10px 20px' >";
						echo "<input class='addr-inpt inpt w100' data-id='".$addr_id."' value='".$delivery_cost."' data-index='delivery_cost'/>";
					echo "</div>";
				echo "</div>";
				echo "<div style='color: white; padding: 20px 0 20px 40px' >";
					echo "Последнее обновление адреса было: " . date('d.m.Y H:i', $addr_fetch['last_update']);
				echo "</div>";
				echo "<script async >";
						echo 
						"$(function(){				
							$('.addr-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeAddrInfo(thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
					
				echo "<div style='color: white; padding: 20px 40px 10px 40px' >";
					echo "<input class='addr-search-on-map-inpt inpt w100' value='".$addr_str."'/>";
				echo "</div>";
				echo "<div style='color: white; padding: 0 40px 40px 40px' >";
					include_once('../_templates/inc/map.php');
				echo "</div>";
				exit;
			}
		} else
		if(isset($_GET['order_id']) && $_GET['order_id'] > 0){
			$order_id = (int) strip_tags($_GET['order_id']);
			
			$order_sql = mysqli_query($con, "SELECT `address_city`,`address_street`,`address_house`,`address_details`,`address_comment`,`user_id` FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
			$order_fetch = mysqli_fetch_assoc($order_sql);
			
			$user_id = $order_fetch['user_id'];
			$city = $order_fetch['address_city'];
			$street = $order_fetch['address_street'];
			$house = $order_fetch['address_house'];
			$details = $order_fetch['address_details'];
			$comment = $order_fetch['address_comment'];
			
			$addr_str = $city . ', ' . $street . ', ' . $house;
			
			$addr_sql = mysqli_query($con, "SELECT `id`,`prev_street_name`,`delivery_cost`,`last_update`,`coords`,`label` FROM `addr_data` WHERE `city` = '".$city."'  AND `street` = '".$street."'   AND `house` = '".$house."' LIMIT 1");
			$addr_id = 0;
			
			if(mysqli_num_rows($addr_sql) == 1){
				$addr_fetch = mysqli_fetch_assoc($addr_sql);
				$addr_id = $addr_fetch['id'];
			}
			mysqli_free_result($addr_sql);
			
			echo "<div style='color: white; padding: 20px 40px'>";
				echo "<div style='font-size: 46px'>".$addr_str."</div>";
			echo "</div>";
			echo "<div style='color: white; padding: 20px 40px' >";
				echo "<input class='order-addr-inpt inpt w15' value='".$city."' data-id='".$order_id."' data-index='address_city'/>";
				echo "<input class='order-addr-inpt inpt w40' value='".$street."' data-id='".$order_id."' data-index='address_street'/>";
				echo "<input class='order-addr-inpt inpt w10' value='".$house."' data-id='".$order_id."' data-index='address_house'/>";
			echo "</div>";
			echo "<div style='color: white; padding: 20px 40px' >";
				echo "<input class='order-addr-inpt inpt w50' value='".$details."' data-id='".$order_id."' data-index='address_details'/>";
				echo "<input class='order-addr-inpt inpt w50' value='".$comment."' data-id='".$order_id."' data-index='address_comment'/>";
			echo "</div>";
			echo "<div style='color: white; padding: 0px 0 10px 40px' >";
				echo "Координаты";
			echo "</div>";
			echo "<div style='color: white; padding: 0 0 20px 40px' >";
				echo "<input class='addr-inpt inpt w50' data-id='".$addr_id."' value='".$addr_fetch['coords']."' data-index='coords' placeholder='Координаты'/>";
			echo "</div>";
			if($addr_id != 0){
				echo "<div class='col w50'>";
					echo "<div style='color: white; padding: 0 20px' >";
						echo "<div style='color: white; padding: 20px 0 10px 20px' >";
							echo "Предыдущие названия улицы";
						echo "</div>";
						echo "<div style='color: white; padding: 0 0 10px 20px' >";
							echo "<input class='addr-inpt inpt w100' data-id='".$addr_id."' value='".$addr_fetch['prev_street_name']."' data-index='prev_street_name'/>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<div class='col w25'>";
					echo "<div style='color: white; padding: 20px 0 10px 20px' >";
						echo "Label";
					echo "</div>";
					echo "<div style='color: white; padding: 0 0 10px 20px' >";
						echo "<input class='addr-inpt inpt w100' data-id='".$addr_id."' value='".$addr_fetch['label']."' data-index='label'/>";
					echo "</div>";
				echo "</div>";
				echo "<div class='col w25'>";
					echo "<div style='color: white; padding: 20px 0 10px 20px' >";
						echo "Стоимость доставки";
					echo "</div>";
					echo "<div style='color: white; padding: 0 0 10px 20px' >";
						echo "<input class='addr-inpt inpt w100' data-id='".$addr_id."' value='".$addr_fetch['delivery_cost']."' data-index='delivery_cost'/>";
					echo "</div>";
				echo "</div>";
				echo "<div style='color: white; padding: 20px 0 20px 40px' >";
					echo "Последнее обновление адреса было: " . date('d.m.Y H:i', $addr_fetch['last_update']);
				echo "</div>";
				echo "<script async >";
						echo 
						"$(function(){						
							$('.addr-inpt').change(function(){
								var thisId = $(this).attr('data-id');
								var thisInfoIndex = $(this).attr('data-index');
								var thisInfoVal  = $(this).val();
								
								changeAddrInfo(thisId, thisInfoIndex, thisInfoVal);
							});
						});";
				echo "</script>";
				
				if($user_id != 0){
					$user_addr_sql = mysqli_query($con, "SELECT * FROM `user_addrs` WHERE `addr_id` = '".$addr_id."' AND `user_id` = '".$user_id."'");
					if(mysqli_num_rows($user_addr_sql) == 0){
						echo "<div style='color: white; padding: 0 20px' >";
							echo "<div style='color: white; padding: 20px 0 10px 20px' >";
								echo "У пользователя нет такого адреса. <a class='add-new-address-to-user' addr-id='".$addr_id."' user-id='".$user_id."'>Добавить</a> пользователю";
							echo "</div>";
						echo "</div>";
						
					}
				}
			} else {
				echo "<div style='color: white; padding: 0 20px' >";
					echo "<div style='color: white; padding: 20px 0 10px 20px' >";
						echo "Такой адрес не найден в базе, <a class='add-new-address'>добавить</a>?";
					echo "</div>";
				echo "</div>";
			}
			echo "<script async >";
					echo 
					"$(function(){
						$('.add-new-address-to-user').click(function(){
							var addr_id = $(this).attr('addr-id');
							var user_id = $(this).attr('user-id');
							
							addNewAddrToUser(addr_id, user_id, function(){
								openRightDrawer('show-address-details', {order_id: ".$order_id."});							
							});
						});
						$('.add-new-address').click(function(){
							var city = $('input.order-addr-inpt[data-index=address_city]').val();
							var street = $('input.order-addr-inpt[data-index=address_street]').val();
							var house = $('input.order-addr-inpt[data-index=address_house]').val();
							var coords = $('input.addr-inpt[data-index=coords]').val();
							
							addNewAddr(city, street, house, coords, function(){
								openRightDrawer('show-address-details', {order_id: ".$order_id.",city: city, street: street, house: house});							
							});
						});
						$('.order-addr-inpt').change(function(){
							var thisId = $(this).attr('data-id');
							var thisInfoIndex = $(this).attr('data-index');
							var thisInfoVal  = $(this).val();
							
							var city = $('input.order-addr-inpt[data-index=address_city]').val();
							var street = $('input.order-addr-inpt[data-index=address_street]').val();
							var house = $('input.order-addr-inpt[data-index=address_house]').val();
							var coords = $('input.addr-inpt[data-index=coords]').val();
							
							if(thisId && thisInfoIndex){
								changeOrderInfo(thisId, thisInfoIndex, thisInfoVal, function(){
									openRightDrawer('show-address-details', {order_id: ".$order_id.",city: city, street: street, house: house});							
								});
							}
						});
					});";
			echo "</script>";
				
			echo "<div style='color: white; padding: 20px 40px 10px 40px' >";
				echo "<input class='addr-search-on-map-inpt inpt w100' value='".$addr_str."'/>";
			echo "</div>";
			echo "<div style='color: white; padding: 0 40px 40px 40px' >";
				include_once('../_templates/inc/map.php');
			echo "</div>";
			exit;
		} else 
		if(isset($_GET['city'])){
			
			$city = $_GET['city'];
			$street = $_GET['street'];
			$house = $_GET['house'];
			
			$addr_str = $city . ", " . $street . ", " . $house;
			
			echo "<div style='color: white; padding: 20px 40px'>";
				echo "<div style='font-size: 46px'>".$addr_str."</div>";
			echo "</div>";
			
			echo "<div style='color: white; padding: 20px 40px 10px 40px' >";
				echo "<input class='addr-search-on-map-inpt inpt w100' value='".$addr_str."'/>";
				echo "<input class='addr-search-on-map-inpt inpt w100'  data-index='coords' value=''/>";
			echo "</div>";
			echo "<div style='color: white; padding: 0 40px 40px 40px' >";
				include_once('../_templates/inc/map.php');
			echo "</div>";
			exit;
		}
	} else 
	if(isset($_GET['a']) && $_GET['a'] == 'show-ticket-details'){
		$ticket_id = (int)$_GET['id'];
		$ticket_sql = mysqli_query($con, "SELECT * FROM `user_tickets` WHERE `id` = '".$ticket_id."' LIMIT 1");
		$ticket_fetch = mysqli_fetch_assoc($ticket_sql);
		mysqli_free_result($ticket_sql);
		
		$user_id = $ticket_fetch['user_id'];
		$order_id = $ticket_fetch['order_id'];
		$meal_name = $ticket_fetch['meal_name'];
		$time = $ticket_fetch['time'];
		$status = $ticket_fetch['status'];
		$text = $ticket_fetch['text'];
		$answer = $ticket_fetch['answer'];
		
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>#".$ticket_id." тикет</div>";
		echo "</div>";
		echo "<div style='color: white; padding: 20px 40px' >";
			echo "<input class='ticket-inpt inpt w15' readonly value='".$user_id."' data-id='".$ticket_id."' data-index='user_id' placeholder='user_id' />";
			echo "<input class='ticket-inpt inpt w40' readonly value='".$order_id."' data-id='".$ticket_id."' data-index='order_id' placeholder='order_id'/>";
			echo "<input class='ticket-inpt inpt w40' value='".$meal_name."' data-id='".$ticket_id."' data-index='meal_name' placeholder='meal_name'/>";
		echo "</div>";
		echo "<div style='color: white; padding: 20px 40px' >";
			echo "<input class='ticket-inpt inpt w15' readonly value='".date('d.m.Y H:i',$time)."' data-id='".$ticket_id."' data-index='time' placeholder='time' />";
			echo "<input class='ticket-inpt inpt w40' value='".$status."' data-id='".$ticket_id."' data-index='status' placeholder='status'/>";
		echo "</div>";
				
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Текст жалобы";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<textarea class='ticket-txtr inpt' data-id='".$ticket_id."' data-index='text' style='height: 149px; width: 509px;'>".$text."</textarea>";
			echo "</div>";
		echo "</div>";
				
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Ответ жалобы";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<textarea class='ticket-txtr inpt' data-id='".$ticket_id."' data-index='answer' style='height: 149px; width: 509px;'>".$answer."</textarea>";
			echo "</div>";
		echo "</div>";
		
		echo "<script async >";
				echo 
				"$(function(){				
					$('.ticket-inpt').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).val();
						
						changeTicketInfo(thisId, thisInfoIndex, thisInfoVal);
					});
					
					
					$('.ticket-txtr').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $( this ).val().replace(/\\n/g, '!br');
						
						changeTicketInfo(thisId, thisInfoIndex, thisInfoVal);
					});
				});";
		echo "</script>";
		
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'edit-order-time'){
		$order_id = (int)$_GET['id'];
		$order_sql = mysqli_query($con, "SELECT * FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
		$order_fetch = mysqli_fetch_assoc($order_sql);
		
		$is_preorder = $order_fetch['is_preorder'];
		
		$time_created = ''; if($order_fetch['time_created']){ $time_created = date('H:i:s',$order_fetch['time_created']); }
		$time_sent_to_kitchen = ''; if($order_fetch['time_sent_to_kitchen']){ $time_sent_to_kitchen = date('H:i:s',$order_fetch['time_sent_to_kitchen']); }
		$time_sent_to_delivery = ''; if($order_fetch['time_sent_to_delivery']){ $time_sent_to_delivery = date('H:i:s',$order_fetch['time_sent_to_delivery']); }
		$time_delivered = ''; if($order_fetch['time_delivered']){ $time_delivered = date('H:i:s',$order_fetch['time_delivered']); }
		$time_rated = ''; if($order_fetch['time_rated']){ $time_rated = date('H:i:s',$order_fetch['time_rated']); }
		
		$time_desired_date = ''; if($order_fetch['time_desired_delivery_from']){ $time_desired_date = date('d.m.Y',$order_fetch['time_desired_delivery_from']); }
		
		$time_desired_delivery_from = ''; if($order_fetch['time_desired_delivery_from']){ $time_desired_delivery_from = date('H:i:s',$order_fetch['time_desired_delivery_from']); }
		$time_desired_delivery_to = ''; if($order_fetch['time_desired_delivery_to']){ $time_desired_delivery_to = date('H:i:s',$order_fetch['time_desired_delivery_to']); }
		$time_est_delivery_from = ''; if($order_fetch['time_est_delivery_from']){ $time_est_delivery_from = date('H:i:s',$order_fetch['time_est_delivery_from']); }
		$time_est_delivery_to = ''; if($order_fetch['time_est_delivery_to']){ $time_est_delivery_to = date('H:i:s',$order_fetch['time_est_delivery_to']); }
		
		if($time_est_delivery_from == ''){$time_est_delivery_from_autocomp = date('H:i:s',($order_fetch['time_created'] + 60 * 60));}
		if($time_est_delivery_to == ''){$time_est_delivery_to_autocomp = date('H:i:s',($order_fetch['time_created'] + 80 * 60));}
		
		mysqli_free_result($order_sql);
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>#".$order_id." заказ</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w40'>";
				echo "Заказ создан";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w100' readonly value='".$time_created."' data-id='".$order_id."' data-index='time_created' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Передан кухне";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w100' readonly value='".$time_sent_to_kitchen."' data-id='".$order_id."' data-index='time_sent_to_kitchen' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Желаемая дата доставки";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w50' readonly value='".$time_desired_date."' data-id='".$order_id."' data-index='time_desired_delivery_date' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Желаемое время доставки";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w50' value='".$time_desired_delivery_from."' data-id='".$order_id."' data-index='time_desired_delivery_from' placeholder='--:--:--'/>";
				echo "<input class='time-inpt inpt w50' value='".$time_desired_delivery_to."' data-id='".$order_id."' data-index='time_desired_delivery_to' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Ориентировочное время доставки";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w50' value='".$time_est_delivery_from."' data-id='".$order_id."' autocomp='".$time_est_delivery_from_autocomp."' data-index='time_est_delivery_from' placeholder='--:--:--'/>";
				echo "<input class='time-inpt inpt w50' value='".$time_est_delivery_to."' data-id='".$order_id."' autocomp='".$time_est_delivery_to_autocomp."' data-index='time_est_delivery_to' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Передан доставке";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w100' readonly value='".$time_sent_to_delivery."' data-id='".$order_id."' data-index='time_sent_to_delivery' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Доставлен";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w100' readonly value='".$time_delivered."' data-id='".$order_id."' data-index='time_delivered' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Время оценки";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w100' readonly value='".$time_rated."' data-id='".$order_id."' data-index='time_rated' placeholder='--:--:--'/>";
			echo "</div>";
			
			echo "<div class='col w40'>";
				echo "Предзаказ";
			echo "</div>";
			echo "<div class='col w40'>";
				echo "<input class='time-inpt inpt w100' value='".$is_preorder."' data-id='".$order_id."' data-index='is_preorder' placeholder='is_preorder'/>";
			echo "</div>";
			
		echo "</div>";
		echo "<script async >";
				echo 
				"$(function(){				
					$('.time-inpt').dblclick(function(){
						var autocomp = $(this).attr('autocomp');
						if(autocomp && autocomp != ''){
							$(this).val(autocomp);
							var thisId = $(this).attr('data-id');
							var thisInfoIndex = $(this).attr('data-index');
							var thisInfoVal  = $(this).val();
							
							changeOrderInfo(thisId, thisInfoIndex, thisInfoVal, function(){
								openRightDrawer('edit-order-time', {id:thisId});
							});
						}
					});
					$('.time-inpt').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).val();
						
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal, function(){
							openRightDrawer('edit-order-time', {id:thisId});
						});
					});
				});";
		echo "</script>";
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'edit-order-items'){
		$order_id = (int)$_GET['id'];
		$order_sql = mysqli_query($con, "SELECT `order_items`,`summ_meals_in_list`,`summ_discounted_meals` ,`beznal` ,`cash` ,`delivery_cost` FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
		$order_fetch = mysqli_fetch_assoc($order_sql);
		
		$order_items = $order_fetch['order_items'];
		$summ_meals_in_list = $order_fetch['summ_meals_in_list'];
		$summ_discounted_meals = $order_fetch['summ_discounted_meals'];
		$beznal = $order_fetch['beznal'];
		$cash = $order_fetch['cash'];
		$delivery_cost = $order_fetch['delivery_cost'];
		
		mysqli_free_result($order_sql);
		
		include_once('./right_drawer_incs/edit-order-summs.php');
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'edit-order-summs'){
		$order_id = (int)$_GET['id'];
		$order_sql = mysqli_query($con, "SELECT `order_items`,`summ_meals_in_list`,`summ_discounted_meals` ,`beznal` ,`discount` ,`cash` ,`delivery_cost` FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
		$order_fetch = mysqli_fetch_assoc($order_sql);
		
		$order_items = $order_fetch['order_items'];
		$order_items_obj = json_decode($order_fetch['order_items'], true);
		$order_items_str = '';
		foreach($order_items_obj as $item){
			$b = ''; if($item['is_bonus'] == 1){$b = ' - Бонус!';};
			$order_items_str .= $item['name'] . ' ' .$item['amount'] . 'x' .$item['price'] . $b . '<br/>';
		}
		
		$summ_meals_in_list = $order_fetch['summ_meals_in_list'];
		$summ_discounted_meals = $order_fetch['summ_discounted_meals'];
		$beznal = $order_fetch['beznal'];
		$cash = $order_fetch['cash'];
		$discount = $order_fetch['discount'];
		$delivery_cost = $order_fetch['delivery_cost'];
		
		mysqli_free_result($order_sql);
		
		echo 
			"<div style='color: white; padding: 40px; min-width: 350px'>
				<div style='font-size: 46px'>#".$order_id." заказ</div>
			</div>
			
			<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >
				<div class='col w20'>
					В заказе
				</div>
				<div class='col w80'>
					<div class='col order-items-contents'>".$order_items_str."</div>
					
				</div>
			</div>
			<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >
			
				<div style='display: flex; flex-orientation: row'>
					<div class='w20'>
						сумма
					</div>
					<div class='w80'>
						<input class='summs-inpt inpt w40' readonly value='".$summ_meals_in_list."' data-id='".$order_id."' data-index='summ_meals_in_list' placeholder='summ_meals_in_list'/>
					</div>
				</div>
				
				<div style='display: flex; flex-orientation: row'>
					<div class='w20'>
						бонусные
					</div>
					<div class='w80'>
						<input class='summs-inpt inpt w40' readonly value='".$summ_discounted_meals."' data-id='".$order_id."' data-index='summ_discounted_meals' placeholder='summ_discounted_meals'/>
					</div>
				</div>
				
				<div style='display: flex; flex-orientation: row'>
					<div class='w20'>
						Итого
					</div>
					<div class='w80'>
						<div style='padding: 5px 0 40px 0px' class='summ_after_discounted_meals'>".($summ_meals_in_list - $summ_discounted_meals)."</div>
					</div>
				</div>
				
				<div style='display: flex; flex-orientation: row'>
					<div class='w20' style='padding: 4px 0 0 0'>
						доставка
					</div>
					<div class='w40'>
						<input class='summs-inpt inpt w40' value='".$delivery_cost."' data-id='".$order_id."' data-index='delivery_cost' placeholder='delivery_cost'
							style='border: none; border-bottom: 1px solid; border-radius: 0px; background: #333; color: white; padding: 4px 0 0 4px; width: 90%;' />
					</div>
					<div class='w40'>
						<div style='display: flex; flex-orientation: row'>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='delivery_cost' data-value='0'>беспл.</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='delivery_cost' data-value='350'>350</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='delivery_cost' data-value='500'>500</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='delivery_cost' data-value='700'>700</div>
						</div>
					</div>
				</div>
				
				<div style='display: flex; flex-orientation: row'>
					<div class='w20' style='padding: 4px 0 0 0'>
						скидка
					</div>
					<div class='w40'>
						<input class='summs-inpt inpt w40' value='".$discount."' data-id='".$order_id."' data-index='discount' placeholder='discount'
							style='border: none; border-bottom: 1px solid; border-radius: 0px; background: #333; color: white; padding: 4px 0 0 4px' />
					</div>
				</div>
				
				<div style='display: flex; flex-orientation: row'>
					<div class='w20' style='padding: 4px 0 0 0'>
						безнал
					</div>
					<div class='w40'>
						<input class='summs-inpt inpt w40' value='".$beznal."' data-id='".$order_id."' data-index='beznal' placeholder='beznal'
							style='border: none; border-bottom: 1px solid; border-radius: 0px; background: #333; color: white; padding: 4px 0 0 4px; width: 90%;' />
					</div>
					<div class='w40'>
						<div style='display: flex; flex-orientation: row'>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='beznal' data-value='0'>0.00</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed' class='autocomplete' data-id='".$order_id."' data-index='beznal' data-value='".($summ_meals_in_list - $summ_discounted_meals + $delivery_cost)."'>".($summ_meals_in_list - $summ_discounted_meals + $delivery_cost)."</div>
						</div>
					</div>
				</div>
				<div style='display: flex; flex-orientation: row'>
					<div class='w20' style='padding: 4px 0 0 0'>
						наличные
					</div>
					<div class='w40'>
						<input class='summs-inpt inpt w40' value='".$cash."' data-id='".$order_id."' data-index='cash' placeholder='cash'
							style='border: none; border-bottom: 1px solid; border-radius: 0px; background: #333; color: white; padding: 4px 0 0 4px' />
					</div>
					<div class='w40'>
						<div style='display: flex; flex-orientation: row'>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='cash' data-value='0'>без сд.</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='cash' data-value='2000'>2k</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='cash' data-value='5000'>5k</div>
							<div style='padding: 3px 5px; cursor: pointer; border-bottom: 1px dashed; margin-right: 4px' class='autocomplete' data-id='".$order_id."' data-index='cash' data-value='10000'>10k</div>
						</div>
					</div>
				</div>
			</div>
			<script>
				$(function(){				
					$('.autocomplete').click(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal = $(this).attr('data-value');
						
						$('input.summs-inpt[data-index='+ thisInfoIndex +']').val(thisInfoVal);
						
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
					$('.summs-inpt').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).val();
						
						var summ_meals_in_list = $('[data-index=summ_meals_in_list]').val();
						var summ_discounted_meals = $('[data-index=summ_discounted_meals]').val();
						
						$('[data-index=beznal]').val();
						
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
				});				
			</script>";
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'edit-order-status'){
		$order_id = (int)$_GET['id'];
		$order_sql = mysqli_query($con, "SELECT `status` FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
		$order_fetch = mysqli_fetch_assoc($order_sql);
		$status = $order_fetch['status'];
		mysqli_free_result($order_sql);
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>#".$order_id." заказ</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed; min-width: 450px' >";
			echo "<div class='col w20'>";
				echo "Статус";
			echo "</div>";
			echo "<div class='col w80'>";
					echo "<select class='order-slct inpt w70' data-id='".$order_id."' data-index='status'>";
						echo "<option value='' "; if($courier == ''){echo " selected";} echo" disabled>не назначен</option>";
					
					$statuses_sql = mysqli_query($con, "SELECT * FROM `order_statuses`");
					while($status_fetch = mysqli_fetch_assoc($statuses_sql)){
						echo "<option value='".$status_fetch['name']."'"; if($status == $status_fetch['name']){echo " selected";} echo">".$status_fetch['name']."</option>";
					}
					echo "</select>";
			echo "</div>";
		echo "</div>";
		
		echo "<script >";
				echo 
				"$(function(){
					$('.order-slct').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).children('option:selected').val();
						
						if(thisInfoIndex == 'status' && thisInfoVal == 'Declined'){
							
						}
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
				});";
		echo "</script>";
		
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'edit-order-courier'){
		$order_id = (int)$_GET['id'];
		$order_sql = mysqli_query($con, "SELECT `courier` FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
		$order_fetch = mysqli_fetch_assoc($order_sql);
		$courier = $order_fetch['courier'];
		mysqli_free_result($order_sql);
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>#".$order_id." заказ</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed; min-width: 450px' >";
			echo "<div class='col w20'>";
				echo "Курьер";
			echo "</div>";
			echo "<div class='col w80'>";
					echo "<select class='order-slct inpt w70' data-id='".$order_id."' data-index='courier'>";
						echo "<option value='' "; if($courier == ''){echo " selected";} echo" disabled>не назначен</option>";
					
					$courier_sql = mysqli_query($con, "SELECT * FROM `branch_staff` WHERE `branch_id`='".$branch_id."' AND `group` = 'courier' ORDER BY `time_lastseen` DESC");
					while($courier_fetch = mysqli_fetch_assoc($courier_sql)){
						echo "<option value='".$courier_fetch['first_name']."'"; if($courier == $courier_fetch['first_name']){echo " selected";} echo">".$courier_fetch['first_name']."</option>";
					}
					echo "</select>";
			echo "</div>";
		echo "</div>";
		
		echo "<script >";
				echo 
				"$(function(){
					$('.order-slct').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).children('option:selected').val();
						
						if(thisInfoIndex == 'status' && thisInfoVal == 'Declined'){
							
						}
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
				});";
		echo "</script>";
		
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'show-order-details'){
		$order_id = (int)$_GET['id'];
		$order_sql = mysqli_query($con, "SELECT * FROM `order_data` WHERE `id` = '".$order_id."' LIMIT 1");
		$order_fetch = mysqli_fetch_assoc($order_sql);
		mysqli_free_result($order_sql);
		
		$date = $order_fetch['date'];
		$date_year = $order_fetch['date_year'];
		$date_day = $order_fetch['date_day'];
		$date_hour = $order_fetch['date_hour'];
		$date_week = $order_fetch['date_week'];
		$date_day_of_week = $order_fetch['date_day_of_week'];
		
		$is_preorder = $order_fetch['is_preorder'];
		$time_created = date('d.m.Y H:i', $order_fetch['time_created']);
		$time_sent_to_kitchen = ''; 
		if($order_fetch['time_sent_to_kitchen']){
			$time_sent_to_kitchen = date('d.m.Y H:i', $order_fetch['time_sent_to_kitchen']);
		}
		$time_sent_to_delivery = '';
		if($order_fetch['time_sent_to_delivery']){
			$time_sent_to_delivery = date('d.m.Y H:i', $order_fetch['time_sent_to_delivery']);
		}
		$time_desired_delivery_from = '';
		$time_desired_delivery_to = '';
		if($is_preorder == 1){
			$time_desired_delivery_from = date('d.m.Y H:i', $order_fetch['time_desired_delivery_from']);
			$time_desired_delivery_to = date('d.m.Y H:i', $order_fetch['time_desired_delivery_to']);
		}
		$time_est_delivery_from = date('d.m.Y H:i', $order_fetch['time_est_delivery_from']);
		$time_est_delivery_to =date('d.m.Y H:i', $order_fetch['time_est_delivery_to']);
		
		$time_delivered = $order_fetch['time_delivered'];
		$time_rated = $order_fetch['time_rated'];
		
		$user_id = $order_fetch['user_id'];
		$user_phone = $order_fetch['user_phone'];
		
		$address_city = $order_fetch['address_city'];
		$address_street = $order_fetch['address_street'];
		$address_house = $order_fetch['address_house'];
		$address_details = $order_fetch['address_details'];
		$address_comment = $order_fetch['address_comment'];
		
		$order_items = $order_fetch['order_items'];
		
		$summ_meals_in_list = $order_fetch['summ_meals_in_list'];
		$summ_discounted_meals = $order_fetch['summ_discounted_meals'];
		$delivery_cost = $order_fetch['delivery_cost'];
		$beznal = $order_fetch['beznal'];
		$discount = $order_fetch['discount'];
		$discount_comment = $order_fetch['discount_comment'];
		$cash = $order_fetch['cash'];
		
		$courier = $order_fetch['courier'];
		$user_rated = $order_fetch['user_rated'];
		
		$user_on_send_comment = $order_fetch['user_on_send_comment'];
		$closed_comment = $order_fetch['closed_comment'];
		$admin_comment = $order_fetch['admin_comment'];
		
		$check_printed = $order_fetch['check_printed'];
		$is_notified = $order_fetch['is_notified'];
		$status = $order_fetch['status'];
		$order_source = $order_fetch['order_source'];
		$branch_id = $order_fetch['branch_id'];
		
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>#".$order_id." заказ</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Статус";
			echo "</div>";
			echo "<div class='col w80'>";
					echo "<select class='order-slct inpt w70' data-id='".$order_id."' data-index='status'>";
					$order_statuses_sql = mysqli_query($con, "SELECT * FROM `order_statuses`");
					while($order_statuses_fetch = mysqli_fetch_assoc($order_statuses_sql)){
						echo "<option value='".$order_statuses_fetch['value']."'"; if($status == $order_statuses_fetch['value']){echo " selected";} echo">".$order_statuses_fetch['name']."</option>";
					}
					echo "</select>";
					
					$ticket_sql = mysqli_query($con, "SELECT `id` FROM `user_tickets` WHERE `order_id` = '".$order_id."' LIMIT 1");
					if(mysqli_num_rows($ticket_sql) == 1){
						$ticket = mysqli_fetch_assoc($ticket_sql);
						echo 
						"<div class='col w20'>
							<a class='noa'>
								<div class='show-ticket-btn col btn ghostBtn' data-id='".$ticket['id']."' style='padding: 9px 15px;'>Has ticket</div>
							</a>
						</div>";

					} else {
						echo 
						"<div class='col w20'>
							<a class='noa'>
								<div class='add-new-ticket-btn col btn ghostBtn' data-order-id='".$order_id."' data-user-id='".$user_id."' style='padding: 9px 15px;'>Open ticket</div>
							</a>
						</div>";
					}
			echo "</div>";
		echo "</div>";
		
		//блюда в заказе
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Блюда в заказе";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<textarea class='order-txtr inpt w100' data-id='".$order_id."' data-index='order_items' style='min-height: 120px'>".$order_fetch['order_items']."</textarea>";
			echo "</div>";
		echo "</div>";
		
		//адрес доставки
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Адрес доставки";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$address_city."' data-index='address_city' placeholder='address_city'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$address_street."' data-index='address_street' placeholder='address_street'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$address_house."' data-index='address_house' placeholder='address_house'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$address_details."' data-index='address_details' placeholder='address_details'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$address_comment."' data-index='address_comment' placeholder='address_comment'/>";
			echo "</div>";		
		echo "</div>";
		
		//клиент
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Клиент";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$user_id."' data-index='user_id' placeholder='user_id'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$user_phone."' data-index='user_phone' placeholder='user_phone'/>";
			echo "</div>";
		echo "</div>";
		
		//сумма и оплата
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Сумма и оплата";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "summ_meals_in_list";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$summ_meals_in_list."' data-index='summ_meals_in_list' placeholder='summ_meals_in_list'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "summ_discounted_meals";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$summ_discounted_meals."' data-index='summ_discounted_meals' placeholder='summ_discounted_meals'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "delivery_cost";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$delivery_cost."' data-index='delivery_cost' placeholder='delivery_cost'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "beznal";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$beznal."' data-index='beznal' placeholder='beznal'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "discount";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$discount."' data-index='discount' placeholder='discount'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "discount_comment";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$discount_comment."' data-index='discount_comment' placeholder='discount_comment'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "cash";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$cash."' data-index='cash' placeholder='cash'/>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		
		
		//коммент клиента
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Коммент клиента";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<textarea class='order-txtr inpt w100' data-id='".$order_id."' data-index='user_on_send_comment'>".$order_fetch['user_on_send_comment']."</textarea>";
			echo "</div>";
		echo "</div>";
		
		//коммент админа
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Коммент админа";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<textarea class='order-txtr inpt w100' data-id='".$order_id."' data-index='admin_comment'>".$order_fetch['admin_comment']."</textarea>";
			echo "</div>";
		echo "</div>";
		
		//коммент после закрытия
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Закрыт с комментом";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<textarea class='order-txtr order-items inpt w100' data-id='".$order_id."' data-index='closed_comment'>".$order_fetch['closed_comment']."</textarea>";
			echo "</div>";
		echo "</div>";
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Предзаказ";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='inpt bigchkbx' type='checkbox' value='".$is_preorder."'"; if($is_preorder == 1){ echo " checked";}echo"onclick='return false;'/>";
			echo "</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Время оформления";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$date."' data-index='date' placeholder='date'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$date_year."' data-index='date_year' placeholder='date_year'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$date_day."' data-index='date_day' placeholder='date_day'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$date_hour."' data-index='date_hour' placeholder='date_hour'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$date_week."' data-index='date_week' placeholder='date_week'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$date_day_of_week."' data-index='date_day_of_week' placeholder='date_day_of_week'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$is_preorder."' data-index='is_preorder' placeholder='is_preorder'/>";
			echo "</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Время исполнения";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "created";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$time_created."' data-index='time_created' placeholder='time_created'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "sent_to_kit";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$time_sent_to_kitchen."' data-index='time_sent_to_kitchen' placeholder='time_sent_to_kitchen'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "sent_to_del";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$time_sent_to_delivery."' data-index='time_sent_to_delivery' placeholder='time_sent_to_delivery'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "desired_delivery from to";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w50' data-id='".$order_id."' value='".$time_desired_delivery_from."' data-index='time_desired_delivery_from' placeholder='time_desired_delivery_from'/>";
						echo "<input class='order-inpt inpt w50' data-id='".$order_id."' value='".$time_desired_delivery_to."' data-index='time_desired_delivery_to' placeholder='time_desired_delivery_to'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "estimated_delivery from to";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w50' data-id='".$order_id."' readonly value='".$time_est_delivery_from."' data-index='time_est_delivery_from' placeholder='time_est_delivery_from'/>";
						echo "<input class='order-inpt inpt w50' data-id='".$order_id."' readonly value='".$time_est_delivery_to."' data-index='time_est_delivery_to' placeholder='time_est_delivery_to'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "delivered";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$time_delivered."' data-index='time_delivered' placeholder='time_delivered'/>";
					echo "</div>";
				echo "</div>";
				echo "<div>";
					echo "<div class='col w20'>";
						echo "rated";
					echo "</div>";
					echo "<div class='col w80'>";
						echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$time_rated."' data-index='time_rated' placeholder='time_rated'/>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
			
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Курьер и оценка";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$courier."' data-index='courier' placeholder='courier'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$user_rated."' data-index='user_phone' placeholder='user_rated'/>";
			echo "</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Чек распечатан";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='inpt bigchkbx' type='checkbox' value='".$check_printed."'"; if($check_printed == 1){ echo " checked";}echo"onclick='return false;'/>";
			echo "</div>";
		echo "</div>";
		
		echo "<div style='color: white; padding: 10px 40px; border-bottom: 1px dashed' >";
			echo "<div class='col w20'>";
				echo "Источник и филиал";
			echo "</div>";
			echo "<div class='col w80'>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' readonly value='".$order_source."' data-index='order_source' placeholder='order_source'/>";
				echo "<input class='order-inpt inpt w100' data-id='".$order_id."' value='".$branch_id."' data-index='branch_id' placeholder='branch_id'/>";
			echo "</div>";
		echo "</div>";
		
		echo "<script >";
				echo 
				"$(function(){
					$('.show-ticket-btn').click(function(){
						var thisId = $(this).attr('data-id');
						openRightDrawer('show-ticket-details', {id: thisId});
					});
					$('.add-new-ticket-btn').click(function(){
						addNewTicket(".$order_id.",".$user_id.", function(ticket_id){
							openRightDrawer('show-ticket-details', {id: ticket_id});							
						});
					});
					$('.order-slct').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).children('option:selected').val();
						
						if(thisInfoIndex == 'status' && thisInfoVal == 'Declined'){
							
						}
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
					$('.order-inpt').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $(this).val();
						
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
					$('.order-txtr').change(function(){
						var thisId = $(this).attr('data-id');
						var thisInfoIndex = $(this).attr('data-index');
						var thisInfoVal  = $( this ).val().replace(/\\n/g, '!br');
						
						changeOrderInfo(thisId, thisInfoIndex, thisInfoVal);
					});
					$('.order-txtr.order-items').change(function(){
						countTotalPriceBlock();
					});
					
					function countTotalPriceBlock() {
						console.log('пересчет сумм в заказе');
					}
				})";
		echo "</script>";
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'est-del-time'){
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>Обещаемое время доставки</div>";
			echo "<div style='padding: 20px'>";
				echo "<select class='slct est-time-slct' data-type='minutes-from' style='font-size: 30px;'>";
					echo "<option value='40-60'>Минут 40 - час</option>";
					echo "<option value='60-80' selected>Час +-20минут</option>";
					echo "<option value='70-90'>Час 10 - час 30</option>";
					echo "<option value='90-110'>Час 30 - час 50</option>";
				echo "</select>";
			echo "</div>";
			echo "<div style='padding: 20px'>";
				echo "<a class='noa btn green' onClick='closeRightDrawer()'>Готово</a>";
			echo "</div>";
		echo "</div>";
		echo "<script async >";
				echo 
				"$(function(){
					$('.est-time-slct').change(function(){
						var thisVal  = $(this).children('option:selected').val();
						
						$('input[name=addr-est_delivery]').val(thisVal);
					});
				})";
		echo "</script>";
		exit;
	} else
	if(isset($_GET['a']) && $_GET['a'] == 'choose-date'){
		$month = ['', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
		
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>Выбор даты</div>";
			
			echo "<div style='padding: 20px'>";
				echo "<select class='slct date-change' data-type='day' style='font-size: 30px; border-radius: 4px 0 0 4px'>";
					for($i = 1; $i < 32; $i++){
						$s=sprintf('%02d', $i);
						echo "<option value='".$s."'"; if($s==date('d')){echo " selected";} echo">".$s."</option>";
					}
				echo "</select>";
				echo "<select class='slct date-change' data-type='month' style='font-size: 30px; border-radius: 0'>";
					for($i = 1; $i < 13; $i++){
						$s=sprintf('%02d', $i);
						echo "<option value='".$s."'"; if($s==date('m')){echo " selected";} echo">".$month[$i]."</option>";
					}
				echo "</select>";
				echo "<select class='slct date-change' data-type='year' style='font-size: 30px; border-radius: 0 4px 4px 0'>";
					for($i = date('Y'); $i < date('Y') + 2; $i++){
						echo "<option value='".$i."'"; if($i==date('Y')){echo " selected";} echo">".$i."</option>";
					}
				echo "</select>";
			echo "</div>";
			
			echo "<div style='padding: 20px'>";
				echo "<select class='slct time-change' data-type='hours-from' style='font-size: 30px;'>";
					for($i = ($branch['shift_begins'] - 1); $i <= ($branch['shift_ends']); $i++){
						$s=sprintf('%02d', $i);
						echo "<option value='".$s."'"; if($s==date('H')){echo " selected";} echo">".$s."</option>";
					}
				echo "</select>";
				echo "<span style='font-size: 40px'> : </span>";
				echo "<select class='slct time-change' data-type='minutes-from' style='font-size: 30px;'>";
					echo "<option value='00'>00</option>";
					echo "<option value='15'>15</option>";
					echo "<option value='30'>30</option>";
					echo "<option value='45'>45</option>";
				echo "</select>";
				echo "<span style='font-size: 40px'> - </span>";
				echo "<select class='slct time-change' data-type='hours-to' style='font-size: 30px;'>";
					for($i = ($branch['shift_begins'] - 1); $i <= ($branch['shift_ends']); $i++){
						$s=sprintf('%02d', $i);
						echo "<option value='".$s."'"; if($s==date('H')){echo " selected";} echo">".$s."</option>";
					}
				echo "</select>";
				echo "<span style='font-size: 40px'> : </span>";
				echo "<select class='slct time-change' data-type='minutes-to' style='font-size: 30px;'>";
					echo "<option value='00'>00</option>";
					echo "<option value='15'>15</option>";
					echo "<option value='30'>30</option>";
					echo "<option value='45'>45</option>";
				echo "</select>";
			echo "</div>";
			
			echo "<div style='padding: 20px'>";
				echo "<a class='noa btn green' onClick='closeRightDrawer()'>Готово</a>";
			echo "</div>";
			
			
			echo "<script>";
				echo "var cuttentdate = new Date('"; echo date('m.d.Y H:i:s')."');
					var currentTimeStamp = cuttentdate.getTime(); ";
				echo 
				"$(function(){
					$('.date-change').change(function(){
						var day = $('.date-change[data-type=day]').children(':selected').val();
						var month = $('.date-change[data-type=month]').children(':selected').val();
						var year = $('.date-change[data-type=year]').children(':selected').val();
						var selected_date = new Date('\''+month+'.'+day+'.'+year+'\' 23:59:59');
						
						if( selected_date.getTime() < currentTimeStamp){
							notify('Дата не может быть из прошлого','Неверная дата',1);
							$('input[name=date]').val('');
						} else {
							if( day+'.'+month+'.'+year == '".date('d.m.Y')."'){
								$('input[name=date]').val('');
							} else {
								$('input[name=date]').val(day+'.'+month+'.'+year);
							}
						}
					});
					$('.time-change[data-type=minutes-from]').change(function(){
						var hours_from = $('.time-change[data-type=hours-from]').children(':selected').val();
						var minutes_from = parseInt($('.time-change[data-type=minutes-from]').children(':selected').val());
						
						if(minutes_from != 45){
							$('.time-change[data-type=minutes-to]').val(minutes_from + 15);
						} else {
							$('.time-change[data-type=hours-to]').val(parseInt(hours_from) + 1);
							$('.time-change[data-type=minutes-to]').val('00');
						}
					});
					$('.time-change[data-type=hours-from]').change(function(){
						var hours_from = $('.time-change[data-type=hours-from]').children(':selected').val();
						var minutes_from = parseInt($('.time-change[data-type=minutes-from]').children(':selected').val());
						
						if(minutes_from != 45){
							$('.time-change[data-type=hours-to]').val(hours_from);
							$('.time-change[data-type=minutes-to]').val(minutes_from + 15);
						} else {
							$('.time-change[data-type=hours-to]').val(parseInt(hours_from) + 1);
							$('.time-change[data-type=minutes-to]').val('00');
						}
					});
					$('.time-change').change(function(){
						var hours_from = $('.time-change[data-type=hours-from]').children(':selected').val();
						var minutes_from = $('.time-change[data-type=minutes-from]').children(':selected').val();
						var hours_to = $('.time-change[data-type=hours-to]').children(':selected').val();
						var minutes_to = $('.time-change[data-type=minutes-to]').children(':selected').val();
						
						var day = $('.date-change[data-type=day]').children(':selected').val();
						var month = $('.date-change[data-type=month]').children(':selected').val();
						var year = $('.date-change[data-type=year]').children(':selected').val();
						
						if( day+'.'+month+'.'+year == '".date('d.m.Y')."' && parseInt(hours_to) < ".date('H')." + 1){
							notify('Предзаказ минимум заранее за час','Неверный час',1);
							$('input[name=time]').val('');
						} else {
							if(parseInt(hours_to) < parseInt(hours_from)){
								notify('Час должен быть больше или равен','Неверный час',1);
								$('input[name=time]').val('');
							} else
							if(parseInt(hours_to) > parseInt(hours_from)){
								$('input[name=time]').val(hours_from+':'+minutes_from+'-'+hours_to+':'+minutes_to);
							} else
							if(hours_to == hours_from){
								if(parseInt(minutes_to) > parseInt(minutes_from)){
									$('input[name=time]').val(hours_from+':'+minutes_from+'-'+hours_to+':'+minutes_to);							
								} else {
									$('input[name=time]').val('');
									notify('Интервал минимум 15 минут','Неверные минуты',1);								
								}
							}
						}
					});
				})";
			echo "</script>";
			
		echo "</div>";
		exit;
	} else 
	if(isset($_GET['a']) && $_GET['a'] == 'show-courier-summs'){
		echo "<div style='color: white; padding: 40px'>";
			echo "<div style='font-size: 46px'>Итоги смены</div>";
			
			echo "<div style='padding: 20px 0'>";
				echo "<input class='inpt user-password-inpt' type='password' />";
				echo "<input class='btn green user-password-confirm' type='submit' />";
			echo "</div>";
			echo "<script>";
				echo 
				"$(function(){
					$('.user-password-confirm').click(function(){
						var password = $('.user-password-inpt').val();
						$.get('./_php/user_functions.php?a=confirm_password&p='+password, function(data){
							if(data){
								if(data == 'success'){
									openRightDrawer('show-courier-summs-authorized');
								}
							}
						});
					});
				});";
			echo "</script>";
		echo "</div>";
	} else 
	if(isset($_GET['a']) && $_GET['a'] == 'show-courier-summs-authorized'){
		$d = date('d.m.Y'); //date
		$couriers = array();
		$beznal = 0;
		
		$sql_str = "SELECT * FROM `order_data` 
				WHERE `date` = '".$d."' AND `branch_id` = '".$branch_id."' AND (`status` = 'Delivered' OR `status` = 'Sent to Delivery' OR `status` = 'Sent to Kitchen')";
		$orders_sql = mysqli_query($con, $sql_str);
		while($orders_fetch = mysqli_fetch_assoc($orders_sql)){
			$end_summ = $orders_fetch['summ_meals_in_list'] - $orders_fetch['summ_discounted_meals'] + $orders_fetch['delivery_cost'] - $orders_fetch['discount'] - $orders_fetch['beznal'];
			$courier = $orders_fetch['courier'];
			if($courier){
				if(array_key_exists($courier, $couriers)){
					$couriers[$courier]['summ'] += $end_summ;
					$couriers[$courier]['count'] += 1;
				} else {
					$couriers[$courier]['summ'] = $end_summ;
					$couriers[$courier]['count'] = 1;
				}
				
				$beznal += $orders_fetch['beznal'];
			}
		}
		
		echo "<div style='color: white; padding: 40px; min-width: 750px'>";
			echo "<div style='font-size: 46px'>Итоги смены</div>";
			
			echo "<div style='padding: 20px 0; font-size: 24px'>";
				foreach($couriers as $cour => $data){
					echo "<div class='' style='padding: 10px; border-bottom: 1px dashed #fff'>";
						echo "<div class='col w25'>" . $cour . "</div>";
						echo "<div class='col w25'>" . number_format($data['summ'], 0, '', ' ') . "</div>";
						echo "<div class='col w25'>" . $data['count'] . "</div>";
					echo "</div>";
				}
				echo "<div class=''  style='padding: 10px; border-bottom: 1px dashed #fff'>";
					echo "<div class='col w25'>Безнал: </div>";
					echo "<div class='col w25'>" . number_format($beznal, 0, '', ' ') . "</div>";
					echo "<div class='col w25'></div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	}
?>