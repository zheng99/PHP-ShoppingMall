<?
	//menu.php
	// 관리자이면, 관리자 메뉴

	if($_SESSION[$sess_level] >= $adminLevel) //9로 하지 않은 이유는 개발자 모드일때도 보려고
	{
		echo "
		<table border=1 width=90%>
			<tr height=40>
				<td>Admin Menu</td>
			</tr>
			<tr height=30>
				<td><a href='$PHP_SELF?cmd=manModel'>Product management</a></td>
			</tr>
			<tr height=30>
				<td><a href='$PHP_SELF?cmd=manOrder'>Order management</a></td>
			</tr>
		</table>
		";
	} else if($_SESSION[$sess_id]) //로그인 했으면
	{
		echo "
		<table border=1 width=90%>
			<tr height=40>
				<td>Member Menu</td>
			</tr>
			<tr height=30>
				<td><a href='$PHP_SELF?cmd=manOrder'>Order management</a></td>
			</tr>
		</table>
		";
	}

	echo "<br>
	<table border=1 width=90%>";

	$sql1 = "SELECT * FROM $cat1_table WHERE useflag='1' ORDER BY odr ASC";
	$result1 = mysql_query($sql1);
	$c1 = mysql_fetch_array($result1);

	while($c1)
	{
		echo "
		<tr>
			<td class=ud> $c1[title]</td>
		</tr>
		";
		$sql2 = "SELECT * FROM $cat2_table WHERE cat1=$c1[idx] and useflag='1' ORDER BY odr ASC";
		$result2 = mysql_query($sql2);
		$c2 = mysql_fetch_array($result2);

		while($c2)
		{
			echo"
			<tr>
				<td class=udR><a href='$PHP_SELF?cmd=showList&cat1=$c1[idx]&cat2=$c2[idx]'>$c2[title]</a></td>
			</tr>
			";
			$c2 = mysql_fetch_array($result2);
		}

		$c1 = mysql_fetch_array($result1);
	}


	echo "
	</table>
	";
?>