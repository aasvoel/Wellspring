<?php

include_once (__DIR__ . DIRECTORY_SEPARATOR . "CRUD.php");

$fake_db_json_location_session_var = "Wellspring_Fake_DB";

$CRUD_instance = new CRUD;
$CRUD_instance->set_database_location($fake_db_json_location_session_var);