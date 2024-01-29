<?php

/**
 * Description of Page
 *
 * @author cormi
 */
class Page {
   private $pageId;
   private $status;
   private $pageNumber;
   private $notes;
   private $textFilePath;
   
   //methods go here:
    
//get the text file and not file for the selected document:
public static function getPage(int $docId, int $pageNum)
  {
    global $con;
    $stmt = $con->prepare("CALL GetPage(?,?)");
    $stmt->bind_param('ss',
        $docId,
        $pageNum
    );
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
      return "invalid document Page";
    } else {
      //list($id, $hash) = $result->fetch_row();
     
        //$pages = new Page();
        $pages = $result;
        
//        $document->docId = $docId;
//        $document->docName = $username;
        return $pages; 
    }//end else 
  }//end getPage    
    
    //getters, setters, constructor
    public function getPageId() {
        return $this->pageId;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function getTextFilePath() {
        return $this->textFilePath;
    }

    public function setPageId($pageId): void {
        $this->pageId = $pageId;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

    public function setPageNumber($pageNumber): void {
        $this->pageNumber = $pageNumber;
    }

    public function setNotes($notes): void {
        $this->notes = $notes;
    }

    public function setTextFilePath($textFilePath): void {
        $this->textFilePath = $textFilePath;
    }

    public function __construct($pageId, $status, $pageNumber, $notes, $textFilePath) {
        $this->pageId = $pageId;
        $this->status = $status;
        $this->pageNumber = $pageNumber;
        $this->notes = $notes;
        $this->textFilePath = $textFilePath;
    }

}
