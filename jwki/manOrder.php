
<?
	// manOrder.php
		$cnt = 1;

	if(!$sub)
	{
		$sub = 1;
	}

	echo "<br>
	<table border=0>
		<tr height=40>";

		while($status_list[$cnt])
		{
			if($sub == $cnt)
				$menuClass = "subMenuSelected";
			else
				$menuClass = "subMenu";

			if($_SESSION[$sess_level] >= $adminLevel)
			{
				$sql = "select * from $order_table where status='$cnt' ";
			} else if($_SESSION[$sess_id])
			{
				$sql = "select * from $order_table where status='$cnt' and id='$_SESSION[$sess_id]' ";
			}
			$result = mysql_query($sql);
			$count = mysql_num_rows($result);

			echo "<td class=$menuClass><a href='$PHP_SELF?cmd=$cmd&sub=$cnt'>$status_list[$cnt]</a><br>[ <font color='#FF0000'><b>$count</b></font> ]</td>";
			$cnt ++;

			if($status_list[$cnt])
			{
				echo "<td width=10></td>";
			}
		}
		echo"
		</tr>
	</table><br><br>";

	$sql = "SELECT * FROM $order_table where status='$sub' and id='$_SESSION[$sess_id]'  order by idx DESC";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	if($data)
	{
		// 순서, 주문자, 전화, 수령인, 전화, 금액, 주문일, 비고
		echo "
		<table border=0 width=90%>
			<tr>
				<td class=ud>No</td>
				<td class=ud>Order Name</td>
				<td class=ud>O_Phone</td>
				<td class=ud>Recipient</td>
				<td class=ud>R_Phone</td>
				<td class=ud>Total Price</td>
				<td class=ud>Order Date</td>
				<td class=ud>etc</td>
			</tr>";
			$cnt = 0;
			while($data)
			{
				$cnt ++;

				$days = explode(" ", $data[time]);
				if($days[0] == $today)
					$printDay = substr($days[1], 0, 5);
				else
					$printDay = $days[0];

				$psql = "select sum(price * mcount) as total from $item_table where orderno='$data[idx]' ";
				$presult = mysql_query($psql);
				$pdata = mysql_fetch_array($presult);

				$price = number_format($pdata[total]);

				echo "
				<tr>
					<td class=ud>$cnt</td>
					<td class=ud>$data[o_name]</td>
					<td class=ud>$data[o_mobile]</td>
					<td class=ud>$data[r_name]</td>
					<td class=ud>$data[r_mobile]</td>
					<td class=ud>$price</td>
					<td class=ud>$printDay</td>
					<td class=ud><input type=button value='MyOrder' onClick=\"window.open('showOrder.php?idx=$data[idx]', 'MYORDER', 'resizable=yes scrollbars=yes width=700 height=700')\"></td>
				</tr>";
				$data = mysql_fetch_array($result);
			}
		echo "
		</table><br><br>
		";

	}else
	{
		echo "NO DATA!!!<br><br>";
	}


?>