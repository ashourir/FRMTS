<?php

echo <<<_MODAL

  <div class="modal fade" id="recovery_modal">
    <div class="modal-dialog modal-dialog-centered " id="recovery_dialog">
      <div class="modal-content">
        <div class="modal-header" id="recovery_header">
          <h2 class="recovery_modal_title">Reset your password</h2>
          <div class="recovery_success">
          <h3>A verification link has been sent to</h3>
          <h2 id="reset_email"></h2>
          </div>
        </div>
        
        <div class="modal-body container-fluid recovery_body">
          <label for="recovery_email">Enter email</label>
          <input type="text" class="form-control" id="recovery_email">

          <label for="recovery_dob">Date of birth</label>
          <input type="date" class="form-control" id="recovery_dob" placeholder="Date of Birth">

          <div class="error" style="display:none">Error.  The DOB supplied is not associated with this email. Please confirm both fields</div>
        </div>

        <div class="modal-footer">
        <button class="btn btn-primary" id="recovery_submit">Submit</button>
      </div>
                
      </div>
    </div>

  </div>


_MODAL;
