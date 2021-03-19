<html>
	<head>
		<link rel='stylesheet' href='./_css/style.css'></link>
		<script src='./_js/jquery-latest.js'></script>
		<script src='./_js/Chart.min.js'></script>
		<style>
			.error{padding: 10px; background: #EF9A9A;border: 1px solid #D32F2F; border-radius: 3px; color: #B71C1C; margin: 10px 0;}
			.invisible{display: none}
			.row.header{background: whitesmoke;font-weight: bold;border-bottom: 2px solid #aaa;}
			.row.header div{padding: 3px}
			.row div{padding: 3px; font-size: 13px}
		</style>
	</head>
	<body>
		<div class='page col-100'>
			<div class='nav col-15' style='background: #191919; color: white; height: 100%'>
				<?php include_once('./_templates/inc/nav_left.php');?>
			</div>
			<div class='nav col-85' style=''>
				<div class='content'>
					<?php
						if(isset($_GET['period']) && $_GET['period'] == "month"){
						} else
						{
							echo "<h1>Смена от ".$_GET['date']."</h1>";
							$closed_orders_sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE (`status` = 1 OR `status` = 0) AND `date` = '".$_GET['date']."' ORDER BY `time` ASC") or die(mysql_error());
							if(mysql_num_rows($closed_orders_sql)>0){
								$couriers = array();
								$pizzaAmount = 0;
								$sushiAmount = 0;
								$kitchenAmount = 0;
								$shashlikAmount = 0;
								echo
								"<div style='border-top: 1px solid #aaa; padding-top: 5px'>
									<h4 class='hider' tgt='orders-list'>Закрытые заказы ".mysql_num_rows($closed_orders_sql)."</h4>
									<div class='orders-list invisible'>
										<div class='panel'>";
										echo "<div class='row header'>";
											echo "<div class='col-10'><div>Время</div></div>";
											echo "<div class='col-10'><div>Клиент</div></div>";
											echo "<div class='col-20'><div>Адрес</div></div>";
											echo "<div class='col-20'><div>Заказ</div></div>";
											echo "<div class='col-10'><div>Сумма</div></div>";
											echo "<div class='col-10'><div>Комментарий</div></div>";
											echo "<div class='col-10'><div>Курьер</div></div>";
										echo "</div>";
										$total_orders_numb = 0;
										$user_veteran = 0; $user_newbie = 0;
										$timespent_less10 = 0; $timespent_10_30 = 0; $timespent_30_60 = 0; $timespent_60andmore = 0;
										$money_spent_2500=0; $money_spent_2500_3500=0; $money_spent_3500_5000=0; $money_spent_5000=0;
										$hours = array('11'=>0,'12'=>0,'13'=>0,'14'=>0,'15'=>0,'16'=>0,'17'=>0,'18'=>0,'19'=>0,'20'=>0,'21'=>0,'22'=>0,'23'=>0);
										$hoursC = array('11'=>0,'12'=>0,'13'=>0,'14'=>0,'15'=>0,'16'=>0,'17'=>0,'18'=>0,'19'=>0,'20'=>0,'21'=>0,'22'=>0,'23'=>0);
										$summP = 0; $summS = 0; $summK = 0; $summE = 0; $summD = 0; $summZ = 0;
										while($cos /* closed order single */ = mysql_fetch_assoc($closed_orders_sql)){
											$orderDetails = explode(PHP_EOL, $cos['order_details']);
											foreach($orderDetails as $item){
												preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$item,$matches);
												if($matches[4]=='P'){$pizzaAmount += $matches[2];}
												if($matches[4]=='S'){$sushiAmount += $matches[2];}
												if($matches[4]=='SH'){$shashlikAmount += $matches[2];}
												if($matches[4]=='KGOR'){$kitchenAmount += $matches[2];}
											};
											$uSql = mysql_query("SELECT `time_registered` FROM `users_data` WHERE `user_phone` = '".$cos['user_phone']."'");
											$uFetch = mysql_fetch_assoc($uSql);
											$summP += $cos['summP'];
											$summS += $cos['summS'];
											$summK += $cos['summK'];
											$summE += $cos['summE'];
											$total_orders_numb++;
											if($cos['courier'] == 'зал'){$summZ += $cos['summ'];} else {$summD += $cos['summ'];}

											if(date('d.m.Y',$uFetch['time_registered']) == $cos['date']){$user_newbie++; } else { $user_veteran++;}

											if(array_key_exists($cos['courier'], $couriers)){ $couriers[$cos['courier']][0] += $cos['summ']; $couriers[$cos['courier']][1]++;} else { $couriers[$cos['courier']][0] = $cos['summ']; $couriers[$cos['courier']][1]=1;}

											$hoursC[(date('H',$cos['time_closed']) + 1)]++;
											if(date('H',$cos['time']) == 22 && date('i',$cos['time']) > 45){
												$hours[(date('H',$cos['time']) + 1)]++;
											} else {
												$hours[date('H',$cos['time'])]++;
											}
											$color = ''; $time_spent = $cos['time_closed'] - $cos['time'];
											if($time_spent < 10*60){$timespent_less10++;}
											if($time_spent >= 10*60 && $time_spent < 30*60){$timespent_10_30++;}
											if($time_spent >= 30*60 && $time_spent < 60*60){$color='orange'; $timespent_30_60++;}
											if($time_spent >= 60*60){$color='red';  $timespent_60andmore++;}
											$color2 = 'black'; $money_spent = $cos['summ'];
											if($money_spent < 2500){$money_spent_2500++;} else
											if($money_spent >= 2500 && $money_spent < 3500){$color2='orange'; $money_spent_2500_3500++;} else
											if($money_spent >= 3500 && $money_spent < 5000){$color2='green'; $money_spent_3500_5000++;} else
											if($money_spent >= 5000){$color2='yellow';  $money_spent_5000++;}
											echo "<div class='row'>";
												echo "<div class='col-10'><div  style='color:".$color."'>".date('H:i',$cos['time'])." - ".date('H:i',$cos['time_closed'])."<br/>".countTime($cos['time'],$cos['time_closed'])."</div></div>";
												echo "<div class='col-10'><div>".$cos['user_phone']."</div></div>";
												echo "<div class='col-20'><div>".$cos['address']."</div></div>";
												echo "<div class='col-20'><div>".nl2br($cos['order_details'])."</div></div>";
												echo "<div class='col-10'><div><a href='' onclick=\"window.open('./_templates/paycheck.php?id=".$cos['id']."','newwindow','width=350,height=750'); return false;\" style='text-decoration: none; color: ".$color2."'>".$cos['summ']."</a></div>";
												if(!empty($cos['discount'])){echo "<div>-".$cos['discount']."</div><div>".($cos['summ'] - $cos['discount'])."</div>";}
												echo "</div>";
												echo "<div class='col-10'><div>".$cos['comment']."</div></div>";
												echo "<div class='col-10'><div>".$cos['courier']."<br/>".$cos['operator_id']."</div></div>";
											echo "</div>";
										}
										echo "<div class='hider' tgt='closed-orders-sums' style='padding: 20px; cursor:pointer; border-bottom: 1px dashed' align='right'>Показать итоги</div>";
										echo "<div class='closed-orders-sums invisible'  style='padding: 10px' align='right'>";
											foreach ($couriers as $courier => $summC){
												echo "<div class='col-15'>".$courier." : ".$summC[0]."(".$summC[1].")</div>";
											}
										echo "</div>";
										echo
										"</div>
									</div>
									<div class='row'>";
										echo "<div style='padding: 10px'>Выполнено пицца ".$pizzaAmount.", Суши ".$sushiAmount.", Шашлык ".$shashlikAmount.", Кухня ".$kitchenAmount."</div>";
									echo
									"</div>
									<div class='row'>";
										foreach ($couriers as $courier => $summC){
											echo "<div class='col-15'>".$courier." : ".$summC[0]."(".$summC[1].")</div>";
										}
									echo
									"</div>
									<div class='row'>
										<div class='col-20'>Менее 10 минут: ".$timespent_less10."</div>
										<div class='col-20'>10 - 30 минут: ".$timespent_10_30."</div>
										<div class='col-20'>30 - 60 минут: ".$timespent_30_60."</div>
										<div class='col-20'>Дольше 60 минут: ".$timespent_60andmore."</div>
									</div>
									<div class='row'>
										<div class='col-20'>2500: ".$money_spent_2500."(".(floor($money_spent_2500*100/$total_orders_numb))."%)</div>
										<div class='col-20'>2500-3500: ".$money_spent_2500_3500."(".(floor($money_spent_2500_3500*100/$total_orders_numb))."%)</div>
										<div class='col-20'>3500-5000: ".$money_spent_3500_5000."(".(floor($money_spent_3500_5000*100/$total_orders_numb))."%)</div>
										<div class='col-20'>5000: ".$money_spent_5000."(".(floor($money_spent_5000*100/$total_orders_numb))."%)</div>
									</div>
									<div class='row'>
										<div class='col-10'>Новенькие: ".$user_newbie."(".(floor($user_newbie*100/$total_orders_numb))."%)</div>
										<div class='col-10'>Ветераны: ".$user_veteran."(".(floor($user_veteran*100/$total_orders_numb))."%)</div>
									</div>
									<div class='row'>
										<canvas id='typeofOrdersChart' width='400' height='100'></canvas>
									</div>
									<div class='row'>
										<canvas id='closedOrdersChart' width='400' height='100'></canvas>
									</div>
									<div class='row'>
										<canvas id='closedOrdersChart2' width='400' height='100'></canvas>
									</div>
									<div class='row'>
										<canvas id='ordersTimeSpentChart' width='400' height='100'></canvas>
									</div>
									<div class='row'>
										<canvas id='newbieVeterans' width='400' height='100'></canvas>
									</div>
									<div class='row'>
										<div class='col-10'>Доставка: ".$summD."</div>
										<div class='col-10'>Зал: ".$summZ."</div>
									</div>
								</div>";
							}
						}
					?>
					<script>
						$(function(){
							$('.hider').click(function(){
								var thisTarget = $(this).attr('tgt');
								if($('.'+thisTarget).hasClass('invisible')){
									$('.'+thisTarget).removeClass('invisible');
								} else {
									$('.'+thisTarget).addClass('invisible');
								}
							});
						});
					</script>
					<script>
						var ctx = document.getElementById('typeofOrdersChart');
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: ['Pizza','Sushi','Kitchen','Extras'],
								datasets: [{
									label: 'Заказов',
									data: [<?php echo $summP.",".$summS.",".$summK.",".$summE;?>],
									backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)'
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)'
									],
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true
										}
									}]
								}
							}
						});
						var ctx = document.getElementById('newbieVeterans');
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: ['Новенькие','Ветераны'],
								datasets: [{
									label: 'Заказов',
									data: [<?php echo $user_newbie.",".$user_veteran;?>],
									backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)'
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)'
									],
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true
										}
									}]
								}
							}
						});
						var ctx = document.getElementById('ordersTimeSpentChart');
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: ['до 10 минут','10 - 30 минут','30 - 60 минут', 'более 60 минут'],
								datasets: [{
									label: 'Заказов',
									data: [<?php echo $timespent_less10.",".$timespent_10_30.",".$timespent_30_60.",".$timespent_60andmore;?>],
									backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)'
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)'
									],
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true
										}
									}]
								}
							}
						});
						var ctx = document.getElementById('closedOrdersChart');
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: [
									'10',
									'11',
									'12',
									'13',
									'14',
									'15',
									'16',
									'17',
									'18',
									'19',
									'20',
									'21',
									'22',
									'23',
								],
								datasets: [{
									label: 'Заказов',
									data: [
										<?php echo
										$hours['10'].",".
										$hours['11'].",".
										$hours['12'].",".
										$hours['13'].",".
										$hours['14'].",".
										$hours['15'].",".
										$hours['16'].",".
										$hours['17'].",".
										$hours['18'].",".
										$hours['19'].",".
										$hours['20'].",".
										$hours['21'].",".
										$hours['22'].",".
										$hours['23'].",";
										?>
									],
									backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
										'rgba(255, 99, 132, 0.2)',
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
										'rgba(255,99,132,1)',
									],
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true
										}
									}]
								}
							}
						});
						var ctx = document.getElementById('closedOrdersChart2');
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: [
									'11C',
									'12C',
									'13C',
									'14C',
									'15C',
									'16C',
									'17C',
									'18C',
									'19C',
									'20C',
									'21C',
									'22C',
									'23C'
								],
								datasets: [{
									label: 'Заказов',
									data: [
										<?php echo
										$hoursC['11'].",".
										$hoursC['12'].",".
										$hoursC['13'].",".
										$hoursC['14'].",".
										$hoursC['15'].",".
										$hoursC['16'].",".
										$hoursC['17'].",".
										$hoursC['18'].",".
										$hoursC['19'].",".
										$hoursC['20'].",".
										$hoursC['21'].",".
										$hoursC['22'].",".
										$hoursC['23'];
										?>
									],
									backgroundColor: [
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(75, 192, 192, 0.2)',
									],
									borderColor: [
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(75, 192, 192, 1)',
									],
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true
										}
									}]
								}
							}
						});
					</script>
				</div>
			</div>
		</div>
	</body>
</html>
