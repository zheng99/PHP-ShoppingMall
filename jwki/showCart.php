<?
	
	if($mode == "delete")
	{
		$sql = "DELETE FROM $cart_table WHERE idx='$idx' and ip='$_SESSION[$sess_ip]' and time='$_SESSION[$sess_time]'";
		$result = mysql_query($sql);

		echo"
		<script>
			location.href = '$PHP_SELF?cmd=$cmd';
		</script>
		";
	}

	$sql ="SELECT * FROM $cart_table WHERE
			ip='$_SESSION[$sess_ip]' and time='$_SESSION[$sess_time]' ORDER BY idx ASC";

	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	if($data)
	{
		if($cmd == "showCart")
			$show = "";
		else
			$show = "none";
	echo"
	<table border=0 width=80%>
		<tr>
			<td class=ud>No</td>
			<td class=ud>Photo</td>
			<td class=ud>Product Name</td>
			<td class=ud>Price</td>
			<td class=ud>Color</td>
			<td class=ud>Size</td>
			<td class=ud>MCount</td>
			<td class=ud>Total PRice</td>
			<td class=ud style='display:$show;'>비고</td>
		</tr>";
		//style='display:none' 일시 안보임
		$cnt =0;

		$totalCount = 0;
		$sum = 0;

		while($data)
		{
			$cnt ++;
			$msql = "SELECT * FROM $model_table WHERE idx='$data[model]'";
			$mresult = mysql_query($msql);
			$info = mysql_fetch_array($mresult);

			$total = $data[price] * $data[mcount];
			$formatPrice = number_format($data[price]);
			$formatTotal = number_format($total);

			$totalCount += $data[mcount];
			$sum += $total;

			echo"
			<tr>
				<td class=ud>$cnt</td>
				<td class=ud><img src='data/model/1/$info[photo1]'</td>
				<td class=ud>$info[title]</td>
				<td class=ud>$formatPrice</td>
				<td class=ud>$data[color]</td>
				<td class=ud>$data[size]</td>
				<td class=ud>$data[mcount]</td>
				<td class=ud>$formatTotal</td>
				<td class=ud style='display:$show;'><input type=button value='Delete' onClick=\"location.href='$PHP_SELF?cmd=$cmd&mode=delete&idx=$data[idx]'\"></td>
			</tr>";
			$data = mysql_fetch_array($result);
		}
		$formatSum = number_format($sum);

		echo"
			<tr>
				<td colspan=6 class=ud>합계</td>
				<td class=ud>$totalCount</td>
				<td class=ud>$formatSum</td>
				<td class=ud style='display:$show;'></td>
			</tr>
	</table><br><br>";

	if($cmd == "showCart")
	{
	echo" <input type=button value='Order' onClick=\"location.href='$PHP_SELF?cmd=printOrder'\">";
	}

	}else
	{
		echo"Cart is empty.<br>";
	}


?>