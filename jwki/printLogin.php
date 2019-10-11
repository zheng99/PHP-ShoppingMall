<?
	// printLogin.php



	if($_SESSION[$sess_id] )
	{
		echo "<a href='$PHP_SELF?cmd=showCart'>Cart</a> ";
		echo "$_SESSION[$sess_name]"." <input type=button value='Exit' onClick=\"location.href='$PHP_SELF?cmd=logout'\"> ";
	}else
	{
		echo "
		<form name=loginForm method=post action='$PHP_SELF?cmd=login'>
			<input type=text name=id placeholder='ID'>
			<input type=password name=pass placeholder='Password'>
			<input type=submit value='Login'>
			<input type=button value='Sign' onClick=\"location.href='$PHP_SELF?cmd=printJoin'\">
		</form>
		";
	}
?>