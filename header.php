<?php
//Site navigation header

$site_url = "http://" . $_SERVER['SERVER_NAME'];

$api_html = "";

$load_classes = "";
$api_classes = "";
$upload_classes = "";

if (isset($_REQUEST) && isset($_REQUEST['page']))
{
	switch ($_REQUEST['page'])
	{
		case "load":
			$load_classes = "active";
			break;
		case "api":
			$api_classes = "active";
			$api_html = '<a id="api" href="$site_url?page=api" class="' . $api_classes . '">API Request</a>';
			break;
		case "upload":
		default:
			$upload_classes = "active";
			break;
	}
}

$html = <<<STRING_ALPHAOMEGA

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.js"></script>
	<div class="navigation">
		<a id="load" href="$site_url?page=load" class="$load_classes">Load a Table to Display</a>
		<a id="upload" href="$site_url?page=upload" class="$upload_classes">Upload a Table to the Database</a>
		$api_html
	</div>
	<br>

STRING_ALPHAOMEGA;

echo $html;