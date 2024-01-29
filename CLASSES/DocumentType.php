<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of DocumentType
 *
 * @author cormi
 */
class DocumentType {
    private $typeId;
    private $category;
    private $description;
    
    
    
    //methods go here:
    //NATHALIE - This adds a new document type record
public static function InsertType(DocumentType $docType) {
    global $con;
    $typeCat = $docType->category;
    $typeDesc = $docType->description;
    $stmt = $con->prepare("CALL AddDocType(?,?)");
    $stmt->bind_param(
      'ss',
      $typeCat,
      $typeDesc
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
}    

//NATHALIE - This updates a doc type record by id
public static function UpdateType (DocumentType $docType) {
    global $con;
    $id = $docType->typeId;
    $typeCat = $docType->category;
    $typeDesc = $docType->description;
    $stmt = $con->prepare("CALL UpdateDocType(?,?,?)");
    $stmt->bind_param(
      'iss',
      $id,
      $typeCat,
      $typeDesc
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
}

//NATHALIE - This displays all the document types as list items
public static function GetTypesDisplay() {
    global $con;
    $stmt = $con->prepare("CALL GetAllDocumentTypes()");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        $docType = new DocumentType($row["typeId"], $row["category"], $row["description"]); 
        if ($row != null) {
            if ($docType->description != null){
               echo '<li>' . $docType->category . ' (' . $docType->description . ')</option>';               
            }
            else {
                echo '<li>' . $docType->category . '</option>';
            }
                            
        }//end if not null
    }//end while
}

//NATHALIE - This displays all the document types as dropdown options
public static function GetTypesDropDown(){
    global $con;
    $stmt = $con->prepare("CALL GetAllDocumentTypes()");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        $docType = new DocumentType($row["typeId"], $row["category"], $row["description"]); 
        if ($row != null) {
            if ($docType->description != null){
               echo "<option value='$docType->typeId'>$docType->category($docType->description)</option>";               
            }
            else {
                echo "<option value='$docType->typeId'>$docType->category</option>"; 
            }
                            
        }//end if not null
    }//end while
  }

//JEFFERY
//Get the document type from the document_type table by the document type ID number
public static function GetDocumentTypeDataById(int $typeId)
{
    global $con;
    $stmt = $con->prepare("CALL GetDocumentTypeDataById(?)");
    $stmt->bind_param("i", $typeId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
  
  //getters, setters and constructor:
  public function getTypeId() {
      return $this->typeId;
  }

  public function getCategory() {
      return $this->category;
  }

  public function getDescription() {
      return $this->description;
  }

  public function setTypeId($typeId): void {
      $this->typeId = $typeId;
  }

  public function setCategory($category): void {
      $this->category = $category;
  }

  public function setDescription($description): void {
      $this->description = $description;
  }

  public function __construct($typeId, $category, $description) {
      $this->typeId = $typeId;
      $this->category = $category;
      $this->description = $description;
  }

}
