<?php
//ToDo: move to separate file(s) (/upload/index.php & /table_selector/index.php & /table/index.php)
include_once __DIR__ . DIRECTORY_SEPARATOR . "includes.php";

if (isset($_REQUEST) && isset($_REQUEST['page']))
{
	switch ($_REQUEST['page'])
	{
		case "load":
			include_once(__DIR__ . DIRECTORY_SEPARATOR . "load" . DIRECTORY_SEPARATOR . "index.php");
			break;
		case "upload":
		default:
			include_once(__DIR__ . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . "index.php");
			break;
	}
}


?>
