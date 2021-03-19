<html>
	<head>
		<title>Онлайн система учета заказов</title>
		<link rel="icon" sizes="192x192" href="./_css/fav_icon.png">
		<link rel="icon" sizes="128x128" href="./_css/fav_icon.png">
		<link rel="apple-touch-icon" sizes="128x128" href="./_css/fav_icon.png">
		<link rel="apple-touch-icon-precomposed" sizes="128x128" href="./_css/fav_icon.png" />
		<!--meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" /-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!--m1eta name="mobile-web-app-capable" content="yes"-->
		<link rel="apple-touch-startup-image" href="./_css/fav_icon.png" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<!--m1eta name="apple-mobile-web-app-capable" content="yes"-->
		<style>
			*{margin:0; padding:0; font-family: sans-serif}
			.error{padding: 10px; background: #EF9A9A;border: 1px solid #D32F2F; border-radius: 3px; color: #B71C1C; margin: 10px 0;}
			.inpt{font-size: 24px; padding: 10px; margin-bottom: 10px}
			.btn{font-size: 18px; padding: 10px 25px; border: 1px solid #aaa; border-radius: 6px; -webkit-appearance: none; background: #0c0807; color: white}
			.invisible{display: none}
		</style>
		<script src='./_js/jquery-latest.js'></script>
	</head>
	<body style='background: #191919; background: #f6f6f6;'>
		<div class='page col-100' align='center'>
			<div style='max-width: 450px'>
				<div class='nav col-85' style='b1ackground: #191919; padding: 20px'>
					<div class='content'>
						<div style='color: white; padding: 20px 0 40px 0' align='center'><img src='../_css/logo.png' style='width: 75%' /></div>
						<div class='panel'>
							<div class='row header'>
								<form method='post'>
									<div style='padding: 10px 0' >
										<div align='center' style='width: 100%'>
											<div align='center' style='width: 75%'>
											<?
												if(isset($_COOKIE['lastlogin'])){
													echo "<div class='hider' tgt='hidden-obj' selfhide='1' style='font-size: 36px; padding-bottom: 10px'>".$_COOKIE['lastlogin']."</div>";
													echo "<div class='hidden-obj invisible'><input name='user_login' class='inpt' placeholder='Логин' value='".$_COOKIE['lastlogin']."' style='width: 100%' /></div>";
												} else {
													echo "<input name='user_login' class='inpt' placeholder='Логин' style='width: 100%' />";
												}
											?>
												<input name='user_password' class='inpt' type='password' pattern='[0-9]*' inputmode='numeric' style='width: 100%'  placeholder='&bull; &bull; &bull; &bull; &bull;'/>
												<br/>
												<div align='right' style='width: 100%'>
													<input type='submit' class='btn' name='login' value='Вход'/>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<?php
								if(isset($_SESSION['auth_error'])){
									echo "<div align='center' style='width: 100%'><div align='center' style='width: 75%'><div class='error'>".$_SESSION['auth_error']."</div></div></div>";
									unset($_SESSION['auth_error']);
								}
							?>
						</div>
						<script>
							$('.hider').click(function(){
								var thisTarget = $(this).attr('tgt');
								var thisSelfHide = parseInt($(this).attr('selfhide'));
								if(thisSelfHide == 1){
									$('.'+thisTarget).removeClass('invisible');
									$(this).addClass('invisible');
								} else {
									if($('.'+thisTarget).hasClass('invisible')){
										$('.'+thisTarget).removeClass('invisible');
									} else {
										$('.'+thisTarget).addClass('invisible');
									}
								}
							});
						</script>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
