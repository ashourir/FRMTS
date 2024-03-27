<?php
include_once("connect.php");

/**
 * Description of Document
 *
 * @author cormi
 */
class Document
{
  private $docId;
  private $docName;
  private $statusId;
  private $folderName;
  private $typeId;
  private $numPages;
  private $description;
  private $textFilePath;

  //methods go here:
  //NATHALIE - Insert document record to database
  public static function InsertDocument(Document $doc)
  {
    global $con;
    $docName = $doc->docName; //I have to put this in a variable outside of my storedproc otherwise I'm getting  'Only variables should be passed by reference' errors even though it's working in my insertDocType method without having to do this.
    $statusId = $doc->statusId;
    $folderName = $doc->folderName;
    $typeId = $doc->typeId;
    $numPages = $doc->numPages;
    $desc = $doc->description;
    $id = 0;
    $stmt = $con->prepare("CALL AddDocument(?,?,?,?,?,?,?)");
    $stmt->bind_param(
      'sssiisi',
      $docName,
      $statusId,
      $folderName,
      $typeId,
      $numPages,
      $desc,
      $id
    );
    $stmt->execute();
    $stmt->close();
    $stmt2 = $con->prepare("SELECT LAST_INSERT_ID()");
    $stmt2->execute();
    $stmt2->bind_result($id);
    $stmt2->fetch();
    $stmt2->close();
    return $id;
  }

