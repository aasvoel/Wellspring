<?php

$CRUD_instance->open_database();
$TableCreator_instance = new TableCreator;
$table_array = [];
$add_datatables_activation = true;
$order_by = null;
	
if (isset($_REQUEST) && isset($_REQUEST['table_id']))
{
	$database_table = $CRUD_instance->read($_REQUEST['table_id']);
	
	$first_row_are_headers = true;
	$table_headers = null;
	$table_array = array_unique($database_table,SORT_REGULAR);
	
	//per requirements, must be sorted by RUN_NUMBER
	if (in_array("RUN_NUMBER",$table_array[0]))
	{
		$order_by = array_search("RUN_NUMBER",$table_array[0]);
	}
}
else
{
	$db_keys = $CRUD_instance->get_database_table_keys();

	if (isset($db_keys) && !empty($db_keys))
	{
		foreach ($db_keys as &$key)
		{
			$key = "<a href='$site_url?page=load&table_id=$key'>Table ID: $key</a>";
		}
	}
	else
	{
		$db_keys = ["No tables found in database."];
	}
	
	$first_row_are_headers = false;
	$table_headers = ["Database Tables"];
	$table_array = [$db_keys];
}

$table = $TableCreator_instance->create_table($table_array,$add_datatables_activation,$first_row_are_headers,$table_headers,$order_by);

$html = <<<STRING_ALPHAOMEGA
$table

STRING_ALPHAOMEGA;

echo $html;