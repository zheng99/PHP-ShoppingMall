<?
	// db.inc.php

	$conn = mysql_connect("localhost", "jwki", "jwki2pass");
	mysql_select_db("jwki", $conn) or die("DB Select Error");
	mysql_query("set names utf8");

	$prefix = "";
	$user_table = "user_table";
	$cat1_table = "cat1_table";
	$cat2_table = "cat2_table";
	$model_table = "model_table";
	$cart_table = "cart_table";
	$order_table = "order_table";
	$item_table = "item_table";

?>
