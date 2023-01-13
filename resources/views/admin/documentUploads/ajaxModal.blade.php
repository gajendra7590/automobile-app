 <form role="form" method="POST" class="ajaxFormSubmit" action="{{ isset($action) ? $action : '' }}"
     enctype="multipart/form-data" data-redirect="ajaxModalCommon">
     @csrf
     @if (isset($method) && $method == 'PUT')
         @method('PUT')
     @endif
     <div class="box-body">
         <div class="row">
             <div class="form-group col-md-12">
                 <label>Document Section Type</label>
                 <select class="form-control" name="active_status">
                     <option value="">--SELECT DOCUMENT TYPE--</option>
                     @if (isset($documentTypes) && count($documentTypes) > 0)
                         @foreach ($documentTypes as $documentType)
                             <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                         @endforeach
                     @endif
                 </select>
             </div>
             <div class="form-group col-md-12">
                 <label>Minimal</label>
                 <select class="form-control select2" style="width: 100%;">
                     <option selected="selected">Alabama</option>
                     <option>Alaska</option>
                     <option>California</option>
                     <option>Delaware</option>
                     <option>Tennessee</option>
                     <option>Texas</option>
                     <option>Washington</option>
                 </select>
             </div>
         </div>
     </div>
     <!-- /.box-body -->
     <div class="box-footer">
         <div class="row">
             <div class="col-md-12">
                 <input name="country_id" type="hidden" value="1">
                 <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                     @if (isset($method) && $method == 'PUT')
                         UPDATE
                     @else
                         SAVE
                     @endif
                 </button>
             </div>
         </div>
     </div>
 </form>
