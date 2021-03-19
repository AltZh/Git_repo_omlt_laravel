<html>
	<head>
		<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Roboto&family=Ubuntu+Condensed&display=swap" rel="stylesheet">
		<meta charset='UTF-8' />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
		<script src='./_js/jquery-latest.js'></script>
		<script src='./_js/notification-center.js'></script>
		
		<link rel="stylesheet" href="./_css/nav-left.css" />
		<link rel="stylesheet" href="./_css/main-style.css" />
		<link rel="stylesheet" href="./_css/notification-center.css" />
	</head>
	<body>
		<div style='width: 100%'>
			<? include_once('./_templates/inc/nav-left.php'); ?>
			<div class='page-content'>
				<div style='padding-left: 30px'>
					<div class='page-header-wrapper'>
						<div class='page-header'>Настройки филиала</div>
					</div>
					<div style='padding: 24px'>
						<div class='form-header-wrapper'>
							<div class='form-header'>Весь список</div>
						</div>
						<div class='form-content-wrapper'>
							<form method='post'>
							<? 
								$settings_sql = mysql_query("SELECT * FROM `branch_data` WHERE `id` = '".$branch_id."' LIMIT 1");
								$settings = mysql_fetch_assoc($settings_sql);
								$name = $settings['name'];
								$shift_begins = $settings['shift_begins'];
								$shift_ends = $settings['shift_ends'];
								$city = $settings['city'];
								$addr_str = $settings['addr_str'];
								$contacts_data = $settings['contacts_data'];
								
								echo "<div>";
									echo "<div class='col w20'>name</div>";
									echo "<div class='col w80'><input class='inpt w100' name='name' value='".$name."'/></div>";
								echo "</div>";
								
								echo "<div>";
									echo "<div class='col w20'>shift_begins</div>";
									echo "<div class='col w80'><input class='inpt w100' name='shift_begins' value='".$shift_begins."'/></div>";
								echo "</div>";
								
								echo "<div>";
									echo "<div class='col w20'>shift_ends</div>";
									echo "<div class='col w80'><input class='inpt w100' name='shift_ends' value='".$shift_ends."'/></div>";
								echo "</div>";
								
								echo "<div>";
									echo "<div class='col w20'>city</div>";
									echo "<div class='col w80'><input class='inpt w100' name='city' value='".$city."'/></div>";
								echo "</div>";
								
								echo "<div>";
									echo "<div class='col w20'>addr_str</div>";
									echo "<div class='col w80'><input class='inpt w100' name='addr_str' value='".$addr_str."'/></div>";
								echo "</div>";
								
								echo "<div>";
									echo "<div class='col w20'>contacts_data</div>";
									echo "<div class='col w80'><textarea name='contacts_data' class='inpt w100' style='height: 90px'>".$contacts_data."</textarea></div>";
								echo "</div>";
								
								echo "<div>";
									echo "<div class='col w20'></div>";
									echo "<div class='col w80'><input type='submit' class='btn green' name='update_info' value='save'/></div>";
								echo "</div>";
							?>
							</form>
							<script>
								$(function(){
									$('.delete_item').on('click',function(e){
										var r = confirm("Вы действительно хотите удалить?");
										if(!r){ e.preventDefault();}
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>