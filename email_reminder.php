<?php
function getAllProjectsAtMidway()
{
  define('DB_HOST', 'us-cdbr-east-06.cleardb.net');
  define('DB_USER', 'b6d3edeb1d35b2');
  define('DB_PASS', 'dcb5fcbf');
  define('DB_NAME', 'heroku_87fd46069ef71b1');

  global $con;
  $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$con) {
    die('Could not connect: ' . mysql_error());
  }

  $list = array();

  $stmt = $con->prepare('CALL getAllProjectsAtMidway()');
  $stmt->execute();
  $result = $stmt->get_result();
  while (list($email, $projName) = $result->fetch_row()) {
    array_push($list, [$email, $projName]);
  }
  $stmt->close();

  foreach ($list as $recipient) {
    list($email, $projName) = $recipient;
    $msg = "15 days has elapsed.  You only have 15 more days to complete " . $projName;
  }
}

getAllProjectsAtMidway();
