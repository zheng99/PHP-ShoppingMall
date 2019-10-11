<?
	// 메인페이지 화면
	session_save_path("sess");
	session_start();

	include "inc/config.inc.php";
	include "inc/db.inc.php";
	include "inc/function.inc.php";

	$now = getNow(); // 현재시간
	$today = today(); // 오늘날짜

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

  <title>Homepage</title>
  <link rel="stylesheet" href="css/style.css">
 </head>
 <body>

 <div id=wrapper>
	<div id=topmostMenu>
		<?
			include "printLogin.php";
		?>
	</div> <!-- topmostMenu  -->

	<div id=header>
		<div id=home>
			Shopping Mall
		</div> <!-- home  -->

		<div id=topMenu>
			
		</div> <!-- topMenu  -->

	
	</div> <!-- header  -->

	<div id=contents>
		<div id=menu>
			<?
				include "menu.php";
			?>
		</div>  <!-- menu  -->
		<div id=display>
			<?
				$x = "1111";
				$auth = md5($x);
				

				if($cmd and file_exists("$cmd.php"))
				{
					include "$cmd.php";
				} else if($cmd and file_exists("admin/$cmd.php"))
				{
					include "admin/$cmd.php";
				}

			?>
		</div>  <!-- display  -->
		<div id=banner>
			Banner
		</div>  <!-- banner  -->
	</div> <!-- contents  -->

	<div id=footer>
		<div id=siteinfo>
			Site Info
		</div> <!-- siteinfo  -->
	</div> <!-- footer  -->

 </div> <!-- wrapper  -->


 </body>
</html>
<?
	mysql_close($conn);
?>