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
					<div class='col-10' align=right></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><?php echo $_SESSION['user_phone'];?></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><a href='./?logout'>Выйти</a></div></div>
				</header>
				<div class='content'>
					<div class='footer' style='margin-bottom: 30px;'>
						<h2>Добавить запись</h2>
						<form method='post'>
							<div class='col-10'><input type='text' name='date' required='required' class='inpt col-100' placeholder='Дата'/></div>
							<div class='col-20'><input type='text' name='purchase' required='required' class='inpt col-100' placeholder='Товар'/></div>
							<div class='col-15'><input type='text' name='amount' required='required' class='inpt col-100 calc amount' placeholder='Количество'/></div>
							<div class='col-15'><input type='text' name='price' required='required' class='inpt col-100 calc price' placeholder='Цена'/></div>
							<div class='col-15'><input type='text' name='summ' required='required' class='inpt col-100 summ' placeholder='Сумма'/></div>
							<div class='col-5'><input type='submit' name='add_new_purchase' value='go' class='btn btn-default col-100'/></div>
						</form>
					</div>
					<div class='footer' style='margin-bottom: 30px;'>
						<h2>Поиск по закупкам</h2>
						<form method='get'>
							<div class='col-15'><input type='text' name='purchase' class='inpt col-100' placeholder='Закупка' value='<?php echo $_GET['purchase'];?>'/></div>
							<div class='col-5'>
								<input type='submit' value='Поиск' class='btn btn-default col-100'/>
								<input type='hidden' name='sector' value='purchases'/>
							</div>
						</form>
						<!--
						<form method='get'>
							<div class='col-10'><input type='text' name='date' class='inpt col-100' placeholder='Дата с' value='<?php echo $_GET['date'];?>'/></div>
							<div class='col-10'><input type='text' name='date_to' class='inpt col-100' placeholder='Дата по' value='<?php echo $_GET['date_to'];?>'/></div>
							<input type='hidden' name='sector' value='purchases' class='btn btn-default col-100'/>
							<div class='col-5'><input type='submit' name='search' value='go' class='btn btn-default col-100'/></div>
						</form>
						-->
					</div>
					<h1>Закупки</h1>
					<div class='panel'>
						<div class='row header'>
							<div class='col-10'><div>Дата</div></div>
							<div class='col-20'><div>Товар</div></div>
							<div class='col-10'><div align='right'>Количество</div></div>
							<div class='col-10'><div align='right'>Цена</div></div>
							<div class='col-10'><div align='right'>Сумма</div></div>
						</div>
						<?php
							if(isset($_GET['purchase'])){
								$summ = 0;
								$purchase = $_GET['purchase'];
								$userSql = mysql_query("SELECT * FROM `purchases_data` WHERE `purchase` LIKE '%".$purchase."%' ORDER BY `datetime` DESC");
								while($fetch = mysql_fetch_assoc($userSql)){
									$summ+=$fetch['summ'];
									echo "<div class='row'>";
										echo "<div class='col-10'><div>".$fetch['date']."</div></div>";
										echo "<div class='col-20'><div><a href='./?sector=purchases&purchase=".$fetch['purchase']."'>".$fetch['purchase']."</a></div></div>";
										echo "<div class='col-10'><div align='right'>".$fetch['amount']."</div></div>";
										echo "<div class='col-10'><div align='right'>".$fetch['price']."</div></div>";
										echo "<div class='col-10'><div align='right'>".$fetch['summ']."</div></div>";
									echo "</div>";
								}
								echo "<div class='row' style='background: #aaa; border-top: 1px solid #333'>";
									echo "<div class='col-10'><div></div></div>";
									echo "<div class='col-20'><div></div></div>";
									echo "<div class='col-10'><div></div></div>";
									echo "<div class='col-10'><div>Итого:</div></div>";
									echo "<div class='col-10'><div>".number_format($summ,0,'',' ')."</div></div>";
								echo "</div>";
							}
							else {
								$summ = 0;
								$date = date('d.m.Y'); if(isset($_GET['date'])){$date = $_GET['date'];}
								$userSql = mysql_query("SELECT * FROM `purchases_data` GROUP BY `purchase`");
								while($fetch = mysql_fetch_assoc($userSql)){
									$sql2 = mysql_query("SELECT * FROM `purchases_data` WHERE `purchase`='".$fetch['purchase']."' AND `id` < '".$fetch['id']."' ORDER BY `date` DESC LIMIT 1") or die(mysql_error());
									$fetch2 = mysql_fetch_assoc($sql2);
									$extraprice = ''; if($fetch2['price'] != ''){$extraprice = "(".$fetch2['price'].")";}
									$summ+=$fetch['summ'];
									echo "<div class='row'>";
										echo "<div class='col-10'><div>".$fetch['date']."</div></div>";
										echo "<div class='col-20'><div><a href='./?sector=purchases&purchase=".$fetch['purchase']."'>".$fetch['purchase']."</a></div></div>";
										echo "<div class='col-10'><div align='right'>".$fetch['amount']."</div></div>";
										echo "<div class='col-10'><div align='right'>".$fetch['price']." ".$extraprice."</div></div>";
										echo "<div class='col-10'><div align='right'>".$fetch['summ']."</div></div>";
									echo "</div>";
								}
								echo "<div class='row' style='background: #aaa; border-top: 1px solid #333'>";
									echo "<div class='col-10'><div></div></div>";
									echo "<div class='col-20'><div></div></div>";
									echo "<div class='col-10'><div></div></div>";
									echo "<div class='col-10'><div>Итого:</div></div>";
									echo "<div class='col-10'><div>".number_format($summ,0,'',' ')."</div></div>";
								echo "</div>";
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
