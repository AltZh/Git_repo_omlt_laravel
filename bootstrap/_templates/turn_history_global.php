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
					<form method="get">
						<input type='hidden' name='sector' value='turn_history_global' />
						<input name='a' placeholder='a'/>
						<input name='b' placeholder='b'/>
						<input type='submit' />
					</form>
					<form method="get">
						<input type='hidden' name='sector' value='turn_history_global' />
						<input name='j' placeholder='j' value='<? echo $_GET['j']; ?>'/>
						<input name='z' placeholder='z' value='<? echo $_GET['z']; ?>'/>
						<input type='submit' />
					</form>
					<?php
						//if(isset($_GET['count'])){
							$b=2;if(isset($_GET['b'])){$b = $_GET['b'];}
							$a=0;if(isset($_GET['a'])){$a = $_GET['a'];}
							for($i=$a;$i<$b;$i++){
								$date = strtotime('-'.$i.' day 00:00:00');
								$sqll = mysql_query("SELECT * FROM `turn_orders_data` WHERE `time` >= ".strtotime('-'.$i.' day 00:00:00')." AND `time` < ".strtotime('-'.$i.' day 23:59:00')." AND (`status` = 1 OR `status` = 0)");
								$summP = 0;
								$ordersNumb = mysql_num_rows($sqll);
								while($fetch = mysql_fetch_assoc($sqll)){
									$summP += $fetch['summ'];
								}
								$hSql = mysql_query("SELECT * FROM `turn_history_summs_data` WHERE `date` = '".date('d.m.Y', $date)."'");
								if(mysql_num_rows($hSql) == 1){
									mysql_query("UPDATE `turn_history_summs_data` SET `summ` = '".$summP."',`date_year` = '".date('Y',$date)."',`date_month` = '".date('m',$date)."',`date_day` = '".date('z',$date)."',`date_week` = '".date('W',$date)."',`date_day_of_week` = '".date('N',$date)."',`orders_numb` = '".$ordersNumb."' WHERE `date` = '".date('d.m.Y', $date)."'");
								} else {
									mysql_query("INSERT INTO `turn_history_summs_data` (`date`,`date_year`,`date_month`,`date_day`,`date_week`,`date_day_of_week`,`summ`,`orders_numb`) VALUES ('".date('d.m.Y',$date)."','".date('Y',$date)."','".date('m',$date)."','".date('z',$date)."','".date('W',$date)."','".date('N',$date)."','".$summP."','".$ordersNumb."')");
								}
							}
						//}
							echo "<h2>История</h2>";
							$summThisMonth = array();
							$thisYear  = date('Y');
							$thisMonth = date('m');
							$thisDay   = date('z',strtotime(date('t.m.Y 23:59:59')));
							$thisMonthFirstDay = date('z',strtotime(date('01.m.Y H:i:s')));

							while($thisDay >= $thisMonthFirstDay){
								$sql = mysql_query("SELECT * FROM `turn_history_summs_data` WHERE `date_day` = '".$thisDay."' AND `date_year` = '".date('Y')."'");
								if(mysql_num_rows($sql)== 1){
									$fetch = mysql_fetch_assoc($sql);
									$summThisMonth[date('d.M', strtotime($fetch['date'])).' - '. date('D', strtotime($fetch['date']))] = $fetch['orders_numb'];
									if(isset($_GET['showSumm'])){
										$summThisMonth[date('d.M', strtotime($fetch['date'])).' - '. date('D', strtotime($fetch['date']))] = $fetch['summ'];
									}
								} else {
									$summThisMonth[$thisDay] = 0;
								}
								$thisDay--;
							}
							$summThisMonthLastYear = array();
							$thisYear  = date('Y', strtotime('-12 month'));
							$thisMonth = date('m');
							$thisDay   = date('z',strtotime(date('t.m.Y 23:59:59', strtotime('-12 month'))));
							$thisMonthFirstDay = date('z',strtotime(date('01.m.Y H:i:s', strtotime('-12 month'))));

							while($thisDay >= $thisMonthFirstDay){
								$sql = mysql_query("SELECT * FROM `turn_history_summs_data` WHERE `date_day` = '".$thisDay."' AND `date_year` = '".$thisYear."'");
								if(mysql_num_rows($sql)== 1){
									$fetch = mysql_fetch_assoc($sql);
									$summThisMonthLastYear[date('d.M', strtotime($fetch['date'])).' - '. date('D', strtotime($fetch['date']))] = $fetch['orders_numb'];
									if(isset($_GET['showSumm'])){
										$summThisMonthLastYear[date('d.M', strtotime($fetch['date'])).' - '. date('D', strtotime($fetch['date']))] = $fetch['summ'];
									}
								} else {
									$summThisMonthLastYear[$thisDay] = 0;
								}
								$thisDay--;
							}
							echo "<div style='m1argin-top:50px'><canvas id='closedOrdersChart-thisMonth' width='400' height='100'></canvas></div>";
							$sql = mysql_query("SELECT SUM(`summ`),SUM(`orders_numb`) FROM `turn_history_summs_data` WHERE `date_day` >= '".$thisMonthFirstDay."' AND `date_day` <= '".date('z')."' AND `date_year` = '".date('Y')."'");
							$fetch = mysql_fetch_assoc($sql);
							$sql2 = mysql_query("SELECT DISTINCT `date_day` FROM `turn_history_summs_data` WHERE `date_day` >= '".$thisMonthFirstDay."' AND `date_day` <= '".date('z')."' AND `date_year` = '".date('Y')."'");
							//echo number_format($fetch['SUM(`summ`)'],0,' ',',')." - ".$fetch['SUM(`orders_numb`)']. " - ";
							echo round($fetch['SUM(`orders_numb`)'] /mysql_num_rows($sql2)). " - " .  number_format($fetch['SUM(`summ`)']/$fetch['SUM(`orders_numb`)'],2,'.',' ');
							$totalNumbChartData[date('M')] = $fetch['SUM(`orders_numb`)'];

							$months = array();

							$j = 12; if(isset($_GET['j'])){$j = $_GET['j'];}
							$z = 1;  if(isset($_GET['z'])){$z = $_GET['z'];}
							for($z=$z;$z<$j;$z++){
								$months[$z] = array();
								$months[$z]['days']  = (int)date('t', strtotime('-'.$z.' month'));
								for($k=$months[$z]['days']; $k>0; $k--){
									if($k<10){$k='0'.$k;}
									$sql = mysql_query("SELECT * FROM `turn_history_summs_data` WHERE `date` = '".$k.".".date('m.Y',strtotime('-'.$z.' month'))."'") or die(mysql_error());
									$fetch = mysql_fetch_assoc($sql);
									if(isset($_GET['showSumm'])){
										$months[$z]['summ_by_day'][date('d.M', strtotime($fetch['date'])).' - '. date('D', strtotime($fetch['date']))] = $fetch['summ'];
									} else {
										$months[$z]['summ_by_day'][date('d.M', strtotime($fetch['date'])).' - '. date('D', strtotime($fetch['date']))] = $fetch['orders_numb'];
									}
								}
								echo "<div style='margin-top:50px'><canvas id='closedOrdersChart-prev-".$z."Month' width='400' height='100'></canvas></div>";
								$sql = mysql_query("SELECT SUM(`summ`),SUM(`orders_numb`) FROM `turn_history_summs_data` WHERE `date_day` <= '".date('z', strtotime(date('t.m.Y',strtotime('-'.$z.' month 00:00:00'))))."' AND `date_day` >= '".date('z', strtotime(date('01.m.Y',strtotime('-'.$z.' month 00:00:00'))))."' AND `date_year` = '".date('Y',strtotime('-'.$z.' month'))."'");
								$fetch = mysql_fetch_assoc($sql);
								echo round($fetch['SUM(`orders_numb`)'] / date('t',strtotime('-'.$z.' month 00:00:00'))). " - " .  number_format($fetch['SUM(`summ`)']/$fetch['SUM(`orders_numb`)'],2,'.',' ');
								if(isset($_GET['showSumm'])){
									echo " - ".number_format($fetch['SUM(`summ`)'],0,' ',',')." - ".$fetch['SUM(`orders_numb`)'];
									$totalNumbChartData[date('M.Y',strtotime('-'.$z.' month'))] = $fetch['SUM(`summ`)'];
								} else {
									$totalNumbChartData[date('M.Y',strtotime('-'.$z.' month'))] = $fetch['SUM(`orders_numb`)'];
								}
							}


							echo "<div style='margin-top:50px'><canvas id='closedOrdersNumbTotalChart' width='400' height='100'></canvas></div>";
					?>
					<script>
						var ctx = document.getElementById('closedOrdersChart-thisMonth');
						<?php $thisChartData  = $summThisMonth; ?>
						<?php $thisChartData2 = $summThisMonthLastYear; ?>
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: [<?php foreach($thisChartData as $summ=>$value){echo "'".$summ."',";}?>],
								datasets: [{
										label: 'Этот месяц',
										data: [<?php foreach($thisChartData as $summ=>$value){echo $value.",";}?>],
										backgroundColor: [
											<?php 	foreach($thisChartData as $val){
													if($val >= 70){
														echo "'rgb(76, 175, 80)',"; //green
													} else
													if($val >= 60 && $val < 70){
														echo "'rgb(139, 195, 74)',"; //green
													} else
													if($val >= 50 && $val < 60){
														echo "'rgb(255, 193, 7)',"; //orange
													} else
													if($val >= 40 && $val < 50){
														echo "'rgb(255, 235, 59)',"; //yellow
													} else {
														echo "'rgb(244, 67, 54)',"; //red
													}
												}
											?>],
										borderColor: [<?php foreach($thisChartData as $val){echo "'rgba(3, 3, 3, 0)',";}?>],
										borderWidth: 1
									},{
										label: 'Прошлый год',
										data: [<?php foreach($thisChartData2 as $summ=>$value){echo ($value).",";}?>]
									}],
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
						<?
							foreach($months as $month=>$value){
								echo "var ctx = document.getElementById('closedOrdersChart-prev-".$month."Month');";
								echo "var myChart = new Chart(ctx, {
										type: 'bar',
										data: {
											labels: [";foreach($value['summ_by_day'] as $day=>$summ){echo "'".$day."',";} echo "],
											datasets: [{
												label: 'Заказов',
												data: [";foreach($value['summ_by_day'] as $day=>$summ){echo "'".$summ."',";} echo "],
												backgroundColor: [";
														foreach($value['summ_by_day'] as $val){
															if($val >= 70){
																echo "'rgb(76, 175, 80)',"; //green
															} else
															if($val >= 60 && $val < 70){
																echo "'rgb(139, 195, 74)',"; //green
															} else
															if($val >= 50 && $val < 60){
																echo "'rgb(255, 193, 7)',"; //orange
															} else
															if($val >= 40 && $val < 50){
																echo "'rgb(255, 235, 59)',"; //yellow
															} else {
																echo "'rgb(244, 67, 54)',"; //red
															}
														}
												echo
												"],
												borderColor: ["; foreach($value['summ_by_day'] as $val){echo "'rgba(100, 181, 246, 0)',";} echo "],
												borderWidth: 1
											}],
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
									});";
							}
						?>

						var ctx = document.getElementById('closedOrdersNumbTotalChart');
						<?php $thisChartData = $totalNumbChartData; ?>
						var myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: [<?php foreach($thisChartData as $summ=>$value){echo "'".$summ."',";}?>],
								datasets: [{
									label: 'Заказов',
									data: [<?php foreach($thisChartData as $summ=>$value){echo $value.",";}?>],
									backgroundColor: [
										<?php 	foreach($thisChartData as $val){
												if($val >= 70){
													echo "'rgb(76, 175, 80)',"; //green
												} else
												if($val >= 60 && $val < 70){
													echo "'rgb(139, 195, 74)',"; //green
												} else
												if($val >= 50 && $val < 60){
													echo "'rgb(255, 193, 7)',"; //orange
												} else
												if($val >= 40 && $val < 50){
													echo "'rgb(255, 235, 59)',"; //yellow
												} else {
													echo "'rgb(244, 67, 54)',"; //red
												}
											}
										?>
									],
									borderColor: [<?php foreach($summPrev4Month as $val){echo "'rgba(100, 181, 246, 0)',";}?>],
									borderWidth: 1
								}],
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
