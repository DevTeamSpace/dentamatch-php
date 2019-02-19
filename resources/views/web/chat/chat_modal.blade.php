<div id="ShortListMessageBox" class="modal fade" role="dialog">
  <div class="modal-dialog custom-modal popup-wd522">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Message</h4>
      </div>
      <div class="modal-body ">
        <form>
          <div class="form-group custom-select">
            <textarea id="chatMsg" class="form-control messageBoxTextArea"
                      placeholder="Type your message here"></textarea>
          </div>
          <div class="text-right mr-t-20 mr-b-30">
            <input type="hidden" id="seekerId" value="">
            <button id="sendChat" type="submit" class="btn btn-primary pd-l-30 pd-r-30">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>