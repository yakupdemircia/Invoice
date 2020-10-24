<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Invoice Operations</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <h3 class="text-center">Actions</h3>

                <div class="row">
                    <div class="col-md-3">
                        <form class="delete" method="post" action="">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-lg w-100 p-1">Delete</button>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-lg w-100 p-1 pay_all">Paid</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning btn-lg w-100 p-1 pay_partial" data-toggle="modal" data-target="#modal_pay_partial">P. Paid</button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-danger btn-lg w-100 p-1 delayed">Delayed</button>
                    </div>
                </div>

                <h3 class="text-center mt-4">Send Mail</h3>

                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Client:</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Title:</label>
                        <input class="form-control" id="message_title" value="Payment Reminder">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text">Dear {client_name}, your invoice dated at {invoice_date} with the amount of {amount} is not paid. This is an automated message. Please do not reply.</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary send_mail">Send Email</button>
                <button type="button" class="btn btn-warning disabled">Send Sms</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_pay_partial" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="modal_pay_partial">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h3>Partial Payment</h3>
                <p>Please input payment amount</p>
                <input type="number" id="to_be_paid" data-tabindex="1">
                <button class="btn btn-primary pay" data-toggle="modal">Save Payment</button>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Close</button>
            </div>
        </div>
    </div>
</div>
