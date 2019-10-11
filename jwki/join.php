<?

	//join.php
	//비밀번호 다시 한번 확인 틀리면 종료
	if($pass != $pass2)
	{
		exit();
	}
	if(strlen($id) < 4)
	{
		exit();
	}
	//패스워드 암호화
	$pass = md5($pass);

	$sql = "INSERT INTO $user_table VALUES ('','$id','$name',
			'$pass','1','$mobile','$zip','$addr1','$addr2'
	)";

	$result = mysql_query($sql);

	if($result)
		$msg = "Sign Success";
	else
		$msg = "Sign Error";

	echo"
	<script>
		alert('$msg')
		location.href='$PHP_SELF';
	</script>
	";
?>