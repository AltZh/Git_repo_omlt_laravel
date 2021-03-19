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
					<!--div class='footer' style='margin-bottom: 30px;'>
						<h2>Поиск по номеру</h2>
						<form method='get'>
							<div class='col-15'><input type='text' name='phone' class='inpt col-100' placeholder='Номер' value='<?php echo $_GET['phone'];?>'/></div>
							<div class='col-5'><input type='submit' name='sector' value='clients' class='btn btn-default col-100'/></div>
						</form>
					</div-->
					<h1>Заявки в службу контроля качества</h1>
					<div class='panel'>
					<?php
						if(isset($_GET['phone'])){
							$phone = $_GET['phone'];
							echo
							"<div style='padding: 10px 0; font-size: 24px; font-weight: bold'>Заявки</div>";
							$sql = mysql_query("SELECT * FROM `users_tickets_data` WHERE `user_phone`='".$phone."' ORDER BY `time` DESC");
							while($fetch = mysql_fetch_assoc($sql)){
								echo "<div style='padding: 10px 0'>";
									echo "<a href='./?sector=".$_GET['sector']."&id=".$fetch['id']."'>[".date('d.m.Y H:i',$fetch["time"])."] ".$fetch["topic"]."</a>";
								echo "</div>";
							}
						} else
						if(isset($_GET['id'])){
							$itemId = (int) $_GET['id'];
							$sql = mysql_query("SELECT * FROM `users_tickets_data` WHERE `id`='".$itemId."'");
							$fetch = mysql_fetch_assoc($sql);
							$status = 'Не решен';if($fetch["status"]==1){$status='Решен нейтрально';}
							if($fetch["status"]==2){$status='Решен позитивно';}
							if($fetch["status"]==3){$status='Решен негативно';}

							echo "<div><a href='./?sector=".$_GET['sector']."'>&larr; назад</a></div>";
							echo "<div style='padding: 10px 0; font-size: 18px; font-weight: bold'>Заявка от:</div>";
							echo "<div style='padding: 10px 0'>".$fetch["user_phone"]."</div>";
							echo "<div style='padding: 10px 0; font-size: 18px; font-weight: bold'>Тема:</div>";
							echo "<div style='padding: 10px 0'>".$fetch["topic"]."</div>";
							echo "<div style='padding: 10px 0; font-size: 18px; font-weight: bold'>Заявка на:</div>";
							echo "<div style='padding: 10px 0'>".$fetch["ticket_to"]."</div>";
							echo "<div style='padding: 10px 0; font-size: 18px; font-weight: bold'>Подробности:</div>";
							echo "<div style='padding: 10px 0'>".$fetch["description"]."</div>";
							echo "<div style='padding: 10px 0; font-size: 18px; font-weight: bold'>Статус:</div>";
							echo "<div style='padding: 10px 0'>".$status."</div>";
							echo "<div style='padding: 10px 0; font-size: 18px; font-weight: bold'>Решение:</div>";
							echo "<div style='padding: 10px 0'>".$fetch["conclusion"]."</div>";
							if($fetch['status'] == 0){
								echo
								"<form method='post'>
									<select name='status' style='padding: 5px; width: 70%'>
										<option value='0'>Не решен</option>
										<option value='1'>Решен нейтрально</option>
										<option value='2'>Решен позитивно</option>
										<option value='3'>Решен негативно</option>
									</select>
									<textarea name='conclusion' placeholder='Описание решения' style='padding: 5px; width: 70%'></textarea>
									<input type='hidden' name='ticket_id' value='".$itemId."'/>
									<input type='submit' name='update_ticket' value='открыть тикет' style='padding: 5px; width: 70%'/>
								</form>";
							}
						} else {
							echo "<div style='padding: 20px 0'>";
								echo
								"<form method='post'>
									<input type='text'   name='user_phone' placeholder='Номер телефона' style='padding: 5px; width: 70%; margin-bottom: 10px'/>
									<input type='text'   name='topic' placeholder='Тема жалобы' style='padding: 5px; width: 70%; margin-bottom: 10px'/>
									<input type='text'   name='ticket_to' placeholder='На кого тикет' style='padding: 5px; width: 70%; margin-bottom: 10px'/>
									<textarea name='description' placeholder='Описание тикета' style='padding: 5px; width: 70%; height: 120px; margin-bottom: 10px'></textarea>
									<br/>
									<input type='submit' name='open_ticket' value='открыть тикет' style='padding: 5px; w1idth: 70%'/>
								</form>";
							echo "</div>";
							echo
							"<div style='padding: 10px 0; font-size: 24px; font-weight: bold'>Заявки</div>";
							$sql = mysql_query("SELECT * FROM `users_tickets_data` ORDER BY `time` DESC");
							while($fetch = mysql_fetch_assoc($sql)){
								echo "<a href='./?sector=".$_GET['sector']."&id=".$fetch['id']."'>[".date('d.m.Y H:i',$fetch["time"])."] ".$fetch["topic"]."</a><br/>";
							}
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
