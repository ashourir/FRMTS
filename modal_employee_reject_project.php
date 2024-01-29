<?php
echo <<<_MODAL
    <div class="modal employeeConfirmReject" id="employee_modal_confirm_reject" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmation to Proceed</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h3>Before we continue.</h3>
            <h4>What happens when clicking on this button and confirming this action?</h4>
            <p>1. The entire document will be sent back to the pool of documents to be transcribed by volunteers.</p>
            <p>2. The entire transcribed text will be deleted permanently.</p>
            <p>3. A volunteer will be able to select this document to start the transcription process.</p>
            <p>4. You will no longer have access to this document.</p>
            <p>5. You will be redirected to your employee page so that you can select a new document to approve.</p>
            <br />
            <br />
            <h3>Do you want to continue?</h3>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="employeeBtnRejectCancel" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="employeeBtnRejectConfirm">Confirm</button>
          </div>
        </div>
      </div>
    </div>
_MODAL;
