<?php
?>
<html>
	<head>
		<title>Прием заказов</title>
		<link rel="icon" sizes="192x192" href="./_css/fav_icon.png">
		<link rel="icon" sizes="128x128" href="./_css/fav_icon.png">
		<link rel="apple-touch-icon" sizes="128x128" href="./_css/fav_icon.png">
		<link rel="apple-touch-icon-precomposed" sizes="128x128" href="./_css/fav_icon.png" />
		<link rel='stylesheet' href='./_css/style.css'></link>
<!--
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
-->
		<script src='./_js/jquery-latest.js'></script>
		<style>
			.error{padding: 10px; background: #EF9A9A;border: 1px solid #D32F2F; border-radius: 3px; color: #B71C1C; margin: 10px 0;}
			.row.header{background: whitesmoke;font-weight: bold;border-bottom: 2px solid #aaa;}
			.row.header div{padding: 3px}
			.row div{padding: 3px; font-size: 13px}
			.row:hover{background-color: whitesmoke}

			.noa{text-decoration: none; color: inherit}
			.noa:hover{text-decoration: underline; color: inherit}

			.c1hart-bar-single:first-child{padding: 0 5px}
			.chart-bar-single{display: inline-block; vertical-align: bottom; position: relative; border-radius: 2px 2px 0 0; color: white; width: 10px; padding-top: 3px; text-align:center; padding: 0 3px; font-size: 10px; b1order: 1px solid white; margin-right: 0px}
			.chart-bar-single:hover{color: black; border: 1px solid inherit; p1adding: 0 5px; cursor: pointer}
			.chart-bar-single.history{opacity: 0.8}
			.chart-bar-single.history:hover{opacity: 1}
			.chart-bar-single div{position: absolute; left:0; bottom:4px; width: 100%; text-align: center}

			.order-contents.minimized{overflow:hidden; height: 20px}

			.hidden{display: none}

			.slct-else{width:100%; border: 1px dashed #aaa; background:white}
			.slct-else.minimized{width:22px}

			.orders-courier{cursor: pointer}
			.orders-courier-all{cursor: pointer}

			.placeholder{font-style: italic; cursor: pointer; color: #333}
			.placeholder:hover{ text-decoration: underline; color: #aaa}

			textarea{border: 1px dashed #aaa}

			select{padding: 5px;}
		</style>
	</head>
	<body>
		<div class='page col-100' style='background: white'>
			<div class='col-100' style='min-height: 100%'>
				<div class='content' style='background: #333'>
					<div class='nav col-10' style='background: #191919; color: white; min-height: 100%; font-size: 14px; line-height: 28px'>
						<?php
							include_once('./_templates/inc/nav_left.php');
						?>
						<div style='padding: 20px 0 0 10px'>
								<?php 
									$months = ["","января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
									$dayNames = ["","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Воскресенье"];
									echo "<div><h3>".date('d')." ".$months[date('m')]."</h3></div>";
									echo "<div style='padding: 10px 0; font-size: 13px'>";
										echo "<div>".$dayNames[date('N')]."</div>";
										echo "<div>День года ".(date('z')+1)."/".(date('z', strtotime('31 December Y'))+1)."</div>";
										echo "<div>Неделя года ".date('W')."/".date('W', strtotime('December 28th'))."</div>";
									echo "</div>";
								?>
								<div class='date-time' style='font-weight: bold'>99-99-99</div>

								<div class='session' style='height: 5px; margin-top: 10px; width: 165px'></div>
								<div style='font-size: 10px' class='hrsLeftText'>...</div>
								<script>
									$(function(){
										function updateTime(){
											var d = new Date();
											var hrs = d.getHours();
											var min = d.getMinutes();
											var sec = d.getSeconds();
											hrsstr = hrs; if(hrs < 10){hrsstr = '0' + hrs}
											minstr = min; if(min < 10){minstr = '0' + min}
											secstr = sec; if(sec < 10){secstr = '0' + sec}
											$('.date-time').html(hrs + ':' + minstr + ':' + secstr);
											var sessionHoursLeft = 22 - hrs;
											if(sessionHoursLeft >= 0 && hrs > 9){
												$('.session').html('');
												for(i=0;i<sessionHoursLeft;i++){
													$('.session').append("<div style='width:3%; display: inline-block; vertical-align: top; height: 4px; background: #8bc34a;border-right: 2px solid white'></div>");
												}
												for(i=sessionHoursLeft;i<12;i++){
													$('.session').append("<div style='width:3%; display: inline-block; vertical-align: top; height: 4px; background: #c1c1c1;border-right: 2px solid white'></div>");
												}
												//$('.session').css('width', ((sessionHoursLeft * 100 / 12)) + '%');
												hrsStr = ''; if(sessionHoursLeft < 1){ hrsStr = 'конец смены ';}
												else if(sessionHoursLeft < 10){ hrsStr = '0' +sessionHoursLeft+ ':';}
												else if(sessionHoursLeft >= 10){ hrsStr = sessionHoursLeft+ ':';}
												minStr = ''; if(59 - min > 1){minStr = (59 - min) + '';} else {minStr = '00:'+(59-sec);}
												$('.hrsLeftText').html(hrsStr + minStr);
											}
										}
										updateTime();
										setInterval(updateTime, 1000);
									});
								</script>
							</div>
							<div <?php if($_SESSION['user_access_level'] >= 7){ echo "style='display: block'";} else {echo "style='display: none'". $_SESSION['user_access_level']; } ?> >
									<div>
										<div style='font-family: Cuprum; margin-top: 20px; margin-left: 10px'>
											<div id='recentOrders' style='width: 100%; height: 67px;'></div>
											<br/>
											<div id='recentOrders2' style='width: 100%; height: 67px;'></div>
											<br/>
											<div id='recentOrders6' style='width: 100%; height: 37px'></div>
											<br/>
								<!--
											<div>
												<div style='display: inline-block; padding: 5px; background: #d32f2f'></div>
												<div style='display: inline-block; padding: 5px; background: #f44336'></div>
												<div style='display: inline-block; padding: 5px; background: #ff9800'></div>
												<div style='display: inline-block; padding: 5px; background: #ffcd38'></div>
												<div style='display: inline-block; padding: 5px; background: #ffee62'></div>
												<div style='display: inline-block; padding: 5px; background: #a2ce6e'></div>
												<div style='display: inline-block; padding: 5px; background: #6fbe72'></div>
											</div>
								-->
										</div>
									</div>
								</div>
					</div>
					<div class='col-90' style='background: white'>
						<div class='col-70'>
							<div style='padding: 20px'>
								<h3 class='hider' tgt='open-orders-form-wrapper'>Прием заказов <a href='./'>очистить форму</a></h3>
								<div class='panel open-orders-form-wrapper'>
									<div style=''>
										<form id='send_order_form' method='post'>
											<div style='padding: 10px 0 0 0' >
												<div c1lass='col-90'>
													<div>
														<div class='col-20'>
															<div style='font-size: 10px'>Телефон</div>
															<input name='client_phone' autocomplete='off' class='client_info inpt col-100' placeholder='' value='<?if(isset($_GET['phone'])){echo$_GET['phone'];}?>' />
														</div>
														<!--div class='col-20'>
															<div style='font-size: 10px'>Источник</div>
															<select name='client_source' class='client_info inpt col-100' style='padding: 4px 0; background: white'>
																	<option value='' selected>не известен</option>
																	<option value='whatsapp'>whatsapp</option>
																	<option value='site'>site</option>
																	<option value='87471092682'>747-1092682</option>
																	<option value='87019656448'>701-9656448</option>
																	<option value='87182781932'>78-19-32</option>
															</select>
														</div-->
														<div class='col-15'>
															<div style='font-size: 10px'>Улица</div>
															<input name='client_address_street' autocomplete='off' class='addrs-input addrs-street client_address_street client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['street'])){echo$_GET['street'];}?>'  />
															<input type='hidden' class='coords' value='' name='coordinates'/>
														</div>
														<div class='col-5'>
															<div style='font-size: 10px'>Дом</div>
															<input name='client_address_house' autocomplete='off' class='addrs-input addrs-house client_address_house client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['house'])){echo$_GET['house'];}?>'  />
														</div>
														<div class='col-5'>
															<div style='font-size: 10px'>Кв.</div>
															<input name='client_address_appartment' autocomplete='off' class='client_address_appartment client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['appartment'])){echo$_GET['appartment'];}?>'  />
														</div>
														<div class='col-5'>
															<div style='font-size: 10px'>Под.</div>
															<input name='client_address_podiezd' autocomplete='off' class='client_address_podiezd client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['podiezd'])){echo$_GET['podiezd'];}?>'  />
														</div>
														<div class='col-5'>
															<div style='font-size: 10px'>Эт.</div>
															<input name='client_address_floor' autocomplete='off' class='client_address_floor client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['floor'])){echo$_GET['floor'];}?>'  />
														</div>
														<div class='col-5'>
															<div style='font-size: 10px'>По зв.</div>
															<div style='padding: 8px 0 0 8px'>	
																<input type='radio' name='client_address_domofon' value='1'/>
															</div>
															<!--
															<select name='client_address_domofon' class='client_address_domofon client_info inpt otherinf col-100' style='padding: 4px 0; background: white'>
																<option selected value='1' >Есть</option>
																<option value='0' >Нет</option>
															</select>
															-->
														</div>
														<div class='col-10'>
															<div style='font-size: 10px'>Код п.</div>
															<input name='client_address_podiezd_code' autocomplete='off' class='client_address_podiezd_code client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['podiezd_code'])){echo$_GET['podiezd_code'];}?>'  />
														</div>
														<div class='col-30'>
															<div style='font-size: 10px'>Уточнение</div>
															<input name='client_address' autocomplete='off' class='client_address client_info inpt otherinf col-100' placeholder='' value='<?if(isset($_GET['address'])){echo$_GET['address'];}?>'  />
														</div>
													</div>
												</div>
												<div style='position: relative' style='padding: 0'>
													<div class='phone_tooltip_wrapper' style='display: none; position: absolute; top: 0px; z-index: 20; left: 0; width: 650px; padding: 5px; background: white; box-shadow: 0 2px 5px #aaa; font-size: 12px; line-height: 28px;'>Подсказка к телефону</div>
												</div>
												<div style='position: relative' class='user_info_tooltip'></div>
											</div>
											<div style='padding: 0' >
												<div s1tyle='width: 650px'>
													<div class='col-50'>
														<textarea name='order_items' class='order_items otherinf' style='width: 99.5%; height: 101px; padding: 6px 9px' placeholder='Детали заказа'><?if(isset($_GET['items'])){echo preg_replace('/\n+/', "\n", trim($_GET['items']));}?></textarea>
														<input name='order_meal' class='order_meal inpt' autocomplete='off' placeholder='Добавить блюдо' style='width: 99.5%' />
														<div style='position: relative'>
															<div class='order_details_tooltip_wrapper' style='display: none; position: absolute; top: 0px; left: 0; width: 100%; padding: 5px; background: white; box-shadow: 0 2px 5px #aaa; font-size: 12px; line-height: 28px;'>Подсказка к заказам</div>
														</div>
														<textarea class='price-total-numb-textarea' style='margin-top: 10px; width: 100%; height: 14px; font-size: 10px; border: none; resize: none'></textarea>
													</div>
													<div class='col-25'>
														<textarea name='comment' class='otherinf' style='width: 100%; height: 98px; padding: 6px 9px; margin-bottom: 3px' placeholder='Комментарий к заказу'></textarea>
														<div style='font-size: 12px; padding: 0'>
															<input name='time' value='сегодня, сейчас' class='inpt otherinf col-100' placeholder='Время доставки'/>
														</div>
													</div>
													<div class='col-25'>
														<div style='padding: 10px 0 5px 10px; cursor: pointer' class='price-total-numb'>
															Итого: <span class='new_order_summ'>0</span>тг
														</div>
														<input name='cash' class='client_info inpt otherinf' autocomplete="off" placeholder='Наличные' style='width: 100%' />
														<div style='font-size: 12px; padding: 0 0 12px 0'>
															<input name='client_discount' class='client_info inpt otherinf' placeholder='Скидка' style='width: 34%' />
															<input name='client_discount_comment' class='client_info inpt otherinf' placeholder='За что скидка?' style='width: 63%; border: none; border-bottom: 1px solid; padding-bottom: 1px;' />
														</div>
														<input type='submit' class='btn btn-default' name='open_order' style='width: 100%; cursor: pointer' value='Принять заказ'/>
														<input type='hidden' name='summ' value='0'/>
													</div>
												</div>
											</div>
										</form>
										<script>
											$(function(){
												$('#send_order_form').submit(function(event){
													if($('input[name=summ]').val() == 0){
														event.preventDefault();
													} else {
														$('input[name=open_order]').css('display','none');
													}
												});
												/*
												$('.client_info').change(function(){
													var up = $('input[name=client_phone]').val();
													var ua = $('input[name=client_address]').val();
													$.get('./?get_users_latest_order='+up+'&user_address='+ua, function(data){
															$('.user_info_tooltip').css('display','block').html(data);
													});
												});
												*/
												$('input[name=client_phone]')
													.focus(function(){
														var thisVal = $(this).val();
														$(this).val($(this).val().replace(' ',''));
														if(thisVal<=1){
															$(this).val('8');
														}
														$('.order_details_tooltip_wrapper').css('display','none')
													})
													//.blur(function(){
													//	$('.phone_tooltip_wrapper').css('display','none')
													//})
													.keyup(function(){
														var thisVal = $(this).val();
														$(this).val($(this).val().replace(' ',''));
														if(thisVal>1){
															$.get('./?get_user_by_phone='+thisVal, function(data){
																	$('.phone_tooltip_wrapper').css('display','block').html(data);
															});
														} else
														if(thisVal<=1){
															$(this).val('8');
														}
													});
												$( ".otherinf" ).focus(function(){
													$('.order_details_tooltip_wrapper').css('display','none');
													$('.phone_tooltip_wrapper').css('display','none');
												});
												$( "input[name=order_meal]" )
													.focus(function(){
														$('.phone_tooltip_wrapper').css('display','none')
													})
													//.blur(function(){
													//	$('.order_details_tooltip_wrapper').css('display','none')
													//})
													.keyup(function(){
														var odv = $('input[name=order_meal]').val();
														var odvArr = odv.split(',');
														$.get('./?get_order_details='+odvArr[odvArr.length-1], function(data){
																$('.order_details_tooltip_wrapper').css('display','block').html(data);
														});
													});
												$( ".hasbonus" )
													.click(function(){
														if($(this).prop('checked') == true){
															var bonusAmount = parseInt($('.bonus_amount').val())/100;
															$('.bonus').val($('input[name=summ]').val()*bonusAmount);
														} else {
															$('.bonus').val(0);
														}
													});
												$( ".bonus_amount" )
													.keyup(function(){
														countTotalPrice();
													});
												$( "input[name=bonus_to_spend]" )
													.keyup(function(){
														if($(this).val()>0){
															var summ = $('input[name=summ]').val();
															var bonuses = $(this).val();
															$('.new_order_summ').html(summ - bonuses);
															//$('input[name=summ]').val(summ - bonuses);
															$('.bonus').val(0);
														} else {
															countTotalPrice();
														}
													});
												$( "input[name=client_discount]" )
													.keyup(function(){
														if($(this).val()>0){
															var summ = $('input[name=summ]').val();
															var bonuses = $('input[name=client_discount]').val();
															$('.new_order_summ').html(summ - bonuses);
															//$('input[name=summ]').val(summ - bonuses);
															$('.bonus').val(0);
														} else {
															countTotalPrice();
														}
													});
												$('.order_items').change(function(){
													countTotalPrice();
												});
												$('input[name=summ]').change(function(){
													countTotalPrice();
												});
												$('.order_items').keyup(function(){
													countTotalPrice();
												});

												$('.hider').click(function(){
													var thisTarget = $(this).attr('tgt');
													if($('.'+thisTarget).hasClass('hidden')){
														$('.'+thisTarget).removeClass('hidden');
													} else {
														$('.'+thisTarget).addClass('hidden');
													}
												});
												function countTotalPrice(){
													var orderItems = $('.order_items').val().split('\n');
													var discounts = parseInt($('input[name=client_discount]').val());
													var priceReg = /\[[0-9 -()+]+\]/;
													var amountReg = /\([0-9., -()+]+\)/;
													var summ = 0;
													var hasbonus = 0;
													var bonusAmount = parseInt($('.bonus_amount').val())/100;
													$.each(orderItems, function(index, item) {
														var itemPrice;
														if(item.match(priceReg)){itemPrice = parseInt(item.match(priceReg)[0].replace('[','').replace(']',''));} else {itemPrice=0; hasbonus=1;}
														var itemAmount = parseFloat(item.replace(',','.').match(amountReg)[0].replace('(','').replace(')',''));
														summ += itemAmount*itemPrice;
													});
													//summ = summ - discounts;
													$('input[name=summ]').val(summ);
													$('.new_order_summ').html(summ);
													if(hasbonus==0 && $('.hasbonus').prop('checked') == true){ $('.bonus').val((summ*bonusAmount)); } else { $('.bonus').val(0);}
													$('.price-total-numb-textarea').html("Итого: "+summ+". Без сдачи сможете приготовить?");
												}
											});
										</script>
									</div>
								</div>
							</div>
						</div>
						<div class='col-10'>
							
						</div>
						<div class='col-20' style='display: inline-block'>
								
						</div>
					<?php
					//
					// ПРЕДЗАКАЗЫ
					//
					//$open_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` = 0 AND `date` = '".$turn['date']."' AND `turn_id` = ".$turn['id']." ORDER BY `time` DESC");
					//$open_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` = 0 AND `date` = '".date('d.m.Y')."' AND `operator_id` = '".$_SESSION['user_id']."' ORDER BY `time` DESC");
					$oosql = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `status` = 0 AND `time` > '".strtotime(date('d.m.Y 23:59:59'))."'");
					$open_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` = 0  AND `time` > '".strtotime(date('d.m.Y 23:59:59'))."' ORDER BY `time` DESC");
					//$open_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` = 0  ORDER BY `time` DESC");
					if(mysql_num_rows($open_orders_sql)>0){
						$numbO = mysql_num_rows($oosql); if(mysql_num_rows($oosql)<6){$numbO = 6;}
						echo "<div style='border-top: 1px solid #aaa; padding: 5px'>
							<h4 class='hider' tgt='predzakaz'>Предзаказы в очереди ";$on = mysql_num_rows($oosql); if($on > 3){echo "<span style='color: red'>".$on."</span>";} else {echo $on;} echo"</h4>
							<div class='panel predzakaz hidden'>";
							echo "<div class='row header'>";
								echo "<div class='col-7'><div>Время</div></div>";
								echo "<div class='col-10'><div>Клиент</div></div>";
								echo "<div class='col-15'><div>Адрес</div></div>";
								echo "<div class='col-20'><div>Заказ</div></div>";
								echo "<div class='col-5'><div>Сумма</div></div>";
								echo "<div class='col-10'><div>Курьер</div></div>";
								echo "<div class='col-10'><div>Комментарий</div></div>";
								echo "<div class='col-10'><div>Действие</div></div>";
							echo "</div>";
							$que = mysql_num_rows($open_orders_sql);
							$pizzaAmount = 0;
							$sushiAmount = 0;
							$kitchenAmount = 0;
							$shashlikAmount = 0;
							while($oos /* open order single */ = mysql_fetch_assoc($open_orders_sql)){
								$orderDetails = explode(PHP_EOL, $oos['order_details']);
								foreach($orderDetails as $item){
									preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$item,$matches);
									if($matches[4]=='P'){$pizzaAmount += $matches[2];}
									if($matches[4]=='S'){$sushiAmount += $matches[2];}
									if($matches[4]=='SH'){$shashlikAmount += $matches[2];}
									if($matches[4]=='K'){$kitchenAmount += $matches[2];}
								};
								echo "<div class='row'>";
									echo "<div class='col-7'><div style='display: inline-block; vertical-align: top'>".($que--)."</div>";
										if(time()-$oos['time']>3600){
											echo "<div style='display: inline-block; vertical-align: top; color: red'>".countTime($oos['time'], time())."</div>";
										} else
										if(time()-$oos['time']>2400 && time()-$oos['time']<3600){
											echo "<div style='display: inline-block; vertical-align: top; color: orange'>".countTime($oos['time'], time())."</div>";
										} else
										if(time()-$oos['time']>1200 && time()-$oos['time']<2400){
											echo "<div style='display: inline-block; vertical-align: top; color: yellow'>".countTime($oos['time'], time())."</div>";
										} else
										if(time()-$oos['time']<0){
											echo "<div style='display: inline-block; vertical-align: top; color: green'>".date('d.m H:i',$oos['time'])." - предзаказ</div>";
										} else
										{
											echo "<div style='display: inline-block; vertical-align: top; color: green'>".countTime($oos['time'], time())."</div>";
										}
									//echo "<div>".countTime(time(), ($oos['time'] + 3600))."-".countTime(time(), ($oos['time'] + 4800))."</div>";
									echo "</div>";
									echo "<div class='col-10'><div><a class='noa' href='./?sector=clients&phone=".$oos['user_phone']."' target='_blank'>".$oos['user_phone']."</a></div><div style='color:#aaa'>".$oos['id']."</div></div>";
									echo "<div class='col-15'><div>
											<a class='noa' href='./?sector=clients&address=".$oos['address']."' target='_blank'>".$oos['address']."</a><br/>
											<a class='noa' href='./?sector=map&address=".$oos['address']."' target='_blank' style='color: #aaa'>на карте</a>
										</div></div>";
									echo "<div class='col-20'>
											<div class='order-contents minimized' style='font-size: 12px'>
												".nl2br($oos['order_details'])."
												<br/>
												<a class='noa' href='' onclick=\"printPage('./_templates/paycheck_meals_only.php?id=".$oos['id']."'); return false;\">распечатать</a>
											</div>
										</div>";
									echo "<div class='col-5'><div align='right'>";
											if($oos['discount'] > 0){
												echo "<span style='font-size: 10px'>".$oos['summ']. "<br/>-".($oos['discount'])."</span>";
												echo "<br/><a href='' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1'); return false;\">".($oos['summ'] - $oos['discount'])."</a>";
											}
											else if($oos['bonus_to_spend'] > 0){
												echo "<span style='font-size: 10px'>".$oos['summ']. "-".($oos['bonus_to_spend'])."</span>";
												echo "<br/><a href='' style='font-size: 14px' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1',''); return false;\">".($oos['summ'] - $oos['bonus_to_spend'])."</a>";
											}
											else {
												echo "<a href='' style='font-size: 18px' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1',''); return false;\">".$oos['summ']."</a>";
											}
											if($oos['check_printed'] == 0){
												echo " (!)";
											}
										echo
										"</div></div>";
									echo "<div class='col-10'><div>
											<form method='post'>
											<input type='hidden' name='order_id' value='".$oos['id']."'/>
											<input type='hidden' name='order_time' value='".$oos['time']."'/>
											<select class='slct-else' tgt-id='courier-order-".$oos['id']."'>
												<option value=''>не назначен</option>";
												foreach($couriers as $courier){
													echo "<option value='".$courier."'"; if($oos['courier']==$courier){echo " selected='selected'";} echo">".$courier."</option>";
												}
												echo
												"<option value='else'>другой</option>
											</select>
											<input tgt-id='courier-order-".$oos['id']."' name='order_courier' value='".$oos['courier']."' class='slct-else-v hidden inpt' placeholder='Курьер' style='width: 79%' />
										  </div></div>";
									echo "<div class='col-10'><div>
											<input name='order_comment' value='".$oos['comment']."' class='inpt' placeholder='Комментарий' style='width: 100%' />
										  </div></div>";
									echo "<div class='col-15'><div>
											<select name='order_status' class='slct' style='width: 92px'>
												<option value='0' >сохранить</value>
												<option value='1' selected>закрыть</value>
												<option value='2'>отменен</value>
											</select>
											<input type='submit' class='btn btn-default' name='close_order' style=' cursor: pointer' value='OK'/>
											</form>
										</div></div>";
								echo "</div>";
							}
							echo
							"</div>
						</div>";
					}


					//
					// ОТКРЫТЫЕ ЗАКАЗЫ
					//
					//скорее всего мусор - $oosql = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE `status` = 0 AND `time` < '".strtotime(date('d.m.Y 23:59:59'))."'");
					$open_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` = 0  AND `time` < '".strtotime(date('d.m.Y 23:59:59'))."' ORDER BY `time` DESC");
					if(mysql_num_rows($open_orders_sql)>0){
						$numbO = mysql_num_rows($open_orders_sql); if(mysql_num_rows($open_orders_sql)<6){$numbO = 6;}
						echo "<div style='border-top: 1px solid #aaa; padding: 5px'>
							<h4>Заказов в очереди ";$on = mysql_num_rows($open_orders_sql); if($on > 3){echo "<span style='color: red'>".$on."</span>";} else {echo $on;} echo" Примерное время доставки <span style='color: red'>".countTime(0,$numbO*600)." - ".countTime(0,($numbO*600 + 1200))."</span></h4>
							<div class='panel'>";
							echo "<div class='row header'>";
								echo "<div class='col-7'><div>Время</div></div>";
								echo "<div class='col-10'><div>Клиент</div></div>";
								echo "<div class='col-15'><div>Адрес</div></div>";
								echo "<div class='col-20'><div>Заказ</div></div>";
								echo "<div class='col-5'><div>Сумма</div></div>";
								echo "<div class='col-10'><div>Курьер</div></div>";
								echo "<div class='col-10'><div>Комментарий</div></div>";
								echo "<div class='col-10'><div>Действие</div></div>";
							echo "</div>";
							$que = mysql_num_rows($open_orders_sql);
							$pizzaAmount = 0;
							$sushiAmount = 0;
							$kitchenAmount = 0;
							$shashlikAmount = 0;
							while($oos /* open order single */ = mysql_fetch_assoc($open_orders_sql)){
								$orderDetails = explode(PHP_EOL, $oos['order_details']);
								foreach($orderDetails as $item){
									preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$item,$matches);
									if($matches[4]=='P'){$pizzaAmount += $matches[2];}
									if($matches[4]=='S'){$sushiAmount += $matches[2];}
									if($matches[4]=='SH'){$shashlikAmount += $matches[2];}
									if($matches[4]=='KGOR'){$kitchenAmount += $matches[2];}
									if($matches[4]=='KSUP'){$kitchenAmount += $matches[2];}
								};
								echo "<div class='row'>";
									echo "<div class='col-7'><div style='display: inline-block; vertical-align: top'>".($que--)."</div>";
										if(time()-$oos['time']>3600){
											echo "<div style='display: inline-block; vertical-align: top; color: red'>".countTime($oos['time'], time())."</div>";
										} else
										if(time()-$oos['time']>2400 && time()-$oos['time']<3600){
											echo "<div style='display: inline-block; vertical-align: top; color: orange'>".countTime($oos['time'], time())."</div>";
										} else
										if(time()-$oos['time']>1200 && time()-$oos['time']<2400){
											echo "<div style='display: inline-block; vertical-align: top; color: yellow'>".countTime($oos['time'], time())."</div>";
										} else
										if(time()-$oos['time']<0){
											echo "<div style='display: inline-block; vertical-align: top; color: green'>".date('d.m H:i',$oos['time'])."</div>";
										} else
										{
											echo "<div style='display: inline-block; vertical-align: top; color: green'>".countTime($oos['time'], time())."</div>";
										}
									//echo "<div>".countTime(time(), ($oos['time'] + 3600))."-".countTime(time(), ($oos['time'] + 4800))."</div>";
									echo "</div>";
									echo "<div class='col-10'>
											<form method='post'>";
												if($oos['is_editable'] == 1){
													echo
													"<div class='placeholder' style='padding: 6px 0 0 4px' tgt='user_phone_edit'>".$oos['user_phone']."</div>".
													"<div class='user_phone_edit hidden'><input class='inpt col-100' name='user_phone' value='".$oos['user_phone']."'/></div>".
													"<div><a class='noa' href='./?sector=clients&phone=".$oos['user_phone']."' target='_blank'>история заказов</a></div>";
												} else {
													echo
													"<input type='hidden' name='user_phone' value='".$oos['user_phone']."'/>
													<div><a class='noa' href='./?sector=clients&phone=".$oos['user_phone']."' target='_blank'>".formatPhone($oos['user_phone'])."</a></div>
													<div style='color:#aaa'>".$oos['user_phone']."</div>";
												}
										echo
										"</div>";
									echo "<div class='col-15'>";
												if($oos['is_editable'] == 1){
													echo
													"<div class='placeholder' style='padding: 6px 10px' tgt='user_address_edit'>".$oos['address']."</div>".
													"<div class='user_address_edit hidden'><input class='inpt col-100' name='address' value='".$oos['address']."'/></div>";
												} else {
													echo
													"<div>
														<input type='hidden' name='address' value='".$oos['address']."'/>
														<a class='noa' href='./?sector=clients&address=".$oos['address']."' target='_blank'>".$oos['address']."</a><br/>
														<a class='noa' href='./?sector=map&address=".$oos['address']."' target='_blank' style='color: #aaa'>на карте</a>
													</div>";
												}
										echo
										"</div>";
									echo "<div class='col-20'>";
											//echo "<div>http://m.omelette.kz/order_".$oos['id']."_".$oos['user_phone']."</div>";
												if($oos['is_editable'] == 1){
													echo
													"<div><textarea class='inpt col-100' name='order_details'>".$oos['order_details']."</textarea></div>";
												} else {
													echo
													"<div class='order-contents minimized' style='font-size: 12px'>
														<input type='hidden' name='order_details' value='".$oos['order_details']."'/>
														".nl2br($oos['order_details'])."
														<br/>
														<a class='noa' href='' onclick=\"printPage('./_templates/paycheck_meals_only.php?id=".$oos['id']."'); return false;\">".$oos['summ_cats']."</a>
													</div>";
												}
										echo
										"</div>";
									echo "<div class='col-5'><div align='right'>";
										if($oos['is_editable'] ==1){
											echo "<div class='placeholder' style='padding: 6px 10px' tgt='user_summ_edit'>".$oos['summ']."</div>";
											echo "<input type='hidden' name='cash' value='".$oos['cash']."'/>";
											echo "<div class='user_summ_edit hidden'><input class='inpt col-100' name='summ' value='".$oos['summ']."'/></div>";
												echo "<div><a href='' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1'); return false;\"><span style='font-size: 10px'>чек";
													if($oos['check_printed'] == 0){
														echo " (!)";
													}
												echo "</span></a></div>";
										} else {
											if($oos['discount'] > 0){
												echo "<a href='' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1'); return false;\"><span style='font-size: 10px'>".$oos['summ']. "-".($oos['discount'])."</span>";
												echo "<br/>".($oos['summ'] - $oos['discount'])."</a>";
											}
											else if($oos['bonus_to_spend'] > 0){
												echo "<span style='font-size: 10px'>".$oos['summ']. "-".($oos['bonus_to_spend'])."</span>";
												echo "<br/><a href='' style='font-size: 14px' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1',''); return false;\">".($oos['summ'] - $oos['bonus_to_spend'])."</a>";
											}
											else {
												echo "<a href='' style='font-size: 18px' onclick=\"printPage('./_templates/paycheck.php?id=".$oos['id']."&instaprint=1',''); return false;\">".$oos['summ']."</a>";
											}
											if($oos['check_printed'] == 0){
												echo " (!)";
											}
											if($oos['cash']>0){
												echo "<div > с ".$oos['cash']."</div>";
											}
											echo "<input type='hidden' name='summ' value='".$oos['summ']."'/>";
										}
										echo
										"</div></div>";
									echo "<div class='col-10'>
											<div>
												<input type='hidden' name='order_id' value='".$oos['id']."'/>
												<input type='hidden' name='order_time' value='".$oos['time']."'/>
												<select class='slct-else' tgt-id='courier-order-".$oos['id']."'>
													<option value=''>не назначен</option>";
													foreach($couriers as $courier){
														echo "<option value='".$courier."'"; if($oos['courier']==$courier){echo " selected='selected'";} echo">".$courier."</option>";
													}
													echo
													"<option value='else'>другой</option>
												</select>
												<input tgt-id='courier-order-".$oos['id']."' name='order_courier' value='".$oos['courier']."' class='slct-else-v hidden inpt' placeholder='Курьер' style='width: 79%' />
											  </div>
										  </div>";
									echo "<div class='col-10'><div>
											<input name='order_comment' value='".$oos['comment']."' class='inpt' placeholder='Комментарий' style='width: 100%' />
										  </div></div>";
									echo "<div class='col-15'><div>
											<select name='order_status' class='slct' style='width: 110px'>";
												if($oos['courier'] == ''){
													echo "<option value='0' selected>сохранить</value>
													<option value='1'>закрыть</value>
													<option value='2'>отменен</value>";
												} else {
													echo "<option value='0'>сохранить</value>
													<option value='1' selected>закрыть</value>
													<option value='2'>отменен</value>";
												}
											echo
											"</select>
											<input type='submit' class='btn btn-default' name='close_order' style=' cursor: pointer' value='OK'/>
											</form>
										</div></div>";
								echo "</div>";
							}
							echo
							"</div>
						</div>";
					} else {
						echo "<div style='padding: 10px 0; background: #eaeaea' align=center>Открытых заказов нет</div>";
					}
					/*
					считает количество блюд в работе по каждой кухне,
					не хватает гибкости
					
					if(mysql_num_rows($open_orders_sql)>0){
						echo "<div style='padding: 10px'>В работе пицца ".$pizzaAmount.", Суши ".$sushiAmount.", Шашлык ".$shashlikAmount.", Кухня ".$kitchenAmount."</div>";
					}
					*/
					?>
					<?php
					$closed_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` != 0 AND `date` = '".date('d.m.Y')."' ORDER BY `time` DESC");
					$total_discounts = 0;
						if(mysql_num_rows($closed_orders_sql)>0){
							$couriersS = array();
							$closed_orders_total_sql = mysql_query("SELECT `id`,`summ`,`courier`,`discount`,`bonus_to_spend` FROM `turn_orders_data` WHERE `status` = 1 AND `date` = '".date('d.m.Y')."'");
							while($closed_orders_total_fetch = mysql_fetch_assoc($closed_orders_total_sql)){
								if(array_key_exists($closed_orders_total_fetch['courier'], $couriersS)){
									if($closed_orders_total_fetch['discount'] > 0){
										$total_discounts += $closed_orders_total_fetch['discount'];
										$couriersS[$closed_orders_total_fetch['courier']][0] += ($closed_orders_total_fetch['summ'] - $closed_orders_total_fetch['discount']);
									} else
									if($closed_orders_total_fetch['bonus_to_spend'] > 0){
										$couriersS[$closed_orders_total_fetch['courier']][0] += ($closed_orders_total_fetch['summ'] - $closed_orders_total_fetch['bonus_to_spend']);
									} else {
										$couriersS[$closed_orders_total_fetch['courier']][0] += $closed_orders_total_fetch['summ'];
									}
									$couriersS[$closed_orders_total_fetch['courier']][1]++;
								} else {
									if($closed_orders_total_fetch['discount'] > 0){
										$total_discounts += $closed_orders_total_fetch['discount'];
										$couriersS[$closed_orders_total_fetch['courier']][0] = ($closed_orders_total_fetch['summ'] - $closed_orders_total_fetch['discount']);
									} else
									if($closed_orders_total_fetch['bonus_to_spend'] > 0){
										$couriersS[$closed_orders_total_fetch['courier']][0] = ($closed_orders_total_fetch['summ'] - $closed_orders_total_fetch['bonus_to_spend']);
									} else {
										$couriersS[$closed_orders_total_fetch['courier']][0] = $closed_orders_total_fetch['summ'];
									}
									$couriersS[$closed_orders_total_fetch['courier']][1] = 1;
								}
							}
							//var_dump($couriers);
							echo
							"<div style='border-top: 1px solid #aaa; padding: 5px'>
								<h4 class='hider' tgt='closed-orders-block' style='border-bottom: 1px solid #aaa'>Закрытые заказы ".mysql_num_rows($closed_orders_total_sql)."</h4>
									<div class='panel hidden closed-orders-block'>";
										echo "<div class='row header'>";
											echo "<div class='col-10'><div>Время</div></div>";
											echo "<div class='col-10'><div>Клиент</div></div>";
											echo "<div class='col-15'><div>Адрес</div></div>";
											echo "<div class='col-25'><div>Заказ</div></div>";
											echo "<div class='col-10'><div align='right'>Сумма</div></div>";
											echo "<div class='col-10'><div>Курьер</div></div>";
											echo "<div class='col-15'><div>Комментарий</div></div>";
										echo "</div>";
										$pizzaAmount = 0; $pizzaSumms = 0;
										$sushiAmount = 0; $sushiSumms = 0;
										$kitchenAmount = 0; $kitchenSumms = 0;
										$shashlikAmount = 0; $shashlikSumms = 0;
										while($cos /* closed order single */ = mysql_fetch_assoc($closed_orders_sql)){
											if($cos['status']==1){
												$orderDetails = explode(PHP_EOL, $cos['order_details']);
												foreach($orderDetails as $item){
													preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$item,$matches);
													if($matches[4]=='P'){$pizzaAmount += $matches[2]; $pizzaSumms += ($matches[2] * $matches[3]);}
													if($matches[4]=='S'){$sushiAmount += $matches[2]; $sushiSumms += ($matches[2] * $matches[3]);}
													if($matches[4]=='SH'){$shashlikAmount += $matches[2]; $shashlikSumms += ($matches[2] * $matches[3]);}
													if($matches[4]=='KGOR'){$kitchenAmount += $matches[2]; $kitchenSumms += ($matches[2] * $matches[3]);}
													$totalSumms += ($matches[2] * $matches[3]);
												};
											}
											$color='';if($cos['status'] == 2){$color='gray';}
											echo "<div class='row closed-order-single' style='color:".$color."; border-bottom: 1px dashed #aaa' courier='".$cos['courier']."'>";
												echo "<div class='col-10'><div>".date('H:i',$cos['time'])."-".date('H:i',$cos['time_closed'])." ".countTime($cos['time'], $cos['time_closed'])."</div></div>";
												echo "<div class='col-10'><div><a class='noa' href='./?sector=clients&phone=".$cos['user_phone']."' target='_blank'>".$cos['user_phone']."</a></div></div>";
												echo "<div class='col-15'><div>".$cos['address']."</div></div>";
												echo "<div class='col-25'><div style='font-size: 12px'>".nl2br($cos['order_details'])."</div></div>";
												echo "<div class='col-10'><div align='right'>";
														if($cos['discount'] > 0){
															echo "<a style='font-size: 12px' href='' onclick=\"window.open('./_templates/paycheck.php?id=".$cos['id']."&courier=".$cos['courier']."','newwindow','width=350,height=500'); return false;\">".$cos['summ']."";
															echo " - ".$cos['discount']. " = ".($cos['summ'] - $cos['discount'])."</a>";
														}
														else if($cos['bonus_to_spend'] > 0){
															echo "<a style='font-size: 12px' href='' onclick=\"window.open('./_templates/paycheck.php?id=".$cos['id']."&courier=".$cos['courier']."','newwindow','width=350,height=500'); return false;\">".$cos['summ']."";
															echo " - ".$cos['bonus_to_spend']. " = ".($cos['summ'] - $cos['bonus_to_spend'])."</a>";

														}
														else {
															//echo "<a href='' onclick=\"window.open('./_templates/paycheck.php?id=".$cos['id']."&courier=".$cos['courier']."','newwindow','width=350,height=750'); return false;\">".$cos['summ']."</a>";
															echo "<a style='font-size: 16px' onClick=\"printPage('./_templates/paycheck.php?id=".$cos['id']."','./_templates/paycheck_meals_only.php?id=".$cos['id']."');\">".$cos['summ']."</a>";
														}

														if($cos['cash']>0){
															echo "<div > с ".$cos['cash']."</div>";
														}
													echo
													"</div></div>";
												//echo "<div class='col-15'><div style='font-size: 12px'>".$cos['comment']."</div></div>";
												//echo "<div class='col-5'><div>".$cos['courier']."</div></div>";
												echo "<div class='col-10'><div>
														<form method='post'>
														<input type='hidden' name='order_id' value='".$cos['id']."'/>
														<input type='hidden' name='order_time' value='".$cos['time']."'/>
														<input type='hidden' name='order_status' value='".$cos['status']."'/>
														<select class='slct-else' tgt-id='courier-order-".$cos['id']."'>
															<option value=''>не назначен</option>";
															foreach($couriers as $courier){
																echo "<option value='".$courier."'"; if($cos['courier']==$courier){echo " selected='selected'";} echo">".$courier."</option>";
															}
															echo
															"<option value='else'>другой</option>
														</select>
														<input tgt-id='courier-order-".$cos['id']."' name='order_courier' value='".$cos['courier']."' class='slct-else-v hidden inpt' placeholder='Курьер' style='width: 79%' />
													  </div></div>";
												echo "<div class='col-10'><div>
														<input name='order_comment' value='".$cos['comment']."' class='inpt' placeholder='Комментарий' style='width: 100%' />
													  </div></div>";
												echo "<div class='col-5'><div>
														<input type='submit' class='btn btn-default' name='change_closed_order' style=' cursor: pointer' value='OK'/>
														</form>
													</div></div>";
											echo "</div>";
										}
									echo "</div>";
									echo "<div class='hider' tgt='closed-orders-sums' style='padding: 15px; cursor:pointer;' align='right'>Итоги смены</div>";
									echo "<div class='closed-orders-sums hidden'  style='padding: 10px' align='right'>";
										echo "<div class='col-10 orders-courier-all'>Показать все</div>";
										foreach ($couriersS as $courier => $summC){
											echo "<div class='col-15 orders-courier' courier='".$courier."'>".$courier." : ".$summC[0]."(".$summC[1].")</div>";
										}
										echo "<div class='col-10'>Скидок : ".$total_discounts."</div>";

										echo "<div class='hider' tgt='closed-orders-info' style='padding: 20px; cursor:pointer; border-bottom: 1px dashed' align='right'>Дополнительная информация</div>";
										$closed_orders_sql = mysql_query("SELECT AVG(`summ`) FROM `turn_orders_data` WHERE `status` != 0 AND `date` = '".date('d.m.Y')."'");
										$closed_orders_fetch = mysql_fetch_assoc($closed_orders_sql);
										echo "<div class='closed-orders-info hidden'  style='padding: 10px' align='right'>";
											echo "<div style='padding: 10px'>Средний чек ".number_format($closed_orders_fetch['AVG(`summ`)'], 2, '.',' ')." Пицца ".$pizzaAmount.", Суши ".$sushiAmount.", Шашлык ".$shashlikAmount." (".$shashlikSumms."), Кухня ".$kitchenAmount."</div>";
										echo "</div>";
									echo "</div>";
								echo
								"</div>";
						}
					?>
					<div class='events-log-wrapper' style='position: fixed; bottom: 0; left:0; width: 250px'></div>
					<?php
						$j=1;
						for($i=7;$i>0;$i--){
							$osc = mysql_query("SELECT `id` FROM `turn_orders_data` WHERE (`status` = 0 OR `status` = 1) AND `date_week` = '".date('W')."' AND `date_year` = '".date('Y')."' AND `date_day_of_week` = '".$i."'");
							$numb[$j] =  mysql_num_rows($osc);
							$j++;
						}
						$j=1;
						for($i=7;$i>0;$i--){
							$osc = mysql_query("SELECT `orders_numb` FROM `turn_history_summs_data` WHERE `date_year`='".(date('Y')-1)."' AND `date_week`='".date('W')."' AND `date_day_of_week`='".$i."'");
							$osf = mysql_fetch_assoc($osc);
							$numb3[$j] =  $osf['orders_numb'];
							$j++;
						}
					?>
					<!--
					-->
					<script>
						var obj  = $('#recentOrders');
						var obj2  = $('#recentOrders2');
						var obj6 = $('#recentOrders6');
						var chartHeight = obj.height();
						var chartWidth = obj.width();
						var chartData   = [<?php echo $numb[7].",".$numb[6].",". $numb[5].",". $numb[4].",". $numb[3].",". $numb[2].",". $numb[1];?>];
						var chartData2   = [<?php echo $numb3[7].",".$numb3[6].",". $numb3[5].",". $numb3[4].",". $numb3[3].",". $numb3[2].",". $numb3[1];?>];
						var chartDataMax = Math.max.apply(Math, chartData);
						$.each(chartData, function(index, value){
							var thiHeight = ( value * 100 ) / chartDataMax; if(thiHeight == 0){thiHeight = 1;}
							var thisWidth = (chartWidth / chartData.lenght) - 5;
							var thisColor = '#f44336';
							if(value == 0){var textColor = 'color: #aaa;'}
							if(value < 20 && value > 0){var textColor = 'color: white;'}
							//d32f2f f44336 ff9800 ffcd38 ffee62 a2ce6e 6fbe72
							if(value<30){thisColor = 'rgb(219, 6, 21)';} else
							if(value>=30 && value<35){thisColor = 'rgb(219, 6, 21)';} else
							if(value>=35 && value<40){thisColor = 'rgb(246, 134, 0)';} else
							if(value>=40 && value<50){thisColor = 'rgb(255, 216, 1)';} else
							if(value>=50 && value<60){thisColor = 'rgb(131, 212, 71)';} else
							if(value>=60 && value<70){thisColor = 'rgb(96, 179, 45)';} else
							if(value>=70){thisColor = 'rgb(0, 128, 49)';}
							obj.append("<div class='chart-bar-single' style=' height: "+thiHeight+"%; width: "+thisWidth+"%; background: "+thisColor+"; "+textColor+"'><div>"+value+"</div></div>");
						});
						var chartDataMax = Math.max.apply(Math, chartData2);
						$.each(chartData2, function(index, value){
							var thiHeight = ( value * 100 ) / chartDataMax; if(thiHeight == 0){thiHeight = 1;}
							var thisWidth = (chartWidth / chartData2.lenght) - 5;
							var thisColor = '#f44336';
							if(value < 20 && value > 0){var textColor = 'color: white;'}
							//d32f2f f44336 ff9800 ffcd38 ffee62 a2ce6e 6fbe72
							if(value<30){thisColor = 'rgb(219, 6, 21)';} else
							if(value>=30 && value<35){thisColor = 'rgb(219, 6, 21)';} else
							if(value>=35 && value<40){thisColor = 'rgb(246, 134, 0)';} else
							if(value>=40 && value<50){thisColor = 'rgb(255, 216, 1)';} else
							if(value>=50 && value<60){thisColor = 'rgb(131, 212, 71)';} else
							if(value>=60 && value<70){thisColor = 'rgb(96, 179, 45)';} else
							if(value>=70){thisColor = 'rgb(0, 128, 49)';}
							obj2.append("<div class='chart-bar-single' style=' height: "+thiHeight+"%; width: "+thisWidth+"%; background: "+thisColor+"; "+textColor+"'><div>"+value+"</div></div>");
						});
					
						$(document).delegate('.close-event-btn','click',function(){
							var thisId = $(this).attr('data-id');
							$.get('./_php/?delete_events_log='+thisId, function(data){
								updateEventsLog();
							});
						});
						$(function(){
							function playNotificationSound(){
								$('#sound')[0].play();
							};
							$('.sound').click(playNotificationSound);
							function updateEventsLog(){
								$.get('./_php/?get_events_log',function(data){
									if(data){
										$('.events-log-wrapper').html(data);
										playNotificationSound();
									}
								});
							};
							updateEventsLog();
							setInterval(updateEventsLog,5000);
							
							var copyTextareaBtn = document.querySelector('.price-total-numb');

							copyTextareaBtn.addEventListener('click', function(event) {
							  var copyTextarea = document.querySelector('.price-total-numb-textarea');
							  copyTextarea.select();

							  try {
								var successful = document.execCommand('copy');
								var msg = successful ? 'successful' : 'unsuccessful';
								console.log('Copying text command was ' + msg);
							  } catch (err) {
								console.log('Oops, unable to copy');
							  }
							});
							$('.order-contents').click(function(){
								if($(this).hasClass('minimized') == true){

									$(this).removeClass('minimized');
								} else {
									$(this).addClass('minimized');
								}
							});
						});
					</script>
					<script>
						function printPage(url, url2){
							mywindow = window.open(url,'newwindow','width=350,height=500');
							//mywindow.focus();
							//setTimeout(mywindow.close(),5700);
							//return false;
						}
						$(function(){
							$('.placeholder').click(function(){
								var tgt = $(this).attr('tgt');
								$(this).addClass('hidden');
								$('.'+tgt).removeClass('hidden');
							});
							$('.orders-courier-all').click(function(){
								$('.closed-order-single').removeClass('hidden');
							});
							$('.orders-courier').click(function(){
								var thisData = $(this).attr('courier');
								$('.closed-order-single').addClass('hidden');
								$('.closed-order-single[courier='+thisData+']').removeClass('hidden');
							});
							$('.slct-else').change(function(){
								var obj = $(this);
								var tgtID = obj.attr('tgt-id');
								var selected = $('.slct-else[tgt-id='+tgtID+'] option:selected').attr('value');
								if(selected=='else'){
									$('.slct-else-v[tgt-id='+tgtID+']').val('');
									$(this).addClass('minimized');
									if($('.slct-else-v[tgt-id='+tgtID+']').hasClass('hidden')==true){$('.slct-else-v[tgt-id='+tgtID+']').removeClass('hidden').focus()};
								} else {
									if($('.slct-else-v[tgt-id='+tgtID+']').hasClass('hidden')==false){$('.slct-else-v[tgt-id='+tgtID+']').addClass('hidden')};
									$('.slct-else-v[tgt-id='+tgtID+']').val(selected);
									$(this).removeClass('minimized');
								}
							});
							//setTimeout(function(){ location.reload() }, 1000 * 60 * 25);
							
						});
					</script>
					</div>
					
				</div>
			</div>
		</div>
	</body>
</html>
