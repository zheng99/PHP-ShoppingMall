<?
	//checkid.php 아이디 중복조회
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

  <title>아이디 검사</title>
  <link rel="stylesheet" href="css/style.css">
 </head>
 <body>
 <?

	echo"
	<form name=idForm method=post action='$PHP_SELF'>
		<table border=0 width=100%>
			<tr>
				<td class=left>ID</td>
				<td class=right><input type=text name=id class=inputBox value='$id' placeholder='Please enter more than 4 characters'></td>
			</tr>
			<tr>
				<td colspan=2 class=ud>
				<input type=submit value='ID Search' class=submit>
			</tr>
		</table>
	</form>
	";
	if($id and strlen($id)>=4)
	{
		$sql = "select * from $user_table where id='$id'";
		$result = mysql_query($sql);
		$data = mysql_fetch_array($result);

		if($data)
		{
			echo" <font color='#FF0000'><b>$id</b></font> This ID is in use<br>";
		}else
		{
			echo" <font color='#FF0000'><b>$id</b></font>는 사용 가능한 아이디입니다.
			<input type=button value='This ID Use' onClick=\"opener.document.joinForm.id.value='$id';window.close()\"><br>";
		}
	}

 ?>

	<script> //상세창 앞으로 튀어나오기
		self.focus();
	</script>
 </body>
</html>
<?
	mysql_close($conn);
?>