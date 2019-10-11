<?
	// login.php 로그인페이지

	$pass = md5($pass);
	$sql = "SELECT * FROM user_table
				WHERE
					id='$id' and pass='$pass' ";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	if($data)
	{ // success
		$_SESSION[$sess_id] = $data[id];
		$_SESSION[$sess_name] = $data[name];
		$_SESSION[$sess_level] = $data[level];

		$msg = "$_SESSION[$sess_name] Nice To Meet You";
	}else
	{ // fail
		$msg = "Check your ID, Password";
	}

	echo "
	<script>
		alert('$msg');
		location.href='$PHP_SELF';
	</script>
	";
?>