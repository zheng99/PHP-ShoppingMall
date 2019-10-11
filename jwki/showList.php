<?
	// showList.php


	$MPL = 3;

	$sql = "select * from $model_table where cat1='$cat1' and cat2='$cat2' and useflag='1' order by odr asc";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	if($data)
	{
		echo "
		<table border=0>";

		$cnt = 0;
		while($data)
		{
			$cnt ++;

			if($cnt % $MPL == 1)
				echo "<tr>";

			$data[price] = number_format($data[price]);

			echo "<td width=200>
					<a href='$PHP_SELF?cmd=showModel&cat1=$cat1&cat2=$cat2&idx=$data[idx]'><img src='data/model/2/$data[photo1]' style='border-radius:10px;'></a><br>
					$data[title]<br>
					$data[price]
				</td>";

			if($cnt % $MPL == 0)
				echo "</tr>";

			$data = mysql_fetch_array($result);
		}

		echo "
		</table><br>";
	}else
	{
		echo "No DATA <br>";
	}


?>