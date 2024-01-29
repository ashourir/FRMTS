<?php

include('connect.php');

global $con;
$stmt = $con->prepare('CALL DeletePartialRegistrants()');
$stmt->execute();
$stmt->close();
