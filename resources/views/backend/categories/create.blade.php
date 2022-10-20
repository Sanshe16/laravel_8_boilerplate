@extends('backend.layouts.master-layout')

@section('styles')
    <style>
        .SumoSelect {
            display: block;
            width: auto;
        }
        .SumoSelect .sumoSelect_search{
            padding: 15px 0px 0px 20px;
            border: 1px solid #CED4DA;
            border-radius: 3px;
        }

    </style>
@endsection

@section('body')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card p-2">
            <div class="d-flex justify-content-between align-items-center px-2">
                <div>
                    <button class="btn btn-secondary m-2 p-2" style=><a href="{{route('admin.categories.index')}}" class="gifter_back_btn"><i class="fa fa-angle-left pr-1" style="font-size:12px"></i>Back</a></button>
                </div>
                <div style="margin-left: -7%;">
                    <h3 class="card-title text-center pt-3">Add Category</h3>
                </div>
                <div></div>
            </div>
            <div class="card-body">
                   {{-- Categories Index --}}
                    <div id="categories" class="">
                        <form class="forms-sample add_category" method="POST">
                            <div class="row">
                                <div class="col col-6">
                                    <div class="form-group">
                                        <label for="name">Category Name<font color="red">*</font></label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{isset($category['name']) ? $category['name'] : ' '}}"/>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col col-6 d-flex align-items-center">
                                    <div class="form-group m-0">
                                        <label for="is_active" class="m-0">Is Active?<font color="red">*</font></label>
                                        <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="categories-parent-div">
                                <div class="col col-6 category-div">
                                    <div class="form-group">
                                        <label for="parent_category">Parent Category</label>
                                        <select class="select form-control input-field sumoSelect_search category ingoreMultipleSelections" data-level="0"  id="parent_category" name="parent_category" data-ignore="1">
                                            <option value="0">Select a category if it's child</option>
                                            @if(isset($categories) && count($categories) > 0)
                                                @foreach($categories as $category)
                                                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('parent_category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-6">
                                    <img class="mb-2" width="170px" id="imagePreview" src="{{ asset('assets/images/placeholder_image.png') }}">
                                    <div class="form-group">
                                        <input id="upload-photo" name="image" type="file" onchange="loadFile(event)" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2 add-button">Create</button>
                            {{-- <button class="btn btn-secondary text-white">Cancel</button> --}}
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
@stack('scripts')
@endsection

@section('scripts')
<script src="{{asset('assets/custom/admin/categories/create_category.js')}}"></script>
<script src="{{asset('assets/custom/admin/categories/delete_category.js')}}"></script>
@endsection
