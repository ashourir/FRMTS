<?php

/**
 * Description of Collection
 *
 * @author cormi
 */
class Collection
{
  private $collectionId;
  private $name;
  private $timePeriod;
  private $description;


  //methods go here:
  //

  //NATHALIE - grab all collections to display in the create collection tab
  public static function GetCollectionsDisplay()
  {
    global $con;
    $stmt = $con->prepare("CALL GetAllCollectionsFullDetail()");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
      $coll = new Collection($row["collectionId"], $row["name"], $row["timePeriod"], $row["description"]);
      if ($row != null) {
        echo '<li>' . $coll->name . ' (' . $coll->timePeriod . ')' . '</option>';
      } //end if not null
    } //end while
  }

  //NATHALIE - grab and populate dropdown list with collections
  public static function GetCollectionsDropDown()
  {
    global $con;
    $stmt = $con->prepare("CALL GetAllCollections()");
    $stmt->execute();
    $result = $stmt->get_result();
    $options = "";
    while ($row = mysqli_fetch_assoc($result)) {
      $coll = new Collection($row["collectionId"], $row["name"], null, null);
      if ($row) {
        $options .= "<option value='$coll->collectionId'>$coll->name</option>";
      } //end if not null
    } //end while
    return $options;
  }

  //NATHALIE - insert record to database 
  public static function CreateCollection($name, $timePeriod, $collectionDesc)
  {
    global $con;
    $id = 0;
    $stmt = $con->prepare("CALL CreateCollection(?,?,?,?)");
    $stmt->bind_param(
      'ssss',
      $name,
      $timePeriod,
      $collectionDesc,
      $id
    );
    $stmt->execute();
    $stmt->close();
    $stmt2 = $con->prepare("SELECT LAST_INSERT_ID()");
    $stmt2->execute();
    $stmt2->bind_result($id);
    $stmt2->fetch();
    $stmt2->close();

    echo "id: " . $id . "<BR>";
    //return $stmt->affected_rows == 1;
    return $id;
  }  //end of CreateCollection

  //NATHALIE - Update collection record from id
  public static function UpdateCollection(Collection $coll)
  {
    global $con;
    $id = $coll->collectionId;
    $newName = $coll->name;
    $newTimePeriod = $coll->timePeriod;
    $newDesc = $coll->description;
    $stmt = $con->prepare("CALL UpdateCollection(?,?,?,?)");
    $stmt->bind_param(
      'isss',
      $id,
      $newName,
      $newTimePeriod,
      $newDesc
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }


  public static function InsertCollDocMap($collId, $docId)
  {
    global $con;
    $stmt = $con->prepare("CALL InsertCollDocMap(?,?)");
    $stmt->bind_param(
      'ii',
      $collId,
      $docId
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }


  //getters, setters, constructor
  public function __get($name)
  {
    if (property_exists($this, $name)) {
      return $this->$name;
    }
    return null;
  }

  public function __set($name, $value)
  {
    if (property_exists($this, $name)) {
      $this->$name = $value;
    }
  }

  public function __construct($collectionId, $name, $timePeriod, $description)
  {
    $this->collectionId = $collectionId;
    $this->name = $name;
    $this->timePeriod = $timePeriod;
    $this->description = $description;
  }
}
