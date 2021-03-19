<?
 $con = mysql_connect("localhost","v_15697_root","oMwT7bz9");
 if (!$con)
   {
   die('Could not connect: ' . mysql_error());
   }
mysql_select_db("v-15697_alina", $con);
mysql_query("SET NAMES 'utf8'");
//mysql_query("SET NAMES 'cp1251'");

function sqlSafe($value){
	/* sql safe */
	$value = strip_tags(htmlentities($value));
	return $value;
}
?>
