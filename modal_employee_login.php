<?php

echo <<<_MODAL

  <div class="modal fade" id="e_login_modal">
    <div class="modal-dialog modal-dialog-centered " id="e_modal_dialog">
      <div class="modal-content">
        <div class="modal-header" id="elogin_header">
          <h2 class="modal-title">Employee Log In</h2>
        </div>
        

        <div class="modal-body container-fluid dm_body">
          <form>
            <div class="form-floating p-2">
              <input type="text" class="form-control" id="eUsername">
              <label for="eUsername">Username</label>
            </div>
            <div class="form-floating p-2 pword">
              <input type="password" class="form-control" id="ePassword">
              <label for="ePassword">Password</label>
              <span class="input-group-addon toggleVis" id="toggleEPass">
                <i class="bi bi-eye" id="show_epassword" style="display:none"></i>
                <i class="bi bi-eye-slash" id="hide_epassword"></i>
              </span>
            </div>
          </form>
        </div>

        <div class="modal-footer">
        <button class="btn btn-primary" id="elogin">Log In</button>
      </div>
                
      </div>
    </div>

  </div>


_MODAL;
