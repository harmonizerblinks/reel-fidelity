 <!-- CONTACT FORM DIALOG -->
 <div class="modal fade in" id="modal-email">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    <i class="icon icon-envelope"></i> Send Email
                </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal validate contact" method="post" action="src/AjaxAction.class.php?action=send_site_comment">
                    <div class="form-group">

                        <label class="col-md-3 control-label">
                            To:
                        </label>

                        <div class="col-md-6">
                            <p class="form-control"><em>Customer Care</em></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            Full name:
                        </label>
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            Email:
                        </label>
                        <div class="col-md-6">
                            <input type="text" name="email" class="form-control required email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            Telephone:
                        </label>
                        <div class="col-md-6">
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            Subject:
                        </label>
                        <div class="col-md-6">
                            <input type="text" name="subject" class="form-control required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">
                            Message:
                        </label>
                        <div class="col-md-6">
                            <textarea name="message" class="form-control required" rows="6"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" id="submit" class="btn btn-success">
                    <span>Send</span> <i class="icon icon-share-alt"></i>
                </button>
            </div>
        </div>
    </div>
    <div id="virtualKeyboard"></div>
</div>
<div id="thanks"></div>
<!-- END CONTACT FORM DIALOG -->