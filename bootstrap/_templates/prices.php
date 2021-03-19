<?php
?>
<html>
	<head>
		<link rel='stylesheet' href='./_css/style.css'></link>
		<script src='./_js/jquery-latest.js'></script>
		<style>
			.invisible{display: none}
			.description{ padding: 10px; background: #eee}

			.item-single{padding: 10px 0; cursor: pointer}

			.hidden{display: none}
			.slct-else{width:100%; border: 1px dashed #aaa; background:white}
			.slct-else.minimized{width:22px}
			select{padding: 4px;border: 1px dashed #aaa; background:white}
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
					<div class='col-10' align=right><div style='padding: 3px; color: white'></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'></div></div>
					<div class='col-10' align=right><div style='padding: 3px; color: white'><a href='./?logout'>Выйти</a></div></div>
				</header>
				<div class='content'>
					<h3>Прайсы</h3>
					<div class='panel'>
						<div>
							<input name='filter' class='inpt col-100' placeholder='поиск по названию и составу' />
							<div class='hidden'>
								<form method=post>
									<input class='inpt' type='text' name='name' />
									<input class='inpt' type='submit' name='add_meal' />
								</form>
							</div>
							<script>
								$(function(){
									$('input[name=filter]').keyup(function(){
										var thisVal = $(this).val().toLowerCase();
										if(thisVal.length > 2){
											$('.item-single').addClass('invisible');
											$('.item-single-name').each(function(){
												if($(this).html().toLowerCase() != $(this).html().toLowerCase().replace(thisVal,'')){
													$(this).parent().parent().parent().removeClass('invisible');
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
								$sql = mysql_query("SELECT * FROM `meals_data` WHERE `id` = '".$_GET['id']."'");
								$fetch = mysql_fetch_assoc($sql);

								echo "<form method=post>
									<input type='hidden' name='id' value='".$fetch['id']."'/>
									<input class='inpt' style='width:550px' type='text' name='name' value='".$fetch['name']."'/>
									<br/>
									<input class='inpt' style='width:550px'  type='text' name='price' value='".$fetch['price']."' />
									<br/>
									<input class='inpt' style='width:550px'  type='text' name='type' value='".$fetch['type']."' />
									<br/>
									<input class='inpt' style='width:550px'  type='text' name='cat' value='".$fetch['cat']."' />
									<br/>
									<textarea  style='width:550px'class='inpt'  name='description'>".$fetch['description']."</textarea>
									<br/>
									<select name='action' style='width:550px' class='slct'>
										<option value='delete'>delete</option>
										<option value='save' selected>save</option>
									</select>
									<br/>
									<input class='inpt'  type='submit' name='save_meal' />
								</form>";
							} else
							{
								if(isset($_GET['s'])){
									if($_GET['s'] == 'show'){
										$itemsSql = mysql_query("SELECT * FROM `meals_data` WHERE `show`= 1 ORDER BY `status` DESC,`name` ASC");
									} else {
										$itemsSql = mysql_query("SELECT * FROM `meals_data` WHERE `type` = '".$_GET['s']."' ORDER BY `status` DESC,`name` ASC");
									}

									while($itemsFetch = mysql_fetch_assoc($itemsSql)){
										$sync_cols = array(
													"meal_name"=>$itemsFetch['name'],
													"price"=>$itemsFetch['price'],
													"type"=>$itemsFetch['type'],
													"meal_category"=>$itemsFetch['cat'],
													"meal_image"=>$itemsFetch['meal_image'],
													"meal_weight"=>$itemsFetch['meal_weight'],
													"show"=>$itemsFetch['show'],
													"meal_descr"=>$itemsFetch['description']
													);
										$color = 'black';
										if($itemsFetch['status'] == 0){
											$color = 'gray';
										}
										echo "<div class='col-100 item-single' style='color:".$color."'>";
											echo "<div>
													<div class='col-50'>
														<div class='hider item-single-name' tgt='item_".$itemsFetch['id']."'>
															".$itemsFetch['name']." ".$itemsFetch['price']."
														</div>
													</div>
													<div class='col-50'>";
														if($itemsFetch['site_id'] > 0){
															echo "<div class='meal_sync' row_id='".$itemsFetch['id']."' meal_id='".$itemsFetch['site_id']."' meal_info='".json_encode($sync_cols)."'>";
															if($itemsFetch['last_update'] > $itemsFetch['last_sync']){
																echo "(!)";
															}
															echo "sync</div>";
														}
													echo
													"</div>
												  </div>";
											echo "<div class='invisible item_".$itemsFetch['id']." description'>
													<div class='col-60'>
														<form method=post>
															<div style='padding: 5px'>
																<div>
																	<input type='hidden' name='id' value='".$itemsFetch['id']."'/>
																	<div style='width: 150px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Наименование</div>
																		<div>
																			<input class='inpt' style='width:100%' type='text' name='name' value='".$itemsFetch['name']."' placeholder='name'/>
																		</div>
																	</div>
																	<div style='width: 60px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Сбстоим.</div>
																		<div>
																			<input class='inpt' style='width:100%'  type='text' name='netcost' value='".$itemsFetch['netcost']."' placeholder='netcost' />
																		</div>
																	</div>
																	<div style='width: 60px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Цена</div>
																		<div>
																			<input class='inpt' style='width:100%'  type='text' name='price' value='".$itemsFetch['price']."' placeholder='price' />
																		</div>
																	</div>";
																	echo
																	"<div style='width: 70px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Вид</div>
																		<div>
																			<select class='slct-else' tgt-id='meal-type-".$itemsFetch['id']."'>
																				<option value=''>не назначен</option>";
																				$mealTypesSql = mysql_query("SELECT * FROM `meal_types` ORDER BY `type` ASC");
																				while($mealTypesFetch = mysql_fetch_assoc($mealTypesSql)){
																					echo "<option value='".$mealTypesFetch['type']."'"; if($itemsFetch['type']==$mealTypesFetch['type']){echo " selected='selected'";} echo">".$mealTypesFetch['type']."</option>";
																				}
																				echo
																				"<option value='else'>другой</option>
																			</select>
																			<input tgt-id='meal-type-".$itemsFetch['id']."' name='type' value='".$itemsFetch['type']."' class='slct-else-v hidden inpt' placeholder='Тип' style='width: 79%' />";
																	echo "</div>
																	</div>
																	<div style='width: 80px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Статус</div>
																		<div>
																			<select name='status'>
																				<option value='1'"; if($itemsFetch['status'] == 1){echo " selected ";}echo">Активно</option>
																				<option value='0'"; if($itemsFetch['status'] == 0){echo " selected ";}echo">Не активно</option>
																			</select>
																		</div>
																	</div>
																	<div style='width: 80px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Брутто</div>
																		<div>
																			<input class='inpt' style='width:100%'  type='text' name='meal_weight' value='".$itemsFetch['meal_weight']."' placeholder='meal_weight' />
																		</div>
																	</div>
																</div>
																<div>
																	<div style='width: 60px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Сайт</div>
																		<div>
																			<input class='inpt' style='width:50px'  type='text' name='site_id' value='".$itemsFetch['site_id']."' placeholder='site_id' />
																		</div>
																	</div>
																	<div style='width: 80px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Кат. сайт</div>
																		<div>
																			<input class='inpt' style='width:100%'  type='text' name='cat' value='".$itemsFetch['cat']."' placeholder='cat' />
																		</div>
																	</div>
																	<div style='width: 80px; display: inline-block; vertical-align: top'>
																		<div style='font-size: 10px'>Показывать</div>
																		<div>
																			<select name='show'>
																				<option value='1'"; if($itemsFetch['show'] == 1){echo " selected ";}echo">Показывать</option>
																				<option value='0'"; if($itemsFetch['show'] == 0){echo " selected ";}echo">Скрыт</option>
																			</select>
																		</div>
																	</div>
																</div>

																<select name='action' style='width:104px' class='slct'>
																	<option value='delete'>delete</option>
																	<option value='save' selected>save</option>
																</select>
															</div>
															<div style='padding: 5px'>
																<input class='inpt' style='width:550px'  type='text' name='meal_image' value='".$itemsFetch['meal_image']."' placeholder='meal_image' />
															</div>
															<div style='padding: 5px'>
																<input class='inpt' style='width:550px'  type='text' name='admin_comment' value='".$itemsFetch['admin_comment']."' placeholder='admin_comment' />
															</div>
															<div style='padding: 5px'>
																<textarea  style='width:550px; resize: vertical;";if(!empty($itemsFetch['description'])){echo"height:110px";}echo"' class='inpt'  name='description' placeholder='description'>".strip_tags($itemsFetch['description'])."</textarea>
															</div>
															<div style='padding: 5px'>
																<textarea  style='width:550px; resize: vertical;";if(!empty($itemsFetch['recipe'])){echo"height:110px";}echo"' class='inpt'  name='recipe' placeholder='description'>".strip_tags($itemsFetch['recipe'])."</textarea>
															</div>
															<div style='padding: 5px'>
																<input class='btn'  type='submit' name='save_meal' />
																last_update: ".date('d.m.Y',$itemsFetch['last_update'])."
																last_sync: ".date('d.m.Y',$itemsFetch['last_sync'])."
															</div>
														</form>
													</div>
													<div class='col-40'>
														<img src='".$itemsFetch['meal_image']."' style='height: 128px'/>
													</div>
												  </div>";
										echo "</div>";
									}
								} else {
									$itemsSql = mysql_query("SELECT * FROM `meals_data` ORDER BY `name` ASC");
									echo "<h4>Все</h4>";
									while($itemsFetch = mysql_fetch_assoc($itemsSql)){
										$sync_cols = array(
													"meal_name"=>$itemsFetch['name'],
													"price"=>$itemsFetch['price'],
													"type"=>$itemsFetch['type'],
													"meal_category"=>$itemsFetch['cat'],
													"meal_image"=>$itemsFetch['meal_image'],
													"meal_weight"=>$itemsFetch['meal_weight'],
													"show"=>$itemsFetch['show'],
													"meal_descr"=>$itemsFetch['description']
													);
										echo "<div class='col-100 item-single'>";
											echo "<div>
													<div class='col-50'>
														".$itemsFetch['id']."<div class='hider item-single-name' tgt='item_".$itemsFetch['id']."'>
															 ".$itemsFetch['name']." ".$itemsFetch['price']."
														</div>
													</div>
													<div class='col-50'>";
														if($itemsFetch['site_id'] > 0){
															echo "<div class='meal_sync' row_id='".$itemsFetch['id']."' meal_id='".$itemsFetch['site_id']."' meal_info='".json_encode($sync_cols)."'>";
															if($itemsFetch['last_update'] > $itemsFetch['last_sync']){
																echo "(!)";
															}
															echo "sync</div>";
														}
													echo
													"</div>
												  </div>";
											echo "<div class='invisible item_".$itemsFetch['id']." description'>
													<div class='col-60'>
														<form method=post>
															<div style='padding: 5px'>
																<input type='hidden' name='id' value='".$itemsFetch['id']."'/>
																<div style='display: inline-block; vertical-align: top; width: 150px'>
																	<div style='font-size: 8px'>Наименование</div>
																	<input class='inpt' style='width:150px' type='text' name='name' value='".$itemsFetch['name']."' placeholder='name'/>
																</div>
																<div style='display: inline-block; vertical-align: top; width: 60px'>
																	<div style='font-size: 8px'>Цена</div>
																	<input class='inpt' style='width:100%'  type='text' name='price' value='".$itemsFetch['price']."' placeholder='price' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 40px'>
																	<div style='font-size: 8px'>Тип</div>
																	<input class='inpt' style='width:100%'  type='text' name='type' value='".$itemsFetch['type']."' placeholder='type' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 50px'>
																	<div style='font-size: 8px'>Сайт</div>
																	<input class='inpt' style='width:100%'  type='text' name='site_id' value='".$itemsFetch['site_id']."' placeholder='site_id' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 40px'>
																	<div style='font-size: 8px'>Категория</div>
																	<input class='inpt' style='width:100%'  type='text' name='cat' value='".$itemsFetch['cat']."' placeholder='cat' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 40px'>
																	<div style='font-size: 8px'>Видимость</div>
																	<input class='inpt' style='width:100%'  type='text' name='show' value='".$itemsFetch['show']."' placeholder='show' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 40px'>
																	<div style='font-size: 8px'>Статус</div>
																	<input class='inpt' style='width:100%'  type='text' name='status' value='".$itemsFetch['status']."' placeholder='status' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 60px'>
																	<div style='font-size: 8px'>Вес</div>
																	<input class='inpt' style='width:100%'  type='text' name='meal_weight' value='".$itemsFetch['meal_weight']."' placeholder='meal_weight' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 60px'>
																	<div style='font-size: 8px'>Родитель</div>
																	<input class='inpt' style='width:100%'  type='text' name='parent_id' value='".$itemsFetch['parent_id']."' placeholder='meal_weight' />
																</div>
																<div style='display: inline-block; vertical-align: top; width: 104px'>
																	<div style='font-size: 8px'>Действие</div>
																	<select name='action' style='width:100%' class='slct'>
																		<option value='delete'>delete</option>
																		<option value='save' selected>save</option>
																	</select>
																</div>
															</div>
															<div style='padding: 5px'>
																<input class='inpt' style='width:550px'  type='text' name='meal_image' value='".$itemsFetch['meal_image']."' placeholder='meal_image' />
															</div>
															<div style='padding: 5px'>
																<input class='inpt' style='width:550px'  type='text' name='admin_comment' value='".$itemsFetch['admin_comment']."' placeholder='admin_comment' />
															</div>
															<div style='padding: 5px'>
																<textarea  style='width:550px; resize: vertical;";if(!empty($itemsFetch['description'])){echo"height:110px";}echo"' class='inpt'  name='description' placeholder='description'>".strip_tags($itemsFetch['description'])."</textarea>
															</div>
															<div style='padding: 5px'>
																<input class='btn'  type='submit' name='save_meal' />
																last_update: ".date('d.m.Y',$itemsFetch['last_update'])."
																last_sync: ".date('d.m.Y',$itemsFetch['last_sync'])."
															</div>
														</form>
													</div>
													<div class='col-40'>
														<img src='".$itemsFetch['meal_image']."' style='height: 128px'/>
													</div>
												  </div>";
										echo "</div>";
									}
								}
							}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(function(){
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
