<?php
include_once("connect.php");
include_once("./CLASSES/Employee.php");
$username = $_POST["txtUsername"];
$password = password_hash($_POST["txtPassword"], PASSWORD_DEFAULT);
$roles = array();
$reply;

$employeeId = Employee::AddNewEmployee($username, $password);
if ($employeeId == 0){
    $reply = "Nothing added";
}
else{
    if (isset($_POST["chkApprover"])){
        array_push($roles, $_POST["chkApprover"]);
    }
    if (isset($_POST["chkUploader"])){
        array_push($roles, $_POST["chkUploader"]);
    }
    if (isset($_POST["chkAdmin"])){
        array_push($roles, $_POST["chkAdmin"]);
    }
    $reply = Employee::AddEmployeeRoles($employeeId, $roles);
}
header("location:employee.php?addStaffMessage=$reply");