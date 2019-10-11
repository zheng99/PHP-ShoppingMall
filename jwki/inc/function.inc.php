<?
	// inc/function.inc.php
	// 사진 파일 등록시 이름이 같을 경우 충돌할수 있으니 방지를 위해서 시간데이터를 줘서 하기 위해 만듬
	function today()
	{
		// 2019-08-28
		return Date('Y-m-d');
	}

	function todayNomark()
	{
		// 20190828
		return Date('Ymd');
	}


	function getNow()
	{
		// 20190828123456

		$tmp = Date('G');  // 시간
		if($tmp<10)
			$value = Date('Ymd0Gis');
		else
			$value = Date('YmdGis');

		return $value;
	}

	function getFileExt($file)
	{
		$file = strtolower($file); // strtoupper
		$file_info = pathinfo($file);
		return $file_info[extension];
	}

	function isJPG($ext)
	{
		if($ext == "jpg" or $ext == "jpeg")
			return true;
		else
			return false;
	}
?>
