<?php
  header('Access-Control-Allow-Origin: *');
 if(isset($_GET['get_events_log'])){
   include_once('./db_con.php');
	$sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `status` = '0' AND `is_editable` = 1 ORDER BY `time`,`id` DESC") or die(mysql_error());
	if(mysql_num_rows($sql)>0){
		echo
       "<div style='padding: 20px; border: 1px solid #ABB9FF; background: white; color: black; position: relative;border-radius: 3px; margin-bottom: 2px'>".
			"<span style='font-size: 12px'>".mysql_num_rows($sql)." заказы с сайта. <a href='./'>обновить страницу</a></span>".
       //"<div class='close-event-btn' data-id='".$fetch['id']."' style='color: white; position: absolute; top: 5px; right: 5px; width: 15px; height: 15px; text-align: center; line-height: 15px;  background: #333; border-radius: 50%; cursor: pointer; z-index: 1'>x</div>".
       "</div>";
	}
	$sql = mysql_query("SELECT * FROM `events_log_data` WHERE `status` = '0' ORDER BY `time`,`id` DESC") or die(mysql_error());
	while($fetch = mysql_fetch_assoc($sql)){
		echo
       "<div style='padding: 20px; border: 1px solid #ABB9FF; background: white; color: black; position: relative;border-radius: 3px; margin-bottom: 2px'>".
       $fetch['text'].
       "<div class='close-event-btn' data-id='".$fetch['id']."' style='color: white; position: absolute; top: 5px; right: 5px; width: 15px; height: 15px; text-align: center; line-height: 15px;  background: #333; border-radius: 50%; cursor: pointer; z-index: 1'>x</div>".
       "</div>";
	}
	exit;
 } else
if(isset($_GET['get_user_orders_numb'])){
  include_once('./db_con.php');
  $up = $_GET['get_user_orders_numb'];
  $sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `user_phone` = '".$up."' AND `time` > '".strtotime(date('01.m.Y 00:00:00'))."' ORDER BY `time`,`id` DESC") or die(mysql_error());
  echo "[".mysql_num_rows($sql)."]";
  $sql = mysql_query("SELECT * FROM `turn_orders_data` WHERE `user_phone` = '".$up."' ORDER BY `time`,`id` DESC") or die(mysql_error());
  echo "[".mysql_num_rows($sql)."]";
  exit;
} else
 if(isset($_GET['insert_events_log'])){
    include_once('./db_con.php');
    mysql_query("INSERT INTO `events_log_data`(`time`,`status`,`text`) VALUES('".time()."','0','".$_GET['insert_events_log']."')");
    exit;
 } else
 if(isset($_GET['delete_events_log'])){
    include_once('./db_con.php');
    $rowId = (int) $_GET['delete_events_log'];
    mysql_query("DELETE FROM `events_log_data` WHERE `id` = '".$rowId."' LIMIT 1") or die(mysql_error());
    exit;
  }
?>
