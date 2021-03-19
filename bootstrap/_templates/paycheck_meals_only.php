<?php  
	include_once('../_php/db_con.php');
	header("Content-Type: text/html; charset=utf-8");
?>
<html>
	<head>
		<style>
			*{padding: 0; margin:0; font-family: Cuprum, Arial; font-size: 16px;}
		</style>
	</head>
	<body>
		<?php
			$sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `id`='".(int)$_GET['id']."'");
			$fetch = mysql_fetch_assoc($sql);

			$details = explode("\n",$fetch['order_details']);
			$summ=0;
			$pizzaS = '';
			$sushiS = '';
			$kitchenS = '';
			for($i=0;$i<count($details);$i++){
				preg_match('/(.+\(?)\((.+?)\)\[(.+?)\]\{(.+?)\}/',$details[$i],$matches);
				$summ+=$matches[2]*$matches[3];
				if($matches[4] == 'P'){$pizzaS .= $matches[1]." (".$matches[2].")<br/>";}
				if($matches[4] == 'S'){$sushiS .= $matches[1]." (".$matches[2].")<br/>";}
				if($matches[4] == 'K'){$kitchenS .= $matches[1]." (".$matches[2].")<br/>";}
			}
			if($pizzaS != '' && $_GET['courier'] != 'зал'){echo "<div style='width: 350px; page-break-after: always; line-height: 36px; font-size: 20px;' align=center>".$pizzaS."</div>";}
			if($sushiS != '' && $_GET['courier'] != 'зал'){echo "<div style='width: 350px; page-break-after: always; line-height: 36px; font-size: 20px;' align=center>".$sushiS."</div>";}
			if($kitchenS != '' && $_GET['courier'] != 'зал'){echo "<div style='width: 350px; page-break-after: always; line-height: 36px; font-size: 20px;' align=center>".$kitchenS."</div>";}
		?>
		<script>
			window.print();
			setTimeout(function(){ window.close() }, 300);
		</script>
	</body>
</html>
