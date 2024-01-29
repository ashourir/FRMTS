<?php

foreach ($availableDocuments as $doc) {

  $docId = $doc['id'];
  $statusId = $doc['statusId'];
  $statusName = $doc['statusName'];
  $documentName = $doc['documentName'];
  $folderName = $doc['folderName'];
  $typeId = $doc['typeId'];
  $numPages = $doc['numPages'];
  $documentDescription = $doc['documentDescription'];
  $textFilePath = $doc['textFilePath'];
  $category = $doc['category'];
  $typeDesc = $doc['typeDesc'];
  $collectionName = $doc['collectionName'];
  $timePeriod = $doc['timePeriod'];
  $thumbnail_img = "";
  $carousel_img = "";
  $data = array($documentName, $category, $typeDesc, $collectionName, $timePeriod, $statusName);
  $searchable = json_encode($data);


  $images = glob("./UPLOADS/$folderName/*.{jpg,png,gif}", GLOB_BRACE);
  for ($i = 0; $i < count($images); $i++) {
    if (isset($images[$i]) && file_exists($images[$i])) {
      if ($i == 0) {
        $thumbnail_img = '<img class="thumbnail_img" src="' . $images[0] . '"/>';
        $carousel_img .= '<div class="carousel-item active"><img class="carousel_image" src="' . $images[$i] . '" /></div>';
      } else {
        $carousel_img .= '<div class="carousel-item"><img class="carousel_image" src="' . $images[$i] . '"/></div>';
      }
    }
  }


  echo <<<_THUMBNAIL
                   <div class="doc_thumbnail" id="$docId" data-search='$searchable' collection="$collectionName" data-bs-toggle="modal" data-bs-target="#modal_document_$docId">
                      <div class="doc_thumbnail_img"> 
                        $thumbnail_img 
                        <div class="document_state">$statusName</div>
                      </div>
                      <h8><b>$documentName</b></h8>
                      <h9><i>$collectionName Collection</i></h9>
                    </div>
                  _THUMBNAIL;


  echo <<<_MODAL
                 <div class="modal fade" id="modal_document_$docId" tabindex="-1" aria-labelledby="document_label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content modal_document_content">
                      <div class="modal-header">
                        <h5 class="modal-title document_label">Document Overview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="doc_content">
                          <div class="modal_document_image">
                            <div id="modal_document_controls_$docId" class="carousel carousel-dark slide" data-bs-ride="carousel">
                              <div class="carousel-inner">
                                $carousel_img;
                              </div> 
                              <button class="carousel-control-prev" type="button" data-bs-target="#modal_document_controls_$docId" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                              </button>
                              <button class="carousel-control-next" type="button" data-bs-target="#modal_document_controls_$docId" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                              </button>
                            </div>
                          </div>
                          <div class="modal_document_details">
                            <h2>$documentName</h2>
                            <h6>$documentDescription</h6>
                            <h7>$collectionName</h7>
                            <h7>$typeDesc</h7>
                            <h7>$timePeriod</h7>
                            <h7>$category</h7>
                            <h9>$numPages</h9>
                          </div>
                          <div class="modal_document_has_work" style="display:none">
                            <h3>Sorry</h3>
                            <p>It looks like you already have a project started</p>
                          </div>
                        </div>
                                        
                        <div class="transcriber_welcome" style="display:none">
                          <p>Thank you once again for volunteering for this fantastic project! Transcription is the first step
                            of the project. The tips
                            section can help make your transcription experience easier and fun!</p>
                          <br>
                          <p>Finally, the Frequently Asked Questions (FAQ) section can help you with any major questions you may have.</p>
                          <br>
                          <p>You have 30 days to complete your work.  An email will be sent at 15 days to remind you.  Projects will automatically be returned after 30 days</p>
                          
                        </div>
                                          
                        <div class="proofreader_welcome" style="display:none">
                          <p>Thank you once again for volunteering for this fantastic project! Proofreading is the final
                            stretch of the project, and you will help us finish by proofreading the transcribed files. The tips
                            section can help make your proofreading experience easier and fun!</p>
                          <br>
                          <p>Finally, the Frequently Asked Questions (FAQ) section can help you with any major questions you may have.</p>
                          <br>
                          <p>You have 30 days to complete your work.  An email will be sent at 15 days to remind you.  Projects will automatically be returned after 30 days</p>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary modal_choose_doc" doc_id="$docId" status_id="$statusId">Select Document</button>
                        <button type="button" class="btn btn-primary btn_continue" style="display:none">Continue</button>
                      </div>
                    </div>
                  </div>
                </div>
              _MODAL;
}