  //NATHALIE - Update the folderName field for one record
  public static function UpdateFolderName($docId, $folderName)
  {
    global $con;
    $stmt = $con->prepare("CALL UpdateFolderName(?,?)");
    $stmt->bind_param(
      'ss',
      $docId,
      $folderName
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }


  //NATHALIE - This gets the doc name and id for radiobuttons based on collection (in Upload Transcription tab)
  public static function GetDocumentsRadioBtnSingleCollection($collId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetDocumentsByCollection(?)");
    $stmt->bind_param('i', $collId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
      $docId = $row["documentId"];
      $docName = $row["name"];
      if ($row != null) {
        echo '<tr><td><input type="radio" name="docCheck" value="' . $docId . '" onchange="AddText(' . $docId . ')">' . $docName . '</td></tr>';
      }
    } //end while
  }

  //NATHALIE - This gets the doc name and id for the checkboxes (all the documents in the collection the user selects) in Remove Scanned Document tab. The id is the value of the textbox and the name is what is shown to the user
  public static function GetDocumentsCheckBoxesSingleCollection($collId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetDocumentsByCollection(?)");
    $stmt->bind_param('i', $collId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
      $docId = $row["documentId"];
      $docName = $row["name"];
      if ($row != null) {
        echo '<tr><td><input type="checkbox" id="docCheck[]" name="docCheck[]" value="' . $docId . '">' . $docName . '</td></tr>';
      }
    } //end while
  }

  //NATHALIE - This makes documents unavailable - the closest thing we are willing to do to 'delete' the document record
  public static function MakeUnavailableById($docId)
  {
    global $con;
    $stmt = $con->prepare("CALL MakeDocumentUnavailableById(?)");
    $stmt->bind_param(
      'i',
      $docId
    );
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }

  //int $docId, string $name, string $status, string $filepath, int $typeId, int $numpages


  /**
   *This function will query the database with a document id and return its folder name. 
   *
   * @param int $statusId status id unique identifier
   * @author Rodrigo Castro
   * @return string HTML <button> tag that changes its function according to the document actual status
   */
  public static function getDocumentFolderPathByDocId($docId)
  {
    global $con;
    $stmt = $con->prepare("CALL getDocumentFolderPathByDocId(?)");
    $stmt->bind_param('i', $docId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid id";
    } else {
      list($folderName) = $result->fetch_row();

      return $folderName;
    }
  }
  /**
   * This function will query the database document table to retrieve a
   * document status record that matches the $statusId.
   * Then it will return an dinamic HTML <button> component.
   * 
   *
   * @param int $statusId status id unique identifier
   * @author Rodrigo Castro
   * @return string HTML <button> tag that changes its function according to the document actual status
   */
  public static function GetButtonsByDocumentStatusId($statusId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetDocumentsStatusByStatusId(?)");
    $stmt->bind_param('i', $statusId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid id";
    } else {
      list($statusId, $docStatus) = $result->fetch_row();
      $bgColor = "";
      $textColor = "white";
      switch ($statusId) {
        case '0':
        case '1':
          break;
        case '2':
          echo '
          
              <div class="btn-toolbar">
                <span class="form-label form-control-sm" id="spanSaveMsg"></span>
                <button type="button" class="btn btn-save mb-2" id="btnSave">Save Progress</button>
                <button type="button" class="btn btn-complete mb-2" id="btnCompleteTranscribe"> Mark as Complete (Send to Proofread) </button>
                <button type="button" class="btn btn-drop mb-2" id="btnDrop"> Drop this document</button>
              </div>
          
          ';
          break;
        case '3':
          break;
        case '4':
          echo '
          
              <div class="btn-toolbar">
                <span class="form-label form-control-sm" id="spanSaveMsg"></span>
                <button type="button" class="btn btn-save mb-2" id="btnSave">Save Progress</button>
                <button type="button" class="btn btn-complete mb-2" id="btnCompleteProofread"> Mark as Complete (Send to Aproval) </button>
                <button type="button" class="btn btn-drop mb-2" id="btnDrop"> Drop this document</button>
              </div>
          
          ';
          break;
        case '5':
          break;
        case '6':
          echo '
          
              <div class="btn-toolbar">
                <span class="form-label form-control-sm" id="spanSaveMsg"></span>
                <button type="button" class="btn btn-save mb-2" id="btnSave">Save Progress</button>
                <button type="button" class="btn btn-complete mb-2" id="btnCompleteApprove"> Approve (Ready to publish) </button>
                <button type="button" class="btn btn-Reject mb-2" id="employeeBtnReject"> Reject this document</button>
                <button type="button" class="btn btn-drop mb-2" id="employeeBtnDrop"> Drop this document</button>
              </div>
          
          ';
          break;
        case '7':
          break;
        default:

          break;
      }
    }
  }

  /**
   * This function will query the database document table to retrieve a
   * document status record that matches the $statusId.
   * Then it will return an dinamic HTML <label> tab.
   * 
   * @param int $statusId status id unique identifier
   * @author Rodrigo Castro
   * @return string HTML <label> tag that changes the background color depending on its value
   */
  public static function GetDocumentsStatusByStatusId($statusId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetDocumentsStatusByStatusId(?)");
    $stmt->bind_param('i', $statusId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid id";
    } else {
      list($statusId, $docStatus) = $result->fetch_row();
      $bgColor = "";
      $textColor = "white";
      switch ($statusId) {
        case '0':
          $bgColor = "rgb(255, 68, 68)"; //light red
          // $textColor = "";
          break;
        case '1':
          $bgColor = "rgb(84, 84, 255)"; //light blue
          // $textColor = "";
          break;
        case '2':
          $bgColor = "rgb(255, 255, 111)"; //light yellow
          $textColor = "black";
          break;
        case '3':
          $bgColor = "rgb(107, 107, 107)"; //gray
          // $textColor = "";
          break;
        case '4':
        case '5':
        case '6':
          $bgColor = "rgb(255, 180, 42)"; //light orange
          $textColor = "black";
          break;
        case '7':
          $bgColor = "rgb(103, 255, 103)"; //light green
          $textColor = "black";
          break;
        default:
          $bgColor = "";
          $textColor = "";
          break;
      }

      echo '<label class="form-label form-control-sm" 
            id="statusMessage" style="background-color:' . $bgColor . ';
              color: ' . $textColor . ' "
              
            >
            This document is ' . $docStatus . '</label>';
    }
  }
  /**
   * This function will query the database document table to retrieve a
   * document record that matches the $docId.
   * 
   * @param int $docId Document id unique identifier
   * @author Rodrigo Castro
   * @return Document Document object data set 
   */
  public static function getDocumentById(int $docId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetDocument(?)");
    $stmt->bind_param('s', $docId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      return "invalid document";
    } else {
      list($id, $name, $status, $folder, $type, $pages, $desc, $textFile) = $result->fetch_row();
      $document = new Document();
      $document->docId = $id;
      $document->docName = $name;
      $document->statusId = $status;
      $document->folderName = $folder;
      $document->typeId = $type;
      $document->numPages = $pages;
      $document->description = $desc;
      $document->textFilePath = $textFile;

      return $document;
    } //end else 
  } //end getDocument

  /**
   * This function will query the employee table, get the activeDocId and than build a document object with it.
   * 
   * @param int $empId employee id unique identifier
   * @author Rodrigo Castro
   * @return Document Document object data set 
   */
  public static function getDocumentByEmpId(int $empId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetDocumentByEmpId(?)");
    $stmt->bind_param('i', $empId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid document";
    } else {
      list($id, $name, $status, $folder, $type, $pages, $desc, $textFile) = $result->fetch_row();
      $document = new Document();
      $document->docId = $id;
      $document->docName = $name;
      $document->statusId = $status;
      $document->folderName = $folder;
      $document->typeId = $type;
      $document->numPages = $pages;
      $document->description = $desc;
      $document->textFilePath = $textFile;
      return $document;
    } //end else 
  } //end getDocument

  //JEFFERY
  //Returns all documents whose status is for approvers
  public static function GetAllApproverDocuments()
  {
    global $con;
    $stmt = $con->prepare("CALL GetAllApproverDocuments()");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      return "No documents found";
    } else {
      return $result;
    }
  }

