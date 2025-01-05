<?php
$host = "localhost";
$user = "root";
$password= "";
$dbName = "penjualan";

$connectSQL = mysqli_connect($host , $user , $password , $dbName);

session_start(); 