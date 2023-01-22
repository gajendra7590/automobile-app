  <form action="{{ route('customerReturns.store') }}" class="ajaxFormSubmit" data-redirect="ajaxModalCommon" method="POST"
      enctype="multipart/form-data">
      @csrf
      <div class="box-body" data-select2-id="15">
          <div class="row">
              <picture class="text-danger" style="margin: 0px 14px 31px 14px;padding: 2px 9px;">
                  <strong>Important Notice!</strong> Please create customer return carefully, if you do once, then all
                  the sales inventory data will be lost & will never every recover.
                  </p>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label>SELECT SALES INVENTORY</label>
                      <select name="sales_id" class="form-control select2" id="select2Ele"
                          data-placeholder="Select a document section..." style="width: 100%;" data-select2-id="7"
                          tabindex="-1" aria-hidden="true">
                      </select>
                  </div>
              </div>
          </div>
      </div>
      <div class="box-footer">
          <div class="row">
              <div class="col-md-12">
                  <a class="btn btn-danger" href="{{ route('documentUploads.index') }}">BACK</a>
                  <button class="btn btn-primary pull-right" type="submit">CREATE RETURN</button>
              </div>
          </div>
      </div>
      <input type="hidden" id="select2SearchURL" value="{{ route('select2DropdownByType') }}">
  </form>

  <script>
      $(".select2").select2({
          ajax: {
              delay: 1000,
              allowClear: true,
              minimumInputLength: 1,
              dataType: "json",
              url: function() {
                  return $("#select2SearchURL").val();
              },
              beforeSend: function() {

              },
              data: function(params) {
                  var query = {
                      search: params.term,
                      type: 'sales',
                  };
                  return query;
              },
          },
      });
  </script>
