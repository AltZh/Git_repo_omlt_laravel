<?php
?>
<html>
	<head>
		<link rel='stylesheet' href='./_css/style.css'></link>
		<script src='_js/jquery-latest.js'></script>
		<style>
			.invisible{display: none}
			.description{ padding: 10px; background: #eee}
			.description ul{list-style-type: none;}
			.description li{list-style-type: none;}
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
					<div class='col-5' align=right></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><?php echo $_SESSION['user_phone'];?></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><?php echo $_SESSION['user_group'];?></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><a href='./?logout'>Выйти</a></div></div>
				</header>
				<div class='content'>
					<h1>Сотрудники</h1>
					<div class='panel'>
						<div style='padding: 20px 0'>
							<h2>Добавить</h2>
							<form method='post'>
								<div class='col-10'>
									<div class='col-100'>Group</div>
									<input class='inpt col-100' name='group'/>
								</div>
								<div class='col-10'>
									<div class='col-100'>Name</div>
									<input class='inpt col-100' name='name'/>
								</div>
								<div class='col-10'>
									<div class='col-100'>Password</div>
									<input class='inpt col-100' name='password'/>
								</div>
								<div class='col-10'>
									<div class='col-100'>Phone</div>
									<input class='inpt col-100' name='Phone'/>
								</div>
								<div class='col-10'>
									<div class='col-100'>Telegram</div>
									<input class='inpt col-100' name='telegram_chat_id'/>
								</div>
								<div class='col-10'>
									<div class='col-100'>Status</div>
									<input class='inpt col-100' name='status'/>
								</div>
								<div class='col-10'>
									<div class='col-100'>AL</div>
									<input class='inpt col-100' name='access_level'/>
								</div>
								<div class='col-10'>
									<div class='col-100'> </div>
									<input class='btn col-100' type='submit' name='add_new_staff'/>
								</div>
							</form>
						</div>
						<div style='padding: 20px 0'>
							<div>
								<div>
								<h2>Список всех</h2>
								</div>
								<div>
								<div class='col-5'>#</div>
								<div class='col-10'>Группа</div>
								<div class='col-10'>Имя</div>
								<div class='col-10'>Пароль</div>
								<div class='col-10'>Телефон</div>
								<div class='col-10'>Телеграм</div>
								<div class='col-10'>Статус</div>
								<div class='col-10'>Доступ</div>
								</div>
							</div>
							<div>
							<?php
								$itemsSql = mysql_query("SELECT * FROM `staff_data` ORDER BY `group`,`name` ASC");
								$numb=0;
								while($itemsFetch = mysql_fetch_assoc($itemsSql)){
									$numb++;
									echo
									"<form method='post'>
										<div class='row' style='padding: 0 0 10px 0'>
											<div class='col-5'>
												".$numb."
											</div>
											<div class='col-10'>
												<input type='hidden' name='id' value='".$itemsFetch['id']."'/>
												<input class='inpt col-100' name='group' value='".$itemsFetch['group']."'/>
											</div>
											<div class='col-10'>
												<input class='inpt col-100' name='name' value='".$itemsFetch['name']."'/>
											</div>
											<div class='col-10'>
												<input class='inpt col-100' type='password' name='password' value='".$itemsFetch['password']."'/>
											</div>
											<div class='col-10'>
												<input class='inpt col-100' name='phone' value='".$itemsFetch['phone']."'/>
											</div>
											<div class='col-10'>
												<input class='inpt col-100' name='telegram_chat_id' value='".$itemsFetch['telegram_chat_id']."'/>
											</div>
											<div class='col-10'>
												<select name='status' class='slct col-100' >";
													echo "<option value='0' "; if($itemsFetch['status'] == 0){echo "selected='selected'";} echo ">не работает</option>";
													echo "<option value='1' "; if($itemsFetch['status'] == 1){echo "selected='selected'";} echo ">работает</option>";
													echo "<option value='-1'>удалить</option>";
												echo
												"</select>
											</div>
											<div class='col-10'>
												<select name='access_level' class='slct col-100' >";
													echo "<option value='0' "; if($itemsFetch['access_level'] == 0){echo "selected='selected'";} echo ">минимум</option>";
													echo "<option value='1' "; if($itemsFetch['access_level'] == 3){echo "selected='selected'";} echo ">средний</option>";
													echo "<option value='7' "; if($itemsFetch['access_level'] == 7){echo "selected='selected'";} echo ">максимум</option>";
												echo
												"</select>
											</div>
											<div class='col-10'>
												<input class='btn col-100' type='submit' name='update_staff'/>
											</div>
										</div>
									</form>";
								}
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	</body>
</html>