  //JEREMY
  public static function getCountAvailableVolunteerDocuments(int $id)
  {
    global $con;
    $stmt = $con->prepare("CALL GetCountAvailableVolunteerDocuments(?)");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0];
  }


  //JEREMY
  public static function getAvailableVolunteerDocsByColl(int $volunteerId, int $coll, int $offset, int $limit)
  {
    global $con;
    $stmt = $con->prepare("CALL GetAvailableVolunteerDocsByColl(?,?,?,?)");
    $stmt->bind_param("iiii", $volunteerId, $coll, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      return false;
    } else {
      $data = array();
      while (list(
        $docId,
        $statusId,
        $statusName,
        $name,
        $folderName,
        $typeId,
        $numPages,
        $desc,
        $textFilePath,
        $category,
        $typeDesc,
        $collectionName,
        $timePeriod
      ) = $result->fetch_row()) {

        array_push(
          $data,
          [
            "id" => $docId,
            "statusId" => $statusId,
            "statusName" => $statusName,
            "documentName" => $name,
            "folderName" => $folderName,
            "typeId" => $typeId,
            "numPages" => $numPages,
            "documentDescription" => $desc,
            "textFilePath" => $textFilePath,
            "category" => $category,
            "typeDesc" => $typeDesc,
            "collectionName" => $collectionName,
            "timePeriod" => $timePeriod
          ]
        );
      }
      return $data;
    }
  }

  //JEREMY
  public static function getAvailableVolunteerDocuments(int $volunteerId = null, int $offset, int $limit)
  {
    global $con;
    $stmt = $con->prepare("CALL GetAllAvailableDocuments(?,?,?)");
    $stmt->bind_param("iii", $volunteerId, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      return false;
    } else {
      $data = array();
      while (list(
        $docId,
        $statusId,
        $statusName,
        $name,
        $folderName,
        $typeId,
        $numPages,
        $desc,
        $textFilePath,
        $category,
        $typeDesc,
        $collectionName,
        $timePeriod
      ) = $result->fetch_row()) {

        array_push(
          $data,
          [
            "id" => $docId,
            "statusId" => $statusId,
            "statusName" => $statusName,
            "documentName" => $name,
            "folderName" => $folderName,
            "typeId" => $typeId,
            "numPages" => $numPages,
            "documentDescription" => $desc,
            "textFilePath" => $textFilePath,
            "category" => $category,
            "typeDesc" => $typeDesc,
            "collectionName" => $collectionName,
            "timePeriod" => $timePeriod
          ]
        );
      }
      return $data;
    }
  }

  //JEFFERY
  //searches the DB for volunteer documents available to the volunteer that is logged in and filters them by statusId (i.e., if they are in transcribe or proofread step)
  public static function getAvailableVolunteerDocumentsByStatusId(int $volunteerId, int $statusId)
  {
    global $con;
    $stmt = $con->prepare("CALL GetAvailableVolunteerDocsByStatusId(?,?)");
    $stmt->bind_param("ii", $volunteerId, $statusId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      return false;
    } else {
      $data = array();
      while (list(
        $docId,
        $statusId,
        $statusName,
        $name,
        $folderName,
        $typeId,
        $numPages,
        $desc,
        $textFilePath,
        $category,
        $typeDesc,
        $collectionName,
        $timePeriod
      ) = $result->fetch_row()) {

        array_push(
          $data,
          [
            "id" => $docId,
            "statusId" => $statusId,
            "statusName" => $statusName,
            "documentName" => $name,
            "folderName" => $folderName,
            "typeId" => $typeId,
            "numPages" => $numPages,
            "documentDescription" => $desc,
            "textFilePath" => $textFilePath,
            "category" => $category,
            "typeDesc" => $typeDesc,
            "collectionName" => $collectionName,
            "timePeriod" => $timePeriod
          ]
        );
      }
      return $data;
    }
  }

  //JEREMY
  public static function getCountUserDocuments()
  {
    global $con;
    $stmt = $con->prepare("CALL GetCountCompletedDocuments()");
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0];
  }

  //JEREMY
  public static function getUserDocuments(int $offset, int $limit, int $coll = null)
  {
    global $con;
    if ($coll) {
      $stmt = $con->prepare("CALL GetAllCompletedDocumentsByColl(?,?,?)");
      $stmt->bind_param('iii', $coll, $offset, $limit);
    } else {
      $stmt = $con->prepare("CALL GetAllCompletedDocuments(?,?)");
      $stmt->bind_param('ii', $offset, $limit);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      return false;
    } else {
      $data = array();
      while (list(
        $docId,
        $statusId,
        $statusName,
        $name,
        $folderName,
        $typeId,
        $numPages,
        $desc,
        $textFilePath,
        $category,
        $typeDesc,
        $collectionName,
        $timePeriod
      ) = $result->fetch_row()) {

        array_push(
          $data,
          [
            "id" => $docId,
            "statusId" => $statusId,
            "statusName" => $statusName,
            "documentName" => $name,
            "folderName" => $folderName,
            "typeId" => $typeId,
            "numPages" => $numPages,
            "documentDescription" => $desc,
            "textFilePath" => $textFilePath,
            "category" => $category,
            "typeDesc" => $typeDesc,
            "collectionName" => $collectionName,
            "timePeriod" => $timePeriod
          ]
        );
      }
      return $data;
    }
  }

  //JEFFERY
  //Calls the GetAllApproverDocuments() function and returns a formatted string for employee.php's organizaed pages & modal
  //returns the return message from GetAllApproverDocuments() if no documents exist in the Approver section
  public static function GetFormattedApproverDocuments()
  {
    $returnString = "<div class='row'>";
    $results = Document::GetAllApproverDocuments();
    if ($results === "No documents found") {
      return $results;
    } else {
      for ($i = 0; $i < $results->num_rows; $i++) {
        $row = $results->fetch_assoc();
        $filePath = "UPLOADS/" . $row["folderName"] . "/";


        $files = glob($filePath . '*.{jpg,png,jpeg}', GLOB_BRACE);
        $imgPath = $files[0];
        

        $fileId = $row["documentId"];
        $fileType = $row["typeId"];
        $fileName = $row["name"];
        $fileDesc = $row["description"];
        $fileNumPages = $row["numPages"];
        $textFilePath = $row["textFilePath"];
        $imgId = $fileId . "img";
        $typeResults = DocumentType::GetDocumentTypeDataById($fileType);
        $typeRow = $typeResults->fetch_assoc();
        $type = $typeRow["category"];
        $category = $typeRow["description"];
        $returnString .= "<div class='col approver-border'>
                                  <span class='approver-title'>$fileName</span>
                                  <BR><img src='$imgPath' id='$imgId' class='modal-approver img-thumbnail img-fluid w-50 mx-auto d-block'>    
                                  <button type='button' class='approver-button btn btn-primary' data-bs-toggle='modal' data-bs-target='#approverModal' onclick='getDocumentData($fileId)'>View Details</button>
                                  <input type='hidden' id='document$fileId' data-docId='$fileId' data-desc='$fileDesc' data-numPages='$fileNumPages' data-tfp='$textFilePath' data-type='$type' data-category='$category' data-name='$fileName'/>
                          </div>";
        if ($i % 4 == 3) {
          $returnString .= "</div><div class='row'>";
        }
        if ($i === 11) {
          $totalRows = $results->num_rows - 12;
          return $returnString . "</div><BR><div style='display: flex; justify-content: center;'>$totalRows remaining documents</div>";
        }
      }
      return $returnString . "</div>";
    }
  }

  //JEFFERY
  //Calls the getDocumentById function and returns a formatted string for employee.php which contains the details for a thumbnail and details to be passed to the approver modal for the document an Approver account has previously selected.  If it fails, returns “no document found” as a string, however, this should not occur as this function is only called after a check is passed that the logged-in Approver has an active document.
  public static function GetFormattedActiveApproverDocument(int $docId)
  {
    $document = Document::getDocumentById($docId);
    if ($document == null) {
      echo "./IMAGES/logo.jpg";
    }
    $filePath = "UPLOADS/" . $document->folderName . "/";
    $files = glob($filePath . '*.{jpg,png,jpeg}', GLOB_BRACE);
    $imgPath = $files[0];
    $fileId = $document->docId;
    $fileType = $document->typeId;
    $fileName = $document->docName;
    $fileDesc = $document->description;
    $fileNumPages = $document->numPages;
    $textFilePath = $document->textFilePath;
    $imgId = $fileId . "img";
    $typeResults = DocumentType::GetDocumentTypeDataById($fileType);
    $typeRow = $typeResults->fetch_assoc();
    $type = $typeRow["category"];
    $category = $typeRow["description"];
    echo  "<div class='row'>
                          <div class='col approver-border'>
                                  <span class='approver-title'>$fileName</span>
                                  <BR><img src='$imgPath' id='$imgId' class='modal-approver img-thumbnail img-fluid w-50 mx-auto d-block'>    
                                  <button type='button' class='approver-button btn btn-primary' data-bs-toggle='modal' data-bs-target='#approverModal' onclick='getDocumentData($fileId)'>View Details</button>
                                  <input type='hidden' id='document$fileId' data-docId='$fileId' data-desc='$fileDesc' data-numPages='$fileNumPages' data-tfp='$textFilePath' data-type='$type' data-category='$category' data-name='$fileName' data-single='true'/>
                          </div>
                          </div>";
  }


  //JEREMY
  public static function GetTimeRemaining(int $docId)
  {
    global $con;
    $stmt = $con->prepare('CALL GetTaskTimeRemaining(?)');
    $stmt->bind_param('i', $docId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    return $result;
  }



  //JEREMY
  public static function UpdateVolunteerDocument(int $docId, string $status)
  {
    global $con;
    if ($status == "complete") {
      $stmt = $con->prepare('CALL CompleteVolunteerDocument(?, @result)');
    } else {
      $stmt = $con->prepare('CALL ReturnVolunteerDocument(?, @result)');
    }
    $stmt->bind_param('i', $docId);
    $stmt->execute();
    $result = $con->query('SELECT @result as RES');
    $status = $result->fetch_row()[0];
    $stmt->close();
    return $status;
  }

  public static function GetAllAvailableDocumentsEmployeesAsHtmlTable(){
    global $con;
    $stmt = $con->prepare('CALL GetAllAvailableDocumentsEmployees()');
    $stmt->execute();
    $result = $stmt->get_result();

    $html = '<table class="table">';
    $html .= '<thead class="thead-dark"><tr><th>Document ID</th><th>Document</th><th>Employee</th><th>Status</th><th>Action</th></tr></thead>';
    $html .= '<tbody>';
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['documentId'] . '</td>';
        $html .= '<td>' . $row['name'] . '</td>';
        $html .= '<td>' . $row['username'] . '</td>';
        $html .= '<td>' . $row['documentStatus'] . '</td>';
        $html .= '<td><button type="button" onclick="GenerateReassignModal(\'emp\', \'' . $row['employeeId'] . '\', \'' . $row['documentId'] . '\')" class="btn btn-dark">Reassign task</button></td>';        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}

public static function GetAllAvailableDocumentsVolunteersAsHtmlTable(){
  global $con;
  $stmt = $con->prepare('CALL GetAllAvailableDocumentsVolunteers()');
  $stmt->execute();
  $result = $stmt->get_result();

  $html = '<table class="table">';
  $html .= '<thead class="thead-dark"><tr><th>Document ID</th><th>Document</th><th>Volunteer</th><th>Status</th><th>Action</th><th>Remain</th></tr></thead>';
  $html .= '<tbody>';
  while ($row = $result->fetch_assoc()) {
      $html .= '<tr>';
      $html .= '<td id="circletatus'.$row['volunteerId'].'">' . $row['documentId'] . '</td>';
      $html .= '<td>' . $row['name'] . '</td>';
      $html .= '<td>' . $row['email'] . '</td>';
      $html .= '<td>' . $row['documentStatus'] . '</td>';
      $html .= '<td><button type="button" onclick="GenerateReassignModal(\'volunteer\', \'' . $row['volunteerId'] . '\', \'' . $row['documentId'] . '\', \'' . $row['statusId'] . '\')" class="btn btn-dark">Reassign task</button></td>';
      $html .= '<td id="timeRemain'.$row['volunteerId'].'"></td>';
      echo '<script>GetTimeRemaining("'.$row['volunteerId'].'")</script>';
      $html .= '</tr>';
      
    }
  $html .= '</tbody>';
  $html .= '</table>';

  return $html;
}

//Alex
public static function ReassignDocument($prevId, $actualEmpId, $documentId, $mode, $targetRole, $statusId){
  global $con;

  $stmt = $con->prepare("CALL ReassignActiveDocId(?, ?, ?, ?, ?, ?, @result)");
  $stmt->bind_param("iiissi", $prevId, $actualEmpId, $documentId, $mode, $targetRole, $statusId);
  $stmt->execute();
  $stmt->close();
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

  public function __construct()
  {
  }
}
