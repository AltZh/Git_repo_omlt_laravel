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
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
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
					<div class='col-10' align=right><div style='padding: 3px; color: white'><?php echo $_SESSION['user_phone'];?></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><a href='./?logout'>Выйти</a></div></div>
				</header>
				<div class='content'>
					<form>
						<input name='sector' type='hidden' value='<?php echo $_GET['sector'];?>'/>
						<input name='address' value='<?php echo $_GET['address'];?>'/>
						<input type='submit' />
					</form>
					<div id="map" style='width: 100%; height: 500px'></div>
					<script>
						ymaps.ready(init);

						function init() {
							var myMap = new ymaps.Map('map', {
								center: [52.28598411727996,76.97108980688473],
								zoom: 13
							});
							<?php 
								if(isset($_GET['all'])){
									$addrsCoordsArray = array();
									$sql = mysql_query("SELECT `id`,`coordinates`,`street_name_RU`,`house` FROM `users_addrs` WHERE `coordinates` != ''") or die(mysql_error());
									while($fetch = mysql_fetch_assoc($sql)){
										if(!in_array($fetch['coordinates'],$addrsCoordsArray)){
											$addrsCoordsArray[] = $fetch['coordinates'];
											$sql2 = mysql_query("SELECT `id`,`appartment`,`comment`,`address` FROM `users_addrs` WHERE `coordinates` = '".$fetch['coordinates']."'") or die(mysql_error());
											$balloonStr = $fetch['street_name_RU'].", ".$fetch['house']." - ".mysql_num_rows($sql2);
											while($fetch2 = mysql_fetch_assoc($sql2)){
												$balloonStr .= "<br/><a target=\'_blank\'href=\'./?sector=address&id=".$fetch2['id']."\'>".$fetch2['appartment']." ".$fetch2['comment']." ".$fetch2['address']."</a>";
											}
											echo 
											" var myPlacemark_".$fetch['id']." = new ymaps.Placemark([".$fetch['coordinates']."], {
												 iconContent: '".mysql_num_rows($sql2)."',
												 balloonContent: '".$balloonStr."'
												 }, {
												 preset: 'islands#blackStretchyIcon'
												 //preset: 'islands#circleIcon',
												 //iconColor: '#3caa3c'
												 });

												myMap.geoObjects.add(myPlacemark_".$fetch['id'].");";
										}
									}
								}
								if(isset($_GET['address'])){
									echo 
									"ymaps.geocode('Павлодар,".$_GET['address']."', {
										results: 1
									}).then(function (res) {
										// Выбираем первый результат геокодирования.
										var firstGeoObject = res.geoObjects.get(0),
											// Координаты геообъекта.
											coords = firstGeoObject.geometry.getCoordinates(),
											// Область видимости геообъекта.
											bounds = firstGeoObject.properties.get('boundedBy');

										firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
										// Получаем строку с адресом и выводим в иконке геообъекта.
										firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

										// Добавляем первый найденный геообъект на карту.
										myMap.geoObjects.add(firstGeoObject);
										// Масштабируем карту на область видимости геообъекта.
										myMap.setBounds(bounds, {
											// Проверяем наличие тайлов на данном масштабе.
											checkZoomRange: true
										});
									});";
								}
							?>
							 /*
							 var myPlacemark = new ymaps.Placemark(coords, {
							 iconContent: 'моя метка',
							 balloonContent: 'Содержимое балуна <strong>моей метки</strong>'
							 }, {
							 preset: 'islands#violetStretchyIcon'
							 });

							 myMap.geoObjects.add(myPlacemark);
							 */
						}
					</script>

				</div>
			</div>
		</div>
	</body>
</html>
