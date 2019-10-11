<?
	// manModel.php
	echo "Product Management";

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

	$menuList[1] = "Section";
	$menuList[2] = "Group";
	$menuList[3] = "Product Registration";
	$menuList[4] = "Product List";
	$menuList[5] = "Test";

	$cnt = 1;

	if(!$sub)
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

			echo "<td class=$menuClass><a href='$PHP_SELF?cmd=$cmd&sub=$cnt'> $menuList[$cnt]</a></td>";
			$cnt ++;

			if($menuList[$cnt])
			{
				echo "<td width=10></td>";
			}
		}
		echo"
		</tr>
	</table><br><br>";

	if($sub == 4)
	{

		if($mode == "delete")
		{
			$sql = "SELECT * FROM $model_table WHERE idx='$idx'";
			$result = mysql_query($sql);
			$data = mysql_fetch_array($result);

			if($data[photo1])
			{
				for($i=0; $i<=3; $i++)
				{
					@unlink("data/model/$i/$data[photo1]");
				}
			}
			if($data[photo2])
			{
				for($i=0; $i<=4; $i++)
				{
					@unlink("data/model/$i/$data[photo2]");
				}

			}
			$sql = "delete from $model_table where idx='$idx'";
			$result = mysql_query($sql);
			if($result)
				$msg = "Delete Success";
			else
				$msg = "Delete Fail";


			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$data[cat1]&cat2=$data[cat2]';
			</script>
			";
		}
		echo "
			<script>
			function changeCat1()
			{
				var f = document.modelForm;
				if(f.cat1.value != '0')
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1='+f.cat1.value;
			}
			function changeCat2()
			{
				var f = document.modelForm;
				if(f.cat2.value != '0')
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$cat1&cat2='+f.cat2.value;
			}
			</script>
			<form name=modelForm>
				<table border=0 width=90%>
				<tr>
					<td colspan=4 class=menuTitle>Product Registration</td>
				</tr>
				<tr>
					<td class=menuLeft>Section</td>
					<td class=menuRight>
						<select name=cat1 class=inputBox onChange=changeCat1()>
							<option value='0'>== Section ==</option>
							";

							$c1sql = "SELECT * FROM $cat1_table ORDER BY odr ASC";
							$c1r = mysql_query($c1sql);
							$c1 = mysql_fetch_array($c1r);


							while($c1)
							{
								if($cat1 == $c1[idx])
								$mark = "selected";
							else
								$mark = "";
								echo "<option value='$c1[idx]' $mark>$c1[title]</option>";
								$c1 = mysql_fetch_array($c1r);
							}

						echo "
						</select>
					</td>
					<td class=menuLeft>Group</td>
					<td class=menuRight>
						<select name=cat2 class=inputBox onChange=changeCat2()>
							<option value='0'>== Group ==</option>
							";
							$c2sql = "SELECT * FROM $cat2_table WHERE cat1='$cat1' ORDER BY odr ASC";
							$c2r = mysql_query($c2sql);
							$c2 = mysql_fetch_array($c2r);


							while($c2)
							{
								if($cat2 == $c2[idx])
								$mark = "selected";
							else
								$mark = "";
								echo "<option value='$c2[idx]' $mark>$c2[title]</option>";
								$c2 = mysql_fetch_array($c2r);
							}

						echo "
						</select>
					</td>
				</tr>
				</table></form><br><br>";



			$sql = "SELECT * FROM $model_table WHERE cat1='$cat1' and cat2 ='$cat2' ORDER BY odr ASC";
			$result = mysql_query($sql);
			$data = mysql_fetch_array($result);

			$colors = explode(',',$data[color]);


			//순서, 사진, 제품명, 가격, 사용, 비고(수정, 삭제)
			if($data)
			{
				echo "
				<table border=0 width=90%>
					<tr>
						<td class=ud>No</td>
						<td class=ud>Photo</td>
						<td class=ud>Product Name</td>
						<td class=ud>Price</td>
						<td class=ud>Color</td>
						<td class=ud>etc</td>
					</tr>";

				while($data)
				{
					// 만원단위 , 찍어주는 기능
					$data[price] = number_format($data[price]);

					if($data[useflag] == 1)
					{
						$printUse = "YES";
					} else
					{
						$printUse = "---";
					}
					echo "
					<tr>
						<td>$data[odr]</td>
						<td><img src='data/model/1/$data[photo1]'</td>
						<td>$data[title]</td>
						<td>$data[price]</td>
						<td><select>";
						$colors = explode(',',$data[color]);

						$i = 0;
						while($colors[$i]) {
							echo" <option>$colors[$i]</option>";
							$i++;
						}
						echo"
						<script>
							function confirmDelete(pidx)
							{
								if(confirm('Do you want delete?'))
								{
									location.href='$PHP_SELF?cmd=$cmd&sub=$sub&mode=delete&idx='+pidx;
								}
							}
						</script>
						</select>
						</td>
						<td>
							<input type=button value='Update' onClick=\"location.href='$PHP_SELF?cmd=$cmd&sub=3&idx=$data[idx]'\">
							<input type=button value='Delete'
							onClick=confirmDelete($data[idx])>
						</td>

					</tr>";

					$data = mysql_fetch_array($result);

				}
				echo "</table>";
			} else
			{
				echo "No Data !!<br>";
			}
	}

	//제품 등록
	if($sub == 3)
	{
		// 사진 파일이 등록이 되면
		if($upfile1)
		{
			// 확장자명 검사
			$ext =getFileExt($upfile1_name);
			//jpg파일 확인
			if(isJPG($ext) == true)
			{
				// 사진을 현재시간.확장자 명으로 바꿔줄것이다. 사진 중복을 방지하기위해서
				$photo1 = "$now.$ext";

				for($i=1; $i<=3; $i++)
				{
					$img_info = getImageSize($upfile1);
					$thumb_width = $thumb[$i];
					$thumb_height = (int)(($img_info[1] * $thumb_width ) / $img_info[0]);

					// 도화지를 만든다 길이 만큼
					$dst = ImageCreateTrueColor($thumb_width, $thumb_height);
					// 원본파일 저장
					$src = ImageCreateFromJPEG($upfile1);
					// 0은 이미지 자르는 크기
					ImageCopyResampled($dst, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, ImagesX($src), ImagesY($src));
					// 로딩을 할때 조금씩 가져올거냐 / 한번에 가져올거냐  -> 지금코딩은 서서히 가져오겠다
					ImageInterlace($dst);

					touch("data/model/$i/$photo1");
					chmod("data/model/$i/$photo1", 0777);

					ImageJPEG($dst, "data/model/$i/$photo1",72);

					ImageDestroy($src);
					ImageDestroy($dst);
				}
			} else
			{
				$photo1 = "";
			}
		} else
		{
			$photo1="";
		}

		if($upfile2)
		{
			// 확장자명 검사
			$ext =getFileExt($upfile2_name);
			//jpg파일 확인
			if(isJPG($ext) == true)
			{
				// 사진을 현재시간.확장자 명으로 바꿔줄것이다. 사진 중복을 방지하기위해서
				$photo2 = "$now.$ext";

				for($i=1; $i<=4; $i++)
				{
					$img_info = getImageSize($upfile2);
					$thumb_width = $thumb[$i];
					$thumb_height = (int)(($img_info[1] * $thumb_width ) / $img_info[0]);

					// 도화지를 만든다 길이 만큼
					$dst = ImageCreateTrueColor($thumb_width, $thumb_height);
					// 원본파일 저장
					$src = ImageCreateFromJPEG($upfile2);
					// 0은 이미지 자르는 크기
					ImageCopyResampled($dst, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, ImagesX($src), ImagesY($src));
					// 로딩을 할때 조금씩 가져올거냐 / 한번에 가져올거냐  -> 지금코딩은 서서히 가져오겠다
					ImageInterlace($dst);

					touch("data/model/$i/$photo2");
					chmod("data/model/$i/$photo2", 0777);

					ImageJPEG($dst, "data/model/$i/$photo2",72);

					ImageDestroy($src);
					ImageDestroy($dst);
				}
			} else
			{
				$photo2 = "";
			}
		} else
		{
			$photo2="";
		}


		// 제품목록에서 수정을 누르면 화면에 정보가 보여지게 만들기위해서 등록과 목록을 구분해준다.
		if($idx)
		{
			$sql = "select * from $model_table where idx='$idx'";
			$result = mysql_query($sql);
			$org = mysql_fetch_array($result);

			$btnValue = "Product Update";
			$nextMode = "update&idx=$idx";

			$cat1 = $org[cat1];
			$cat2 = $org[cat2];

		}else
		{
			$btnValue = "Product Registration";
			$nextMode = "insert";
		}

		if($mode == "update")
		{
			/*
			첨부파일 1 있는가 ?
				원본파일이 있으면 지워라.
				썸네일을 만든다.
			없으면
				원본 photo1을 그대로 사용
			업데이트 명령을 디비에 수행
			목록페이지 이동하기
		*/
		$price = str_replace(",","",$price);
		if($upfile1)
		{
			$ext =getFileExt($upfile1_name);
			//jpg파일 확인
			if(isJPG($ext) == true)
			{


				// 사진을 현재시간.확장자 명으로 바꿔줄것이다. 사진 중복을 방지하기위해서
				$photo1 = "$now.$ext";

				for($i=1; $i<=3; $i++)
				{
					//사진 삭제를 위해서 / 에러나가 나도 에러 표시 안나게 하기위한 코드는 @
					@unlink("data/model/$i/$org[photo1]");

					$img_info = getImageSize($upfile1);
					$thumb_width = $thumb[$i];
					$thumb_height = (int)(($img_info[1] * $thumb_width ) / $img_info[0]);

					// 도화지를 만든다 길이 만큼
					$dst = ImageCreateTrueColor($thumb_width, $thumb_height);
					// 원본파일 저장
					$src = ImageCreateFromJPEG($upfile1);
					// 0은 이미지 자르는 크기
					ImageCopyResampled($dst, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, ImagesX($src), ImagesY($src));
					// 로딩을 할때 조금씩 가져올거냐 / 한번에 가져올거냐  -> 지금코딩은 서서히 가져오겠다
					ImageInterlace($dst);

					touch("data/model/$i/$photo1");
					chmod("data/model/$i/$photo1", 0777);

					ImageJPEG($dst, "data/model/$i/$photo1",72);

					ImageDestroy($src);
					ImageDestroy($dst);



				}
			} else
			{
				$photo1 = $org[photo1];
			}
		} else
		{
			$photo1 = $org[photo1];
		}

		if($upfile2)
		{
			// 확장자명 검사
			$ext =getFileExt($upfile2_name);
			//jpg파일 확인
			if(isJPG($ext) == true)
			{

				// 사진을 현재시간.확장자 명으로 바꿔줄것이다. 사진 중복을 방지하기위해서
				$photo2 = "$now.$ext";

				for($i=1; $i<=4; $i++)
				{
					@unlink("data/model/$i/$org[photo2]");
					$img_info = getImageSize($upfile2);
					$thumb_width = $thumb[$i];
					$thumb_height = (int)(($img_info[1] * $thumb_width ) / $img_info[0]);

					// 도화지를 만든다 길이 만큼
					$dst = ImageCreateTrueColor($thumb_width, $thumb_height);
					// 원본파일 저장
					$src = ImageCreateFromJPEG($upfile2);
					// 0은 이미지 자르는 크기
					ImageCopyResampled($dst, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, ImagesX($src), ImagesY($src));
					// 로딩을 할때 조금씩 가져올거냐 / 한번에 가져올거냐  -> 지금코딩은 서서히 가져오겠다
					ImageInterlace($dst);

					touch("data/model/$i/$photo2");
					chmod("data/model/$i/$photo2", 0777);

					ImageJPEG($dst, "data/model/$i/$photo2",72);

					ImageDestroy($src);
					ImageDestroy($dst);

				}
			} else
			{
				$photo2 = $org[photo2];
			}
		} else
		{
			$photo2 = $org[photo2];
		}

			$sql = "SELECT * FROM $model_table WHERE idx=$idx";
			$result = mysql_query($sql);
			$data = mysql_fetch_array($result);

			$sql = "update $model_table set
						cat1='$cat1',cat2='$cat2',
						title='$title', price='$price',color='$color',
						size='$size',origin='$origin',useflag='$useflag',
						photo1='$photo1',photo2='$photo2',odr='$odr', memo='$memo'
						where idx='$idx'
					";
			$result = mysql_query($sql);
			if($result)
				$msg = "Update Success";
			else
				$msg = "Update Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=4&cat1=$cat1&cat2=$cat2';
			</script>
			";
		}

		if($mode == "insert")
		{
			//price = 12,000 콤마 빼주기
			$price = str_replace(",","",$price);
			$sql = "INSERT INTO $model_table VALUES(
						'', '$cat1','$cat2','$title', '$price', '$color',
						'$size','$origin','$maker','$useflag',
						'$odr','$photo1','$photo2','$memo'
					)";
			$result = mysql_query($sql);
			if($result)
				$msg = "Registration Success";
			else
				$msg = "Registration Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$cat1&cat2=$cat2';
			</script>
			";
		}


		echo "
		<script>
		function changeCat1()
		{
			var f = document.modelForm;
			if(f.cat1.value != '0')
			location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1='+f.cat1.value;
		}
		function changeCat2()
		{
			var f = document.modelForm;
			if(f.cat2.value != '0')
			location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$cat1&cat2='+f.cat2.value;
		}
		</script>
		<form name=modelForm method=post enctype='multipart/form-data' action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=$nextMode'>
			<table border=0 width=90%>
			<tr>
				<td colspan=4 class=menuTitle>$btnValue</td>
			</tr>
			<tr>
				<td class=menuLeft>Section</td>
				<td class=menuRight>
					<select name=cat1 class=inputBox onChange=changeCat1()>
						<option value='0'>== Section ==</option>
						";




						$c1sql = "SELECT * FROM $cat1_table ORDER BY odr ASC";
						$c1r = mysql_query($c1sql);
						$c1 = mysql_fetch_array($c1r);


						while($c1)
						{
							if($cat1 == $c1[idx])
							$mark = "selected";
						else
							$mark = "";
							echo "<option value='$c1[idx]' $mark>$c1[title]</option>";
							$c1 = mysql_fetch_array($c1r);
						}

					echo "
					</select>
				</td>
				<td class=menuLeft>Group</td>
				<td class=menuRight>
					<select name=cat2 class=inputBox onChange=changeCat2()>
						<option value='0'>== Group ==</option>
						";



						$c2sql = "SELECT * FROM $cat2_table WHERE cat1='$cat1' ORDER BY odr ASC";
						$c2r = mysql_query($c2sql);
						$c2 = mysql_fetch_array($c2r);


						while($c2)
						{
							if($cat2 == $c2[idx])
							$mark = "selected";
						else
							$mark = "";
							echo "<option value='$c2[idx]' $mark>$c2[title]</option>";
							$c2 = mysql_fetch_array($c2r);
						}

					echo "
					</select>
				</td>
			</tr>
			<tr>
				<td class=menuLeft>Product Name</td>
				<td class=menuRight>
					<input type=text name=title placeholder='Product Name Insert' value='$org[title]' class=inputBox>
				</td>";
				$org[price] = number_format($org[price]);
				echo"
				<td class=menuLeft>Price</td>
				<td class=menuRight>
					<input type=text name=price placeholder='Price Insert' value='$org[price]' class=inputBox>
				</td>
			</tr>

			<tr>
				<td class=menuLeft>Color</td>
				<td class=menuRight>
					<input type=text name=color placeholder='Red,Green' value='$org[color]' class=inputBox>
				</td>
				<td class=menuLeft>Size</td>
				<td class=menuRight>
					<input type=text name=size placeholder='L,M,S' value='$org[size]' class=inputBox>
				</td>
			</tr>

			<tr>
				<td class=menuLeft>origin</td>
				<td class=menuRight>
					<input type=text name=origin placeholder='origin Insert' value='$org[origin]' class=inputBox>
				</td>
				<td class=menuLeft>Brand</td>
				<td class=menuRight>
					<input type=text name=maker placeholder='Brand Insert' value='$org[maker]' class=inputBox>
				</td>
			</tr>";

			$sql = "select max(odr) +1 as newodr from $model_table WHERE cat1='$cat1' and cat2 = '$cat2'";
			$result = mysql_query($sql);
			$info = mysql_fetch_array($result);


			if($org[odr])
			{
				$newodr = $org[odr];
			} else if($info[newodr])
				{
				$newodr = $info[newodr];
				}
			else
				$newodr = 1;

			if($org and $org[useflag] == 0)
				$mark = "selected";
			else
				$mark = "";
			echo "
			<tr>
				<td class=menuLeft>No</td>
				<td class=menuRight>
					<input type=text name=odr placeholder='No' value='$newodr' class=inputBox>
				</td>
				<td class=menuLeft>UseFlag</td>
				<td class=menuRight>
					<select name=useflag class=inputBox>
						<option value='1'>Use</option>
						<option value='0' $mark>------</option>
					</select>
				</td>
			</tr>";

			if($org[photo1])
			{
				$printPhoto1 = "Image<img src='data/model/1/$org[photo1]'>";
			}else
			{
				$printPhoto1 = "Image";
			}

			if($org[photo2])
			{
				// 사진 사이즈 조절하기
				$imgs = getImageSize("data/model/4/$org[photo2]");
				$w = $imgs[0] + 20;
				$h = $imgs[1] + 60;
				// 설명사진이 있다고 표시해주고 싶다 font   /   onClick=\"window.open('파일','윈도우이름','속성정보')
				$printPhoto2 = "<a href='#' onClick=\"window.open('data/model/4/$org[photo2]','MyPhoto','resizable=yes scrollbars=yes width=$w height=$h')\"><font color='#FF0000'>Info Image</font></a>";
			}else
			{
				$printPhoto2 = "Info Image";
			}

			echo "
			<tr>
				<td class=menuLeft>$printPhoto1</td>
				<td colspan=3 class=menuRight>
					<input type=file name=upfile1 class=inputBox>
				</td>
			</tr>

			<tr>
				<td class=menuLeft>$printPhoto2</td>
				<td colspan=3 class=menuRight>
					<input type=file name=upfile2 class=inputBox>
				</td>
			</tr>

			<tr>
				<td class=menuLeft>Info</td>
				<td colspan=3 class=menuRight>
					<textarea name=memo class=inputBox style='height:200px; line-height:200%;'>$org[memo]</textarea>
				</td>
			</tr>

			<tr>
				<td colspan=3 class=menuLeft></td>
				<td class=menuRight>
					<input type=submit class=submit value='$btnValue'>
				</td>
			</tr>
		</table>
		</form><br>";


	}	//sub3   ,   textarea 속성  line-height:200%; 줄간격 200프로

	if($sub ==2)
	{
		if($mode == "insert")
		{
			$sql = "INSERT INTO $cat2_table VALUES(
						'', '$cat1','$title', '$odr', '$useflag'
					)";
			$result = mysql_query($sql);
			if($result)
				$msg = "Insert Success";
			else
				$msg = "Insert Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$cat1';
			</script>
			";
		}

		if($mode == "update")
		{

			//update 하고나서 페이지가 처음화면으로 돌아와 버린다 그러므로 카테코리 원본값을 가져오기 위한 작업
			$sql = "SELECT * FROM $cat2_table WHERE idx='$idx'";
			$result = mysql_query($sql);
			$org = mysql_fetch_array($result);
			$cat1 = $org[cat1];


			$sql = "update $cat2_table set
						title='$title', odr='$odr',
						useflag='$useflag'
						where idx='$idx'
					";
			$result = mysql_query($sql);
			if($result)
				$msg = "Update Success";
			else
				$msg = "Update Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$cat1';
			</script>
			";
		}

		if($mode == "delete")
		{
			//update와 마찬가지
			$sql = "SELECT * FROM $cat2_table WHERE idx='$idx'";
			$result = mysql_query($sql);
			$org = mysql_fetch_array($result);
			$cat1 = $org[cat1];

			$sql = "delete from $cat2_table where idx='$idx'
					";
			$result = mysql_query($sql);
			if($result)
				$msg = "Delete Success";
			else
				$msg = "Delete Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1=$cat1';
			</script>
			";
		}




		echo "
		<script>
			function checkError()
			{
				var f = document.catForm;

				if(f.title.value == '')
				{
					alert('Title Insert Please');
					f.title.focus();
					return false;
				}
				if(f.odr.value == '')
				{
					alert('Odr Insert Please');
					f.odr.focus();
					return false;
				}
			}
			function changeCat()
			{
				var f = document.catForm;
				if(f.cat1.value != '0')
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub&cat1='+f.cat1.value;
			}
		</script>

		<form name=catForm method=post action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=insert' onSubmit=\"return checkError()\">
		<table border=0 width=90%>
			<tr>
				<td class=menuLeft>Group</td>
				<td class=menuRight>
					<select name=cat1 class=inputBox onChange=changeCat()>
						<option value='0'>== Select ==</option>
					";
					$c1sql = "SELECT * FROM $cat1_table ORDER BY odr ASC";
					$c1r = mysql_query($c1sql);
					$c1 = mysql_fetch_array($c1r);

					//mark 값은 셀렉티드만 해놓으면 마지막거가 계속 체크되므로 else 공백도 추가
					while($c1)
					{
						if($cat1 == $c1[idx])
							$mark = "selected";
						else
							$mark = "";
						echo "<option value='$c1[idx]' $mark>$c1[title]</option>";
						$c1 = mysql_fetch_array($c1r);
					}

					echo "
					</select>
				</td>
				<td colspan=2 class=menuLeft></td>

			</tr>";

			// 조건 추가ex) 침대 밑에 싱글, 더블 침대
			$sql = "select max(odr) +1 as newodr from $cat2_table WHERE cat1='$cat1'";
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
					<input type=text name=title placeholder='Title insert' class=inputBox>
				</td>
				<td class=menuLeft>No</td>
				<td class=menuRight>
					<input type=text name=odr placeholder='No' value='$newodr' class=inputBox>
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
				<td class=menuLeft colspan=2>
					<input type=submit class=submit value='Registration'>
				</td>
			</tr>
		</table></form><br><br>
		";

		//디스플레이에 소분류에서 등록한 것들만 보여줌
		$sql = "select * from $cat2_table WHERE cat1='$cat1' order by odr asc";
		$result = mysql_query($sql);
		$data = mysql_fetch_array($result);

		if($data)
		{
			echo"
			<script>
				function confirmDelete(pidx)
				{
					if(confirm('Do you want delete?'))
					{
						location.href='$PHP_SELF?cmd=$cmd&sub=$sub&mode=delete&idx='+pidx;
					}
				}
			</script>
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
					if($data[useflag] == 0)
						$mark = "selected";
					else
						$mark = "";

					echo "
					<form method=post action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=update&idx=$data[idx]'>
					<tr>
						<td class=ud><input type=text name=odr value='$data[odr]'></td>
						<td class=ud><input type=text name=title value='$data[title]'></td>
						<td class=ud>
							<select name=useflag class=inputBox>
								<option value='1'>Use</option>
								<option value='0' $mark>------</option>
							</select>
						</td>
						<td class=ud>
							<input type=submit value='Update'>
							<input type=button value='Delete'
							onClick=confirmDelete($data[idx])>
						</td>
					</tr>
					</form>
					";

					$data = mysql_fetch_array($result);
				}

			echo "
			</table>
			";
		}else
		{
			echo "No Data!!!";
		}

	} //sub2

	if($sub ==1)
	{
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
			$sql = "update $cat1_table set
						title='$title', odr='$odr',
						useflag='$useflag'
						where idx='$idx'
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

		if($mode == "delete")
		{
			$sql = "delete from $cat1_table where idx='$idx'
					";
			$result = mysql_query($sql);
			if($result)
				$msg = "Delete Success";
			else
				$msg = "Delete Fail";

			echo "
			<script>
				alert('$msg');
				location.href='$PHP_SELF?cmd=$cmd&sub=$sub';
			</script>
			";
		}



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
					alert('Odr Insert');
					f.odr.focus();
					return false;
				}
			}
		</script>

		<form name=catForm method=post action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=insert' onSubmit=\"return checkError()\">
		<table border=0 width=90%>
			<tr>
				<td colspan=4 class=menuTitle>대분류</td>
			</tr>";

			$sql = "select max(odr) +1 as newodr from $cat1_table";
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
					<input type=text name=odr placeholder='No' value='$newodr' class=inputBox>
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
				<td class=menuLeft colspan=2>
					<input type=submit class=submit value='Registration'>
				</td>
			</tr>
		</table></form><br><br>
		";

		$sql = "select * from $cat1_table order by odr asc";
		$result = mysql_query($sql);
		$data = mysql_fetch_array($result);

		if($data)
		{
			echo"
			<script>
				function confirmDelete(pidx)
				{
					if(confirm('Do you want delete?'))
					{
						location.href='$PHP_SELF?cmd=$cmd&sub=$sub&mode=delete&idx='+pidx;
					}
				}
			</script>
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
					if($data[useflag] == 0)
						$mark = "selected";
					else
						$mark = "";

					echo "
					<form method=post action='$PHP_SELF?cmd=$cmd&sub=$sub&mode=update&idx=$data[idx]'>
					<tr>
						<td class=ud><input type=text name=odr value='$data[odr]'></td>
						<td class=ud><input type=text name=title value='$data[title]'></td>
						<td class=ud>
							<select name=useflag class=inputBox>
								<option value='1'>Use</option>
								<option value='0' $mark>------</option>
							</select>
						</td>
						<td class=ud>
							<input type=submit value='Update'>
							<input type=button value='Delete'
							onClick=confirmDelete($data[idx])>
						</td>
					</tr>
					</form>
					";

					$data = mysql_fetch_array($result);
				}

			echo "
			</table>
			";
		}else
		{
			echo "No Data!!!";
		}

	}	//sub1






?>