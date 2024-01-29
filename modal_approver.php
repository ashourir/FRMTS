<div class='modal fade' id='approverModal' tabindex='-1' aria-labelledby='document_label' aria-hidden='true'>
  <div class='modal-dialog modal-l modal-dialog-centered'>
    <div class='modal-content' id='modal_document_content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='document_label'>Document Overview</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body' id='modal_document_body'>
        <div id='modal_document_image' class='modal_document_image'></div>
        <div class='modal_document_details'>
          <h6 id='document_description'></h6><BR>
          <h7 id='document_type'></h7><BR>
          <h7 id='document_category'></h7><BR>
          <h9 id='document_page_count'></h9><BR><BR>
          <h6 id='document_transcription'></h6>
        </div>
      </div>
      <div class='modal-footer'>
        <input type='button' value='Begin Approval' name='modal_choose_doc' id='modal_choose_doc' class='btn btn-primary'>
        <input type='hidden' name='hidden_id' id='hidden_id' value=''>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
      </div>
    </div>
  </div>
</div>
