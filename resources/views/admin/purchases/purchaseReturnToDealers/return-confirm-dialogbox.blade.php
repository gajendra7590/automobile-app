<form action="{{ isset($action) ? $action : '' }}" class="ajaxFormSubmit" data-redirect=""
    method="POST" enctype="multipart/form-data">
    @csrf
    <div class="box-body" data-select2-id="15">
        <div class="row">
            <div class="form-group col-md-12">
                <label>PURCHASE RETURN NOTE</label>
                <textarea name="purchase_return_note" class="form-control"></textarea>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="id" value="{{ isset($data['id'])?$data['id']:0 }}" />
                <button class="btn btn-primary pull-right" type="submit">CREATE PURCHASE RETURN</button>
            </div>
        </div>
    </div>
</form>
