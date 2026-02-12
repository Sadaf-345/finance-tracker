<?php
error_reporting(0);
include_once("sessioncheck.php");
include_once("db_connection.php");

$id = $_REQUEST["id"];
// echo "ajax is working".$id;
mysqli_query($cn,"delete from income where id='$id'");
mysqli_query($cn,"delete from transactions where ref_id='$id'");
?>