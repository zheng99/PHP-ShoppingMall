<?
	// order.php

	$sql ="INSERT INTO $order_table VALUES(
		'', '$_SESSION[$sess_id]',
		'$o_name', '$o_zip', '$o_addr1', '$o_addr2', '$o_mobile',
		'$r_name', '$r_zip', '$r_addr1', '$r_addr2', '$r_mobile',
		'$momo', '', '1', now()
		)";
	$result = mysql_query($sql);

	
	if($result)
	{
		$msg = "Order Success";

		$osql = "select * from $order_table order by idx desc limit 1";
		$oresult = mysql_query($osql);
		$odata = mysql_fetch_array($oresult);

		$orderno = $odata[idx]; // 내 주문번호 키값.

		// 3. 카트 정보를 얻어와서 복사하기
		$csql = "select * from $cart_table where ip='$_SESSION[$sess_ip]' and time='$_SESSION[$sess_time]'
		order by idx asc";
		$cresult = mysql_query($csql);
		$cdata = mysql_fetch_array($cresult);
		while($cdata)
		{
			// 3-1. 복사 = item_table INSERT
			$isql = "INSERT into $item_table VALUES(
						'', '$orderno',
						'$cdata[model]', '$cdata[color]',
						'$cdata[size]', '$cdata[mcount]',
						'$cdata[price]'
					)";
			$iresult = mysql_query($isql);

			$cdata = mysql_fetch_array($cresult);
		}

		// 4. 카트 삭제
		$delsql = "DELETE FROM $cart_table WHERE  ip='$_SESSION[$sess_ip]' and time='$_SESSION[$sess_time]'";
		$delresult = mysql_query($delsql);

		$_SESSION[$sess_time] = "";

	}else
	{
		$msg = "Order Fail!!!";
	}

	echo "
	<script>
		alert('$msg');
		//location.href='$PHP_SELF';
	</script>
	";

?>