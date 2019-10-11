<?
	// inc/function.inc.php
	// ���� ���� ��Ͻ� �̸��� ���� ��� �浹�Ҽ� ������ ������ ���ؼ� �ð������͸� �༭ �ϱ� ���� ����
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

		$tmp = Date('G');  // �ð�
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
