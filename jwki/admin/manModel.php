<?
	//manModel.php
	echo "Product Management";

	//�����ڸ޴� �̱⶧���� ���Ȼ� �� ��ȣ����� �Ѵ�.
	if($_SESSION[$sess_level] < $adminLevel)
	{
		echo "
		<script>
			alert('Wrong Access');
			location.href='$PHP_SELF';
		</script>
		";
		exit();
	}
	//cab �޴�
	$menuList[1] = "Section";
	$menuList[2] = "Group";
	$menuList[3] = "Product Registration";
	$menuList[4] = "Product List";
	$menuList[5] = "Test";

	$cnt = 1;

	if(!$sub) // ��ǰ������ ������ ù��° �޴��� ����
	{
		$sub = 1;
	}


	echo "
	<table border=0>
		<tr height=40>";
		while($menuList[$cnt])
		{
			if($sub == $cnt)
				$menuClass = "subMenuSelected";
			else
				$menuClass = "subMenu";

			echo "<td class=$menuClass><a href='$PHP_SELF?cmd=$cmd&sub=$cnt'>$menuList[$cnt]</a></td>";
			$cnt ++;

			if($menuList[$cnt]) // �̷��� ���ϸ� �� �������� ĭ�� �ϳ� ���Ƽ� ������� ������ �����
			{
				echo "<td width=10></td>";
			}
		}
		echo"
		</tr>
	</table><br><br>";

	if($sub ==1)
	{
		//�˻�
		if($mode == "insert")
		{
			$sql = "INSERT INTO $cat1_table VALUES(
				'', '$title', '$odr', '$useflag'
			)";
			$result = mysql_query($sql);
			if($result)
				$msg = "Insert Success";
			else
				$msg = "Insert Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub';
			</script>
			";

		}

		if($mode == "update")
		{
			$sql = "UPDATE $cat1_table SET
						title='$title',odr='$odr',
						useflag='$useflag',
						WHERE idx='$idx'
					";
			$result = mysql_query($sql);
			if($result)
				$msg = "Update Success";
			else
				$msg = "Update Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub';
			</script>
			";

		}

		// onsubmit ����� ���� �˻�
		// f.title.focus() Ÿ��Ʋ�� Ŀ�� �����̰� ���ش�
		echo "
		<script>
			function checkError()
			{
				var f = document.catForm;
				if(f.title.value == '')
				{
					alert('Title Insert');
					f.title.focus();
					return false;
				}
				if(f.odr.value == '')
				{
					alert('No Insert');
					f.odr.focus();
					return false;
				}
			}
		</script>

		<form name=catForm method=post
		action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=insert'
		onSubmit=\"return checkError()\">
		<table border=0 width=90%>
			<tr>
				<td colspan=4 class=menuTitle>Section</td>
			</tr>";

			// as newodr�� �̸��� �ٿ��� ������ �´�

			$sql = "SELECT MAX(odr) +1 AS newodr FROM cat1_table";
			$result = mysql_query($sql);
			$info = mysql_fetch_array($result);

			if($info[newodr])
				$newodr = $info[newodr];
			else
				$newodr = 1;

			echo "
			<tr>
				<td class=menuLeft>Title</td>
				<td class=menuRight>
					<input type=text name=title placeholder='Title Insert' class=inputBox>
				</td>
				<td class=menuLeft>No</td>
				<td class=menuRight>
					<input type=text name=odr placeholder='No' value=$newodr class=inputBox>
				</td>
			</tr>

			<tr>
				<td class=menuLeft>UseFlag</td>
				<td class=menuRight>
					<select name=useflag class=inputBox>
						<option value='1'>Use</option>
						<option value='0'>------</option>
					</select>
				</td>

				<td colspan=2 class=menuLeft>
					<input type=submit class=submit value='Registration'>
				</td>
			</tr>

		</table></form><br><br>
		";

		$sql = "SELECT * FROM $cat1_table";
		$result = mysql_query($sql);
		$data = mysql_fetch_array($result);

		if($data)
		{
			echo "
			<table border=0 width=90%>
				<tr>
					<td class=ud>No</td>
					<td class=ud>Title</td>
					<td class=ud>UseFlag</td>
					<td class=ud>etc</td>
				</tr>
				";
				while($data)
				{
					//value �⺻���� 1�̹Ƿ� mark���� �ٿ��༭ 0�ΰ��� Ȯ�����ش�
					if($data[useflag] ==0)
					{
						$mark = "selected";
					} else
						$mark = "";
					echo "
					<form method=post action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=update&idx=$data[idx]'>
					<tr>
						<td class=ud><input type=text
						name=odr value='$data[odr]'>
						</td>
						<td class=ud><input type=text
						name=title value='$data[title]'>
						</td>
						<td class=ud>
							<select name=useflag>
								<option value='1'>Use</option>
								<option value='0'$mark>------</option>
							</select>
						</td>
						<td class=ud>
							<input type=submit value='Registration'>
							<input type=submit value='Delete'>
						</td>
					</tr>
					</form>
					";
					$data = mysql_fetch_array($result);
				}
			echo "
			</table>
			";
		} else
		{
			echo "No Data";
		}

	}

?>
