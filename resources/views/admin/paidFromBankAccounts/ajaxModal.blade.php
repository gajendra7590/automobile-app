<form class="ajaxFormSubmit" role="form" method="POST" action="{{ isset($action) ? $action : '' }}"
    enctype="multipart/form-data" data-redirect="ajaxModalCommon">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif

    <div class="row">
        <div class="form-group col-md-4">
            <label>BRANCH NAME</label>
            <select name="branch_id" id="branch_id" class="form-control">
                @if (isset($branches))
                    @foreach ($branches as $key => $branch)
                        <option {{ isset($data['branch_id']) && $data['branch_id'] == $branch->id ? 'selected' : '' }}
                            value="{{ $branch->id }}"> {{ $branch->branch_name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <input type="hidden" name="gst_rate_percent" id="gst_rate_percent"
                value="{{ isset($data['gst_rate_percent']) ? $data['gst_rate_percent'] : 0 }}">
        </div>
        <div class="form-group col-md-4">
            <label>BANK NAME</label>
            <input type='text' class="form-control autoCapitalized" placeholder="BANK NAME" name="bank_name"
                value="{{ isset($data) && $data->bank_name ? $data->bank_name : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>BANK ACCOUNT NUMBER</label>
            <input type='text' class="form-control" placeholder="BANK ACCOUNT NUMBER" name="bank_account_number"
                value="{{ isset($data) && $data->bank_account_number ? $data->bank_account_number : '' }}" />
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>BANK ACCOUNT HOLDER</label>
            <input type='text' class="form-control autoCapitalized" placeholder="BANK ACCOUNT HOLDER"
                name="bank_account_holder_name"
                value="{{ isset($data) && $data->bank_account_holder_name ? $data->bank_account_holder_name : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>BANK IFSC CODE</label>
            <input type='text' class="form-control autoCapitalized" placeholder="BANK IFSC CODE"
                name="bank_ifsc_code"
                value="{{ isset($data) && $data->bank_ifsc_code ? $data->bank_ifsc_code : '' }}" />
        </div>
        <div class="form-group col-md-4">
            <label>BANK BRANCH NAME</label>
            <input type='text' class="form-control autoCapitalized" placeholder="BANK BRANCH NAME"
                name="bank_branch_name"
                value="{{ isset($data) && $data->bank_branch_name ? $data->bank_branch_name : '' }}" />
        </div>

    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="ajaxFormSubmit">
                    <?php if(isset($method) && $method == 'PUT'): ?>
                    UPDATE
                    <?php else: ?>
                    SAVE
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(".ajaxFormSubmit").validate({
        rules: {
            bank_name: {
                required: true
            },
            bank_account_number: {
                required: true
                number: true
            },
            bank_account_holder_name: {
                required: true
            },
            bank_ifsc_code: {
                required: true
            },
            bank_branch_name: {
                required: true
            }
        }
    });
</script>
