@extends('backend.layouts.master-layout')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/custom/admin/page/admin_profile_settings.css')}}">
@endsection

@section('body')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card mb-0">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="active px-5 py-3"><a data-toggle="tab" href="#general">General</a></li>
            
                <li class="px-5 py-3"><a data-toggle="tab" href="#business_setting">Business Setting</a></li>
            
                <li class="px-5 py-3"><a data-toggle="tab" href="#bank_account">Bank Account</a></li>
        
                <li class="px-5 py-3"><a data-toggle="tab" href="#">Profile Setting</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body admin_settings_tabs">
                <div class="tab-content border-0">
                   {{-- ADMIN ACCOUNT GENERAL SETTING --}}
                    <div id="general" class="tab-pane fade in active show">
                        <h4 class="card-title">General Setting</h4>

                        <form class="forms-sample general_setting" method="POST">
                            <div class="row">
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="username">Seller ID<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="username" id="username" readonly value="{{isset($user['username']) ? $user['username'] : ' '}}"/>
                                    </div>  
                                </div>
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="email">Email Address<font color="red">*</font></label>
                                        <input type="email" class="form-control" name="email" id="email" readonly value="{{isset($user['email']) ? $user['email'] : ' '}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{isset($user['first_name']) ? $user['first_name'] : ' '}}"/>
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  
                                </div>
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{isset($user['last_name']) ? $user['last_name'] : ' '}}"/>
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="country">Country<font color="red">*</font></label>
                                        <select class="select form-control input-field sumoSelect_search"  id="country" name="country">
                                            @if(isset($countries) && count($countries) > 0)
                                                @foreach($countries as $country)
                                                    <option value="{{$country['id']}}" {{$country['id'] == $user['country_id'] ? 'selected="selected"' :''}}>{{$country['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('country')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  
                                </div>
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="states">State<font color="red">*</font></label>
                                        <select class="select form-control input-field"  id="states" name="states" style="color:black">
                                            @if(isset($states) && count($states) > 0)
                                                @foreach($states as $state)
                                                <option value="{{isset($state['id']) ? $state['id'] : ''}}" {{isset($state['id']) && $state['id'] == $user['state_id'] ? 'selected="selected"' :''}}>{{isset($state['name']) ? $state['name'] : ''}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('states')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="city">City<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="city" id="city" value="{{isset($user['city']) ? $user['city'] : ' '}}"/>
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  
                                </div>
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="phone_number">Phone<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{isset($user['phone_number']) ? $user['phone_number'] : ' '}}"/>
                                        @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-3">
                                    <div class="form-group">
                                        <label for="fax">FAX No. </label>
                                        <input type="text" class="form-control" name="fax" id="fax" value="{{isset($user['fax']) ? $user['fax'] : ' '}}"/>
                                    </div>
                                </div>
                                <div class="col col-3">
                                    <div class="form-group">
                                        <label for="zip_code">Zip Code<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="zip_code" id="zip_code" value="{{isset($user['zip_code']) ? $user['zip_code'] : ' '}}"/>
                                        @error('zip_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="address">Address<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="address" id="address" value="{{isset($user['address']) ? $user['address'] : ' '}}"/>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2 update-button">Update</button>
                            {{-- <button class="btn btn-secondary">Cancel</button> --}}
                        </form>
                    </div>

                {{-- ADMIN ACCOUNT BUSINESS SETTING --}}
                    <div id="business_setting" class="tab-pane fade">
                        <h4 class="card-title">Business Setting</h4>
                        @include('backend.settings.business_setting')
                    </div>

                {{-- ADMIN BANK ACCOUNT SETTING --}}
                    <div id="bank_account" class="tab-pane fade">
                        <h4 class="card-title">Bank Account</h4>
                        @include('backend.settings.bank_account_setting')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{asset('assets/custom/admin/page/admin_profile_settings.js')}}"></script>
@endsection