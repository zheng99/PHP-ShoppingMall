<?
	session_save_path("sess");
	session_start();

	include "inc/config.inc.php";
	include "inc/db.inc.php";
	include "inc/function.inc.php";

	$now = getNow();
	$today = today();

	if(!$_SESSION[$sess_time])
	{
		$_SESSION[$sess_time] = $now;
		$_SESSION[$sess_ip] = $REMOTE_ADDR;
	}
?>

<!doctype html>
<html lang="ko">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport"
		content="width=device-width,
			user-scaleale=yes,
			maximum-scale=3.0" />

  <title>Detail Order</title>
  <link rel="stylesheet" href="css/style.css">
 </head>
 <body>
 <?
	if($mode == "update")
	{
		$sql = "update $order_table set
					logisno='$logisno', status='$status'
				where idx='$idx' ";
		$result = mysql_query($sql);
		echo "
		<script>
			opener.location.href='main.php?cmd=manOrder&sub=$status';
			location.href='$PHP_SELF?idx=$idx';
		</script>";
	}


	$sql = "select * from $item_table
				WHERE
				orderno='$idx'
				ORDER by idx ASC";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	if($data)
	{ // 순서, 사진, 제품명, 가격, 색상, 사이즈, 수량, 합계, 비고

		if($cmd == "showCart")
			$show = "";
		else
			$show = "none";

		echo "
		<table border=0 width=90%>
			<tr>
				<td class=ud>No</td>
				<td class=ud>Photo</td>
				<td class=ud>Product Name</td>
				<td class=ud>Price</td>
				<td class=ud>Color</td>
				<td class=ud>Size</td>
				<td class=ud>Count</td>
				<td class=ud>Total Price</td>
				<td class=ud style='display:$show;'>etc</td>
			</tr>";
			$cnt = 0;


			$totalCount = 0;
			$sum = 0;

			while($data)
			{
				$cnt ++;

				$msql = "select * from $model_table
							where idx='$data[model]' ";
				$mresult = mysql_query($msql);
				$info = mysql_fetch_array($mresult);

				$total = $data[price] * $data[mcount];
				$formatPrice = number_format($data[price]);
				$formatTotal = number_format($total);

				$totalCount += $data[mcount];
				$sum += $total;

				echo "
				<tr>
					<td class=ud>$cnt</td>
					<td class=ud><img src='data/model/1/$info[photo1]'></td>
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

		echo "
			<tr>
				<td colspan=6 class=ud>Total Price</td>
				<td class=ud>$totalCount</td>
				<td class=ud>$formatSum</td>
				<td class=ud style='display:$show;'></td>
			</tr>
		</table><br><br>";
	}

	if($_SESSION[$sess_level] >= $adminLevel)
	{
		$sql = "select * from $order_table where idx='$idx' ";
	} else if($_SESSION[$sess_id])
	{
		$sql = "select * from $order_table where idx='$idx' and id='$_SESSION[$sess_id]' ";
	}
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	echo "
	<table border=0 width=90%>
		<tr>
			<td colspan=4 class=menuRight>Order Info</td>
		</tr>
		<tr>
			<td class=menuLeft>Name</td>
			<td class=menuRight><b>$data[o_name]</b></td>
			<td class=menuLeft>ID</td>
			<td class=menuRight><b>$data[id]</b></td>
		</tr>

		<tr>
			<td class=menuLeft>O_Phone</td>
			<td class=menuRight><b>$data[o_mobile]</b></td>
			<td class=menuLeft>Zip code</td>
			<td class=menuRight><b>$data[o_zip]</b></td>
		</tr>
		<tr>
			<td class=menuLeft>Address</td>
			<td class=menuRight colspan=3><b>$data[o_addr1]</b></td>
		</tr>
		<tr>
			<td class=menuLeft>Address2</td>
			<td class=menuRight colspan=3><b>$data[o_addr2]</b></td>
		</tr>

		<tr>
			<td colspan=4 class=menuRight>Recipient Info</td>
		</tr>
		<tr>
			<td class=menuLeft>Name</td>
			<td class=menuRight><b>$data[r_name]</b></td>
			<td class=menuLeft></td>
			<td class=menuRight></td>
		</tr>

		<tr>
			<td class=menuLeft>R_Phone</td>
			<td class=menuRight><b>$data[r_mobile]</b></td>
			<td class=menuLeft>우편번호</td>
			<td class=menuRight><b>$data[r_zip]</b></td>
		</tr>
		<tr>
			<td class=menuLeft>Address</td>
			<td class=menuRight colspan=3><b>$data[r_addr1]</b></td>
		</tr>
		<tr>
			<td class=menuLeft>Address2</td>
			<td class=menuRight colspan=3><b>$data[r_addr2]</b></td>
		</tr>
		<tr>
			<td class=menuLeft>Request</td>
			<td class=menuRight colspan=3>
				<textarea name=memo class=inputBox style='height:100px; line-height:200%;'>$data[memo]</textarea>
			</td>
		</tr>
		<form name=orderForm method=post action='$PHP_SELF?mode=update&idx=$idx'>

		<tr>
			<td class=menuLeft>Delivery number</td>
			<td class=menuRight><input type=text name=logisno class=inputBox style='width:50%;' value='$data[logisno]'>
			<input type=button value='Search' onClick=\"window.open('https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=$data[logisno]','LOGIS','resizable=yes scrollbars=yes width=700 height=700')\"></td>
			<td class=menuLeft>Order status</td>
			<td class=menuRight>
				<select name=status class=inputBox style='background-color:#00FF00;'>";

				$cnt = 1;
				while($status_list[$cnt])
				{
					if($data[status] == $cnt)
						$mark = "selected";
					else
						$mark = "";

					echo "<option value='$cnt' $mark >$status_list[$cnt]</option>";
					$cnt ++;
				}

				echo "
				</select>
			</td>
		</tr>

		<tr>
			<td colspan=4 class=menuLeft>
				<input type=submit value='Change state' class=submit>
				<input type=button value='Close' class=submit onClick=window.close()>
			</td>
		</tr>
	</table>";


 ?>
	<script> //상세창 앞으로 튀어나오기
		self.focus();
	</script>
 </body>
</html>
<?
	mysql_close($conn);
?>