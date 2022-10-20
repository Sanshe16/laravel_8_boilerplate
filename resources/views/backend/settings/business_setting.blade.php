
<form class="forms-sample business_setting" method="POST">
    <div class="row">
        <div class="col col-6">
            <div class="form-group">
                <label for="company_name">Company / Store Name<font color="red">*</font></label>
                <input type="text" class="form-control" name="company_name" id="company_name" value="{{isset($user['company_name']) ? $user['company_name'] : ''}}" />
                @error('company_name')
                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}" style="position: absolute;">
                        {{ $message }}
                    </div>
                @enderror
            </div>  
        </div>
        <div class="col col-6">
            <div class="form-group">
                <label for="company_url">Company URL</label>
                <input type="text" class="form-control" name="company_url" value="{{isset($user['company_url']) ? $user['company_url'] : ''}}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-6">
            <div class="form-group">
                <label for="job_title">Job Title<font color="red">*</font></label>
                <select class="select form-control input-field sumoSelect_search"  id="business_type" name="business_type" required>
                    @if(isset($business_types) && count($business_types) > 0)
                        @foreach($business_types as $business_type)
                            <option value="{{isset($business_type['id']) ? $business_type['id'] : ''}}" {{isset($business_type['id']) && $business_type['id'] == $user['business_type_id'] ? 'selected="selected"' :''}}>{{isset($business_type['title']) ? $business_type['title'] : ''}}</option>
                        @endforeach
                    @endif
                </select>
                @error('business_type')
                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}" style="position: absolute;">
                        {{ $message }}
                    </div>
                @enderror
            </div>  
        </div>
    </div>
    <button type="submit" class="btn btn-primary mr-2 update-button_bussines">Submit</button>
    {{-- <button class="btn btn-light">Cancel</button> --}}
</form>
                   