<?php

if (isset($_POST['coll'], $_POST['count'], $_POST['page'])) {
  $coll = $_POST['coll'];
  $limit = $_POST['count'];
  $currentPage = $_POST['page'];
} else {
  $coll = "all";
  $limit = 25;
  $currentPage = 1;
}

