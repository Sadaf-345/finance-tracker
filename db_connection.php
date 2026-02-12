<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try
{
    $cn = mysqli_connect("localhost","root","","finance_tracker",3307);
}
catch(mysqli_sql_exception $e)
{
     die("Database connection failed!");
}
?>