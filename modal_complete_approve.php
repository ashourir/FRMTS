<?php
echo <<<_MODAL
  <div class="modal confirmApproveComplete" id="modal_complete_approve" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation to Proceed</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h3>Before we continue.</h3>
          <h4>What happens when clicking on this button and confirming this action?</h4>
          <p>1. The entire document will be ready to be published.</p>
          <p>2. You will be redirected to your employee page so you can select a new document to approve</p>
          <br />
          <h3>Before Confirm.</h3>
          <p> Make sure you verified all the document pages.</p>
          <br />
          <h3>Do you want to continue?</h3>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="btnCompleteCancel" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="btnCompleteApproveConfirm">Confirm</button>
        </div>
      </div>
    </div>
    </div>
  _MODAL;
