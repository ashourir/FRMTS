<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'frmtranscribe_admin');
define('DB_PASS', '4[&1vf&Oaa)d');
define('DB_NAME', 'frmtranscribe_museumdb');

global $con;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$con) {
  die('Could not connect: ' . mysql_error());
}

function sanitizeString($var)
{
  $var = htmlentities(strip_tags(stripslashes($var)));
  return $var;
}

function sanitizeSQL($var)
{
  global $con;
  $var = $con->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}
