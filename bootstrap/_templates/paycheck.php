<?php
	include_once('../_php/db_con.php');
	date_default_timezone_set('Asia/Almaty');
	header("Content-Type: text/html; charset=utf-8");

	function formatPhone($phone){
		if(strlen($phone) == 11){
			$code = substr($phone, 1, 3);
			$part1 = substr($phone, 4, 3);
			$part2 = substr($phone, 7, 2);
			$part3 = substr($phone, 9, 2);
			$result = "".$code."-".$part1."-".$part2."-".$part3;
		} else {
			$result = $phone;
		}
		return $result;
	}
?>
<html>
	<head>
		<style>
			*{padding: 0; margin:0; font-family: Ubuntu Condensed, Arial; font-size: 16px;}
		</style>
		<meta charset="utf-8" />
	</head>
	<body>
		<div style='width: 350px; page-break-after: always; d1isplay: none' align=center>
			<table style='width: 100%'>
				<tr>
					<td align=center><img src='../_css/logo.png' style='width: 250px'/></td>
				</tr>
				<tr>
				<tr>
					<td align=center style=' font-size: 12px'>
						<div style='border: 1px solid; border-radius: 20px; padding: 5px 20px; font-size: 15px; display: inline-block; margin-top: 20px'>@omelette.aksu  |  +7 747 358-46-36</div>
					</td>
				</tr>
				<tr>
					<td align=center style='padding: 20px 10px 0 10px; font-size: 12px'>
						<table cellpadding=0 cellspacing=0>
							<tr>
								<td style='border-top: 2px solid; border-right: 1px solid; font-weight: bold; width: 68%; padding: 5px; font-size: 12px' align=center><nobr style='font-size: 11px'>Наименование</nobr></td>
								<td style='border-top: 2px solid; border-right: 1px solid; font-weight: bold; width: 1%; padding: 5px 1px;' align=center><nobr style='font-size: 11px'>Кол.</nobr></td>
								<td style='border-top: 2px solid; border-right: 1px solid; font-weight: bold; width: 12%; padding: 5px; font-size: 12px' align=center><nobr style='font-size: 11px'>Цена</nobr></td>
								<td style='border-top: 2px solid; font-weight: bold; width: 15%; padding: 5px; font-size: 12px' align=center><nobr style='font-size: 11px'>Сумма</nobr></td>
							</tr>
							<?php
								$sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `id`='".(int)$_GET['id']."'");
								$fetch = mysql_fetch_assoc($sql);
								$sql2 = mysql_query("SELECT * FROM `users_data` WHERE `user_phone`='".$fetch['user_phone']."' LIMIT 1");
								$fetch2 = mysql_fetch_assoc($sql2);
								$sql3 = mysql_query("SELECT * FROM `turn_orders_data` WHERE `user_phone`='".$fetch['user_phone']."'");
								$fetch3 = mysql_fetch_assoc($sql3);

								$details = explode("\n",$fetch['order_details']);
								$summ=0;
								$pizzaS = '';
								$sushiS = '';
								$kitchenS = '';
								for($i=0;$i<count($details);$i++){
									preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$details[$i],$matches);
									$summ+=$matches[2]*$matches[3];
									if($matches[4] == 'P'){$pizzaS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'EP'){$pizzaS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'S'){$sushiS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'ES'){$sushiS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'K'){$kitchenS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'KGOR'){$kitchenS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'KSUP'){$kitchenS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'KSLT'){$kitchenS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'EK'){$kitchenS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'SH'){$shashlikS .= $matches[1]." (".$matches[2].")<br/>";}
									if($matches[4] == 'ESH'){$shashlikS .= $matches[1]." (".$matches[2].")<br/>";}
									echo
									"<tr>
										<td style='border-top: 1px solid; border-right: 1px solid; padding: 3px 5px; font-size: 14px'>".$matches[1]."</td>
										<td align=center style='border-top: 1px solid; border-right: 1px solid; padding: 3px 5px; font-size: 14px'>".$matches[2]."</td>
										<td style='border-top: 1px solid; border-right: 1px solid; padding: 3px 5px; font-size: 14px'>".$matches[3]."</td>
										<td style='border-top: 1px solid; padding: 3px 5px; font-size: 14px'>".$matches[2]*$matches[3]."</td>
									</tr>";
								}
								if($fetch['discount'] > 0){
									echo
									"<tr>
										<td style='border-top: 1px solid; b1order-right: 1px solid; padding: 5px; font-size: 14px' colspan='3'>Скидка ".$fetch['discount_comment']."</td>
										<td style='border-top: 1px solid; b1order-right: 1px solid; padding: 5px; font-size: 14px' align=left>-".$fetch['discount']."</td>
									</tr>";
									echo
									"<tr>
										<td style='border-top: 1px solid; padding: 7px 0 0 0; font-size: 12px'>#".(int)$_GET['id']."".date('ymdHi',$fetch['time'])."</td>
										<td style='border-top: 1px solid; p1adding: 5px; font-size: 12px' align=right>Итого: </td>
										<td style='border-top: 1px solid; border-bottom: 1px solid; padding: 5px; font-size: 14px; font-weight: bold' colspan=2 align=right>".($summ-$fetch['discount'])."тг</td>
									</tr>";
								} else
								{
									echo
									"<tr>
										<td style='border-top: 1px solid; padding: 7px 0 0 0; font-size: 12px'>#".(int)$_GET['id']."".date('ymdHi',$fetch['time'])."</td>
										<td style='border-top: 1px solid; p1adding: 5px; font-size: 12px' align=right>ИТОГО: </td>
										<td style='border-top: 1px solid; border-bottom: 1px solid; padding: 5px; font-size: 15px; f1ont-weight: bold' colspan=2 align=center>".number_format($summ,0,'',' ')."тг</td>
									</tr>";
									if($fetch['cash']>0){
										echo
										"<tr>
											<td colspan=2 style='b1order-top: 1px solid; p1adding: 5px; font-size: 11px' align=right>НАЛИЧНЫЕ: </td>
											<td style='b1order-top: 1px solid; b1order-bottom: 1px solid; padding: 5px 5px 0px 5px; font-size: 14px; f1ont-weight: bold' colspan=2 align=center>".number_format($fetch['cash'],0,'',' ')."</td>
										</tr>";
										echo
										"<tr>
											<td colspan=2 style='b1order-top: 1px solid; padding: 0 0 5px 5px; font-size: 11px' align=right>СДАЧА: </td>
											<td style='b1order-top: 1px solid; b1order-bottom: 1px solid; padding: 0 5px 5px 5px; font-size: 14px; f1ont-weight: bold' colspan=2 align=center>".number_format(($fetch['cash']-$summ),0,'',' ')."</td>
										</tr>";
									}
								}
								mysql_query("UPDATE `turn_orders_data` SET `check_printed` = 1 WHERE `id` = '".$fetch['id']."'");
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td align=center style='padding: 10px 10px 0 10px; font-size: 12px'>
						<table cellpadding=0 cellspacing=0 style='width: 100%'>
							<?php
								if($fetch['courier'] == 'самовывоз' or $fetch['courier'] == 'зал'){
									echo
									"<tr>
										<td colspan=3 align=center>
											<div style='border: 1px dashed #999;padding: 10px; margin-top: 15px'>
												<i style='font-size: 12px; line-height: 18px;'>Спасибо, что нашли время к нам заглянуть.</i>
											</div>
										</td>
									</tr>";
								} else {
									echo
									"<tr>
										<td align=left style='width: 50%;padding: 1px 0 1px 7px; font-size: 18px ' align=left>".formatPhone($fetch['user_phone'])."</td>
										<td colspan=1 align=right style='b1order-bottom: 1px dashed #999;padding-top: 2px; font-size: 14px; padding-bottom: 2px; padding-right: 10px'>".$fetch['address']."</td>
									</tr>";
								}
								if($fetch['comment']!=''){
									echo
									"<tr>
										<td colspan=2 style='b1order-bottom: 1px dashed #999;padding-top: 5px;  ' align=center><i style='font-size: 12px'>".$fetch['comment']."</i></td>
									</tr>";
								}
								if(mysql_num_rows($sql3) == 100000000000000){
									echo
									"<tr>
										<td colspan=3 align=center>
											<div style='border: 1px dashed #999;padding: 10px; margin-top: 15px'>
												<i style='font-size: 12px; line-height: 18px;'>
													Добро пожаловать в Омлетную семейку!<br/>
													Заказывайте ваши любимые блюда онлайн!<br/>
													С новой системой заказывать легче и быстрее!<br/>
													Попробуйте сами на сайте <b style='font-size: inherit'>www.omelette.kz</b></i>
											</div>
										</td>
									</tr>";

									/*
									$sqlCount = mysql_query("SELECT * FROM `orders_journal_data` WHERE `user_phone`='".$fetch['user_phone']."'");
									echo
									"<tr>
										<td style='width: 20%;padding: 5px; font-size: 12px ' align=left></td>
										<td colspan=2 style='b1order-bottom: 1px dashed #999;padding-top: 15px;  ' align=center><i style='font-size: 12px'>Заказов ".mysql_num_rows($sqlCount)."</i></td>
									</tr>";
									*/
								}
							?>
							<!--tr>
								<td style='width: 20%;padding: 5px; font-size: 12px ' align=left>Перевозчик:</td>
								<td align=center style='border-bottom: 1px dashed #999;font-size: 12px; padding-top: 15px'><?php echo $fetch['courier'];?></td>
							</tr-->
						</table>
					</td>
				</tr>
				<!--
				<tr>
					<td align=center style='padding: 10px 10px 0 10px; font-size: 24px; f1ont-style: italic'>
						<div style='padding: 10px; border: 1px dashed #333;'>
							<img src='https://banner2.kisspng.com/20180602/gy/kisspng-snowflake-coloring-book-light-child-snow-flake-5b1243d55abb59.4363798415279236693717.jpg' style='height: 18px;   vertical-align: middle;' />
							<?  echo "До Нового Года ".(date('z', strtotime(date('31.12.Y 23:59:59'))) - date('z')). " дн."; ?>
							<img src='https://banner2.kisspng.com/20180602/gy/kisspng-snowflake-coloring-book-light-child-snow-flake-5b1243d55abb59.4363798415279236693717.jpg' style='height: 18px;   vertical-align: middle;' />
						</div>
					</td>
				</tr>
				-->
			</table>
		</div>
		<?php
			$preOrder = '';
			if( $fetch['time'] > time()){
				$preOrder = "<div align='left' style='padding-left: 20px'>Предзаказ на ".date('H:i', $fetch['time'])."</div>";
			}
			if($pizzaS != '' && $_GET['courier'] != 'зал'){
				echo
				"<div style='width: 340px; padding: 0 5px; page-break-after: always; line-height: 20px; font-size: 20px;' align=center>".
					$preOrder.
					"<div>".$pizzaS."</div>".
				"</div>";
			}
			if($kitchenS != '' && $_GET['courier'] != 'зал'){
				echo
				"<div style='width: 340px; padding: 0 5px;  page-break-after: always; line-height: 20px; font-size: 20px;' align=center>".
					$preOrder.
					"<div>".$kitchenS."</div>".
				"</div>";
			}
			if($sushiS != '' && $_GET['courier'] != 'зал'){
				echo
				"<div style='width: 340px; padding: 0 5px;  page-break-after: always; line-height: 20px; font-size: 20px;' align=center>".
					$preOrder.
					"<div>".$sushiS."</div>".
				"</div>";
			}
			if($shashlikS != '' && $_GET['courier'] != 'зал'){
				echo
				"<div style='width: 340px; padding: 0 5px;  page-break-after: always; line-height: 20px; font-size: 20px;' align=center>".
					$preOrder.
					"<div>".$shashlikS."</div>".
				"</div>";
			}

		if(isset($_GET['instaprint'])){
			echo
			"<script>
				window.print();
				setTimeout(function(){window.close(); }, 1000);
				//setTimeout(function(){ window.open('./paycheck_meals_only.php?id=".(int)$_GET['id']."','newwindow','width=350,height=350'); w1indow.close(); }, 1000);
			</script>";
		}
		?>
	</body>
</html>
