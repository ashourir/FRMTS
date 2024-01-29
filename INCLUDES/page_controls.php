<?php
echo <<<_EOF

  <div class="row__search_bar" data-coll="$coll" data-current_page="$currentPage" data-limit="$limit">
    <div class="searchbar">
      <input type="search" class="search_collection" placeholder="Search Collections..." maxlength="100" />
      <img src="./IMAGES/ICONS/search.svg" class="search_icon" alt="search" />
      <div class="view_controls">
        <label for="viewCount">View Count</label>
        <select id="viewCount">
  _EOF;

$optionValues = [25, 50, 100];
$options = "";
foreach ($optionValues as $value) {
  if ($value == $limit) {
    $options .= "<option value='$value' selected>$value</option>";
  } else {
    $options .= "<option value='$value'>$value</option>";
  }
}
echo "$options";


echo <<<_EOF
        </select>
        <button class="update_filter">GO</button>
        <label for="collections_list">Collection</label>
        <select id="collections_list">
          <option value="all">View All</option>
  _EOF;


echo str_replace("value='$coll'", "value='$coll' selected", $collectionList);


echo <<<_EOF
        </select>
        <button class="update_filter">GO</button>
      </div>
    </div>
    <div class="page_controls" total=$totalPages>
  _EOF;


if ($availableDocuments) {
  echo "<div class='page first_pg' page='first_pg'><img class='icon' src='./IMAGES/ICONS/first_page.svg'/> First Page</div>";
  echo "<div class='page prev_pg' page='prev_pg'><img class='icon' src='./IMAGES/ICONS/arrow_back.svg'/> Previous</div>";
  $pages_cont = "<div class='pages_cont'>...</div>";
  $pages = "";
  if ($currentPage - 3 > 0) {
    $pages .= $pages_cont;
  }
  for ($i = 1; $i <= $totalPages; $i++) {
    $id = $i . "_pg";
    if (abs($i - $currentPage) < 3) {
      if ($i == $currentPage) {
        $pages .= "<div class='page' page='$id'><span id='current_page'>$i</span></div>";
      } else {
        $pages .= "<div class='page' page='$id'>$i</div>";
      }
    }
  }
  if ($currentPage + 2 < $totalPages) {
    $pages .= $pages_cont;
  }
  echo "$pages";
  echo "<div class='page next_pg' page='next_pg'>Next <img class='icon' src='./IMAGES/ICONS/arrow_forward.svg'/></div>";
  echo "<div class='page last_pg' page='last_pg' num=$totalPages>Last Page <img class='icon' src='./IMAGES/ICONS/last_page.svg'/></div>";
}


echo <<<_EOF
    </div>
  </div>
  _EOF;
