<?php
include_once (__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "includes.php");

$api = "/api/index.php";

$html = <<<STRING_ALPHAOMEGA

<form action="$api?page=api" method="post" enctype="multipart/form-data">
  Upload CSV file to the database (which can then be loaded into and modified from a sortable table):
  <br>
  <br>
  <input type="text" value="Upload" name="api_class" hidden>
  <input type="text" value="upload_csv" name="api_class_method" hidden>
  <input type="file" name="uploaded_csv" accept=".csv">
  <input type="submit" value="Upload" name="submit">
</form>

STRING_ALPHAOMEGA;

echo $html;