<html>
	<head>
		<link rel='stylesheet' href='./_css/style.css'></link>
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
		<script src='./_js/jquery-latest.js'></script>
		<style>
			.invisible{display: none}
			.description{ padding: 10px; background: #eee}
			
			.item-single{padding: 10px 0; cursor: pointer}
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
					<div class='col-10' align=right><div style='padding: 3px; color: white'><?php echo $_SESSION['user_group'];?></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><a href='./?logout'>Выйти</a></div></div>
				</header>
				<div class='content'>
					<h1>Адреса</h1>
					<div class='panel'>
						<div>
							<input name='filter' class='inpt col-100' placeholder='поиск по названию и составу' />
							<script>
								$(function(){
									$('input[name=filter]').keyup(function(){
										var thisVal = $(this).val();
										if(thisVal.length > 2){
											$('.item-single').addClass('invisible');
											$('.item-single').each(function(){
												if($(this).html() != $(this).html().replace(thisVal,'')){
													$(this).removeClass('invisible');
												}
											});
										} else {
											$('.item-single').removeClass('invisible');
										}
									});
								});
							</script>
						</div>
						<div>
						<?php
							if(isset($_GET['id'])){
								$itemsSql = mysql_query("SELECT * FROM `users_addrs` WHERE `id` = '".$_GET['id']."'");
							} else {
								$itemsSql = mysql_query("SELECT * FROM `users_addrs` WHERE `coordinates` = '' ORDER BY `street_name_RU` DESC LIMIT ".($_GET['p']*20).",20");
							}
							echo "<h4>Все ".mysql_num_rows($itemsSql)."</h4>";
							echo "<br/>";
							echo "<br/>";
							echo "<a href='./?sector=".$_GET['sector']."&p=".($_GET['p']-1)."'>prev</a> - ";
							echo "<a href='./?sector=".$_GET['sector']."&p=".($_GET['p'])."'>reload</a> - ";
							echo "<a href='./?sector=".$_GET['sector']."&p=".($_GET['p']+1)."'>next</a>";
							while($itemsFetch = mysql_fetch_assoc($itemsSql)){
								if(empty($itemsFetch['street_name_RU'])){
									list($street,$therest) = explode(',',$itemsFetch['address']); $street = trim($street);
									list($house,$therest) = explode('-',$therest); $house = trim($house);
									list($appartment,$therest) = explode(' ',$therest); $appartment = trim($appartment); 
									$comment = trim($therest);
								} else {
									$street = $itemsFetch['street_name_RU'];
									$house = $itemsFetch['house'];
									$appartment = $itemsFetch['appartment'];
									$comment = $itemsFetch['comment'];
								}
								$addrStr = $itemsFetch['address']; if(empty($itemsFetch['address'])){$addrStr = $itemsFetch['street_name_RU'].', '.$itemsFetch['house'].'-'.$itemsFetch['appartment'].' p'.$itemsFetch['podiezd'].' f'.$itemsFetch['floor'].' '.$itemsFetch['comment'];}
								$color = 'black'; if($itemsFetch['coordinates']==''){$color = 'red';}
								echo "<div class='col-100 item-single'>";
									echo "<div class='hider' style='color: ".$color."' tgt='item_".$itemsFetch['id']."'>".$addrStr." ".$itemsFetch['user_id']."</div>";
									echo "<div class='invisible item_".$itemsFetch['id']." description'>
											<div class='col-60'>
												<form method=post>
													<input type='hidden' name='id' value='".$itemsFetch['id']."'/>
													<input class='inpt addrs-".$itemsFetch['id']."' style='width:235px' type='text' placeholder='address' name='address' value='".$itemsFetch['address']."'/>
													<input class='inpt' style='width:85px'  type='text' placeholder='city_name_RU' name='city_name_RU' value='".$itemsFetch['city_name_RU']."' />
													<input class='inpt' style='width:70px'  type='text' placeholder='zipcode' name='zipcode' value='".$itemsFetch['zipcode']."' />
													<input class='inpt addrs-inpt-".$itemsFetch['id']." addrs-".$itemsFetch['id']."-street' style='width:90px'  type='text' placeholder='street_name_RU' name='street_name_RU' value='".$street."' />
													<input class='inpt addrs-inpt-".$itemsFetch['id']." addrs-".$itemsFetch['id']."-house' style='width:70px'  type='text' placeholder='house' name='house' value='".$house."' />
													<input class='inpt' style='width:70px'  type='text' placeholder='appartment' name='appartment' value='".$appartment."' />
													<br/>
													<input class='inpt coords-".$itemsFetch['id']."' style='width:150px'  type='hidden' placeholder='coordinates' name='coordinates' value='".$itemsFetch['coordinates']."' />
													<input class='inpt' style='width:70px'  type='text' placeholder='podiezd' name='podiezd' value='".$itemsFetch['podiezd']."' />
													<input class='inpt' style='width:70px'  type='text' placeholder='floor' name='floor' value='".$itemsFetch['floor']."' />
													<input class='inpt' style='width:70px'  type='text' placeholder='podiezd_code' name='podiezd_code' value='".$itemsFetch['podiezd_code']."' />
													<select name='domofon' style='width:70px' class='slct'>
														<option value='0'>не работает</option>
														<option value='1' selected>работает</option>
													</select>
													<br/>
													<textarea  style='width:550px'class='inpt' placeholder='comment' name='comment'>".$comment."</textarea>
													<br/>
													<select name='action' style='width:550px' class='slct'>
														<option value='delete'>delete</option>
														<option value='save' selected>save</option>
													</select>
													<br/>
													<input class='inpt'  type='submit' name='save_item' />
												</form>
												<div class='".$itemsFetch['id']."_coords_status'></div>
											</div>
											<div class='col-40'>
												<div id='map-addrs-".$itemsFetch['id']."' style='width: 100%; height: 250px'></div>
												<script>
													//$('#map-addrs-".$itemsFetch['id']."').click(init);
													ymaps.ready(init_".$itemsFetch['id'].");

													function init_".$itemsFetch['id']."() {
														var myMap_".$itemsFetch['id'].";
														$('.hider[tgt=item_".$itemsFetch['id']."]').on('click', function (event, ui) {
															myMap_".$itemsFetch['id']." = new ymaps.Map('map-addrs-".$itemsFetch['id']."', {
																center: ["; if($itemsFetch['coordinates']!=''){echo $itemsFetch['coordinates'];}else{echo "52.28598411727996,76.97108980688473";}echo"],
																zoom: 13
															});
															
															
															var myPlacemark_".$itemsFetch['id']." = new ymaps.Placemark(["; if($itemsFetch['coordinates']!=''){echo $itemsFetch['coordinates'];}else{echo "52.28598411727996,76.97108980688473";}echo"], {
																iconContent: '',
																balloonContent: ''
															}, {
																preset: 'islands#darkBlueDotIconWithCaption',
																draggable: true
															});
															
															myPlacemark_".$itemsFetch['id'].".events.add('dragend',function(){
																$('.coords-".$itemsFetch['id']."').val(myPlacemark_".$itemsFetch['id'].".geometry.getCoordinates());
															});

															myMap_".$itemsFetch['id'].".geoObjects.add(myPlacemark_".$itemsFetch['id'].");";
															if($itemsFetch['coordinates'] == ''){
																echo
																	"ymaps.geocode('Павлодар,".$itemsFetch['street_name_RU'].", ".$itemsFetch['house']."', {
																		/**
																		 * Опции запроса
																		 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
																		 */
																		// Сортировка результатов от центра окна карты.
																		// boundedBy: myMap.getBounds(),
																		// strictBounds: true,
																		// Вместе с опцией boundedBy будет искать строго внутри области, указанной в boundedBy.
																		// Если нужен только один результат, экономим трафик пользователей.
																		results: 1
																	}).then(function (res) {
																		// Выбираем первый результат геокодирования.
																		var firstGeoObject = res.geoObjects.get(0),
																			// Координаты геообъекта.
																			coords = firstGeoObject.geometry.getCoordinates(),
																			// Область видимости геообъекта.
																			bounds = firstGeoObject.properties.get('boundedBy');

																		//firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
																		// Получаем строку с адресом и выводим в иконке геообъекта.
																		//firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

																		// Добавляем первый найденный геообъект на карту.
																		myPlacemark_".$itemsFetch['id'].".geometry.setCoordinates(coords);
																		// Масштабируем карту на область видимости геообъекта.
																		myMap_".$itemsFetch['id'].".setBounds(bounds, {
																			// Проверяем наличие тайлов на данном масштабе.
																			checkZoomRange: true
																		});
																		if( $('.coords-".$itemsFetch['id']."').val() == ''){
																			$('.coords-".$itemsFetch['id']."').val(coords);
																			$.get('./?sector=".$_GET['sector']."&update_coords=1&street=".$itemsFetch['street_name_RU']."&house=".$itemsFetch['house']."&new_coords='+coords, function(data){
																				$('.".$itemsFetch['id']."_coords_status').html(data);
																			});
																		}
																	});";
															}
															echo
															"$('.addrs-inpt-".$itemsFetch['id']."').keyup(function(){
																var thisStreet = $('.addrs-".$itemsFetch['id']."-street').val();
																var thisHouse = $('.addrs-".$itemsFetch['id']."-house').val();
																ymaps.geocode('Павлодар,'+thisStreet+', '+thisHouse, {
																	/**
																	 * Опции запроса
																	 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
																	 */
																	// Сортировка результатов от центра окна карты.
																	// boundedBy: myMap.getBounds(),
																	// strictBounds: true,
																	// Вместе с опцией boundedBy будет искать строго внутри области, указанной в boundedBy.
																	// Если нужен только один результат, экономим трафик пользователей.
																	results: 1
																}).then(function (res) {
																	// Выбираем первый результат геокодирования.
																	var firstGeoObject = res.geoObjects.get(0),
																		// Координаты геообъекта.
																		coords = firstGeoObject.geometry.getCoordinates(),
																		// Область видимости геообъекта.
																		bounds = firstGeoObject.properties.get('boundedBy');

																	//firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
																	// Получаем строку с адресом и выводим в иконке геообъекта.
																	//firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

																	// Добавляем первый найденный геообъект на карту.
																	myPlacemark_".$itemsFetch['id'].".geometry.setCoordinates(coords);
																	// Масштабируем карту на область видимости геообъекта.
																	myMap_".$itemsFetch['id'].".setBounds(bounds, {
																		// Проверяем наличие тайлов на данном масштабе.
																		checkZoomRange: true
																	});
																	$('.coords-".$itemsFetch['id']."').val(coords);
																});
															});
														});
													}
												</script>
											</div>
										  </div>";
								echo "</div>";
							}
						echo "<br/>";
						echo "<br/>";
						echo "<a href='./?sector=".$_GET['sector']."&p=".($_GET['p']-1)."'>prev</a> - ";
						echo "<a href='./?sector=".$_GET['sector']."&p=".($_GET['p'])."'>reload</a> - ";
						echo "<a href='./?sector=".$_GET['sector']."&p=".($_GET['p']+1)."'>next</a>";
						?>
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
