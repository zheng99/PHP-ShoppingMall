<?
	//로그아웃 페이지
	echo "logout.php<br>";

	session_destroy();

	echo "
	<script>
		alert('Good Bye');
		location.href='$PHP_SELF';
	</script>
	";
?>