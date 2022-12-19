<div class="modal fade" id="ajaxModalDialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <form action='' id='ajaxModalDialogForm' method='POST' class="ajaxFormSubmit" data-redirect="ajaxModalCommon">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title ajaxModalDialogTitle">Delete Confirmation!</h4>
                </div>
                <div class="modal-body ajaxModalDialogBody">
                    Are you sure you want to delete?
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-success" id="delete">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
