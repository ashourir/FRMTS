<?php
include("connect.php");
include("./CLASSES/Employee.php");
session_start();
$empId = $_SESSION['employee'];
$password = $_REQUEST["q"];
$success = Employee::CheckEmployeePassword($empId, $password);
echo $success;
