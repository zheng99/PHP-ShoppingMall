<?

	if($who)
	{
		$sql = "delete from $cart_table where idx='$who' and ip='$_SESSION[$sess_ip]' and time='$_SESSION[$sess_time]' ";
		$result = mysql_query($sql);
		echo "
		<script>

			location.href='$PHP_SELF?cmd=$cmd&cat1=$cat1&cat2=$cat2&idx=$idx';
		</script>
		";
	}

	if($mode == "putcart")
	{
		echo "Putcart<br>";
		$sql = "INSERT INTO $cart_table VALUES('',
					'$_SESSION[$sess_ip]', '$_SESSION[$sess_time]',
					'$_SESSION[$sess_id]',
					'$idx', '$color', '$size', '$count',
					'$price'
				)";
		$result = mysql_query($sql);
		echo "
		<script>2019-09-23

			location.href='$PHP_SELF?cmd=$cmd&cat1=$cat1&cat2=$cat2&idx=$idx';
		</script>
		";
	}



	$sql = "select * from $model_table where idx='$idx' ";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	if($data)
	{
		$price = $data[price];

		$data[price] = number_format($data[price]);
		echo "
		<script>
			function reprice()
			{
				var f = document.modelForm;
				// alert(f.count.value);
				// alert('$price');

				var total = f.count.value * $price;
				f.total.value = total;
			}
		</script>

		<form name=modelForm method=post action='$PHP_SELF?cmd=$cmd&cat1=$cat1&cat2=$cat2&idx=$idx&mode=putcart'>

		<input type=hidden name=price value='$price'>
		<table border=1 width=90%>
			<tr>
				<td width=50%>
					<img src='data/model/2/$data[photo1]'><br>
					이전....확대....다음
				</td>
				<td width=50%>
					<table border=0 width=100%>
						<tr>
							<td class=left>Product Name</td>
							<td class=right>$data[title]</td>
						</tr>
						<tr>
							<td class=left>Brand</td>
							<td class=right>$data[maker]</td>
						</tr>
						<tr>
							<td class=left>Origin</td>
							<td class=right>$data[origin]</td>
						</tr>
						<tr>
							<td class=left>Price</td>
							<td class=right>$data[price]원</td>
						</tr>
						<tr>`
							<td class=left>Size</td>
							<td class=right>
								<select name=size class=inputBox>";
									$split_size = explode("," , $data[size]);
									$cnt = 0;
									while($split_size[$cnt])
									{
										echo "<option value='$split_size[$cnt]'>$split_size[$cnt]</option>";
										$cnt ++;
									}
								echo "
								</select>
							</td>
						</tr>";

						echo "
						<tr>
							<td class=left>Color</td>
							<td class=right>
								<select name=color class=inputBox>";
									$split_color = explode("," , $data[color]);
									$cnt = 0;
									while($split_color[$cnt])
									{
										echo "<option value='$split_color[$cnt]'>$split_color[$cnt]</option>";
										$cnt ++;
									}
								echo "
								</select>
							</td>
						</tr>

						<tr>
							<td class=left>Count</td>
							<td class=right>
								<select name=count class=inputBox onChange=reprice()>";
								for($i=1; $i<=20; $i++)
								{
									echo "<option value='$i'>$i</option>";
								}

								echo"
								</select>
							</td>
						</tr>

						<tr>
							<td class=left>Total Price</td>
							<td class=right>
								<input type=text class=inputBox name=total value='$data[price]' readonly style='border:0px solid #FFFFFF;'>
							</td>
						</tr>

					</table>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<input type=submit value='Cart'>
				</td>
			</tr>";

			$data[memo] = nl2br($data[memo]);

			echo "
			<tr>
				<td colspan=2>";

				$sql = "select * from $cart_table where ip='$_SESSION[$sess_ip]' and time='$_SESSION[$sess_time]' and model='$idx'  order by idx asc";
				$result = mysql_query($sql);
				$info = mysql_fetch_array($result);

				// 사진, 수량, 색상, 합계, 비고(삭제)
				if($info)
				{
					echo "
					<script>
						function deleteCart(pidx)
						{
							location.href='$PHP_SELF?cmd=$cmd&cat1=$cat1&cat2=$cat2&idx=$idx&who='+pidx;
						}
					</script>

					<table border=0 width=100%>
						<tr>
							<td class=ud>Photo</td>
							<td class=ud>Color</td>
							<td class=ud>Size</td>
							<td class=ud>Count</td>
							<td class=ud>Total Price</td>
							<td class=ud>etc</td>
						</tr>";
						$countTotal = 0;
						$priceTotal = 0;
						while($info)
						{
							$sum = $price * $info[mcount];

							$countTotal += $info[mcount];
							$priceTotal += $sum;

							$printSum = number_format($sum);
							echo "
							<tr>
								<td class=ud><img src='data/model/1/$data[photo1]'></td>
								<td class=ud>$info[color]</td>
								<td class=ud>$info[size]</td>
								<td class=ud>$info[mcount]</td>
								<td class=ud>$printSum</td>
								<td class=ud><input type=button value='Delete' onClick=deleteCart($info[idx])></td>
							</tr>";

							$info = mysql_fetch_array($result);
						}

						$priceTotal = number_format($priceTotal);

						echo "
						<tr>
							<td colspan=3 class=ud>Total Price</td>
							<td class=ud>$countTotal</td>
							<td class=ud>$priceTotal</td>
							<td class=ud><input type=button value='Order' onClick=\"location.href='$PHP_SELF?cmd=printOrder'\"> </td>
						</tr>
						";

					echo "
					</table><br>
					";
				}


					echo "$data[memo]<br>";

					if($data[photo2])
					{
						echo "<img src='data/model/4/$data[photo2]'>";
					}
				echo "
				</td>
			</tr>
		</table></form>
		";
	}else
	{
		echo "NO DATA!!!<br>";
	}

?>