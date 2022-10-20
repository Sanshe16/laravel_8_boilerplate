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
    <div class="col-md-12">
        <form class="forms-sample update_products" method="POST" data-id="{{ $product->id }}">
            <div class="card p-2">
                <div class="d-flex justify-content-between align-items-center px-2">
                    <div>
                        <button class="btn btn-secondary m-2 p-2" style=><a href="{{route('admin.products.index')}}" class="gifter_back_btn"><i class="fa fa-angle-left pr-1" style="font-size:12px"></i>Back</a></button>
                    </div>
                    <div style="margin-left: -7%;">
                        <h3 class="card-title text-center pt-3">Edit Product</h3>
                    </div>
                    <div></div>
                </div>
                <div class="card-body">
                    <div id="categories" class="">
                        <h5 class="card-title">Basic</h5>
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label for="product_name">Product Name<font color="red">*</font></label>
                                    <input type="text" class="form-control text_typed_value_count" name="product_name" id="product_name" value="{{isset($product['name']) ? $product['name'] : ' '}}" maxlength="255"/>
                                    <div class="text_value_count"><span class="text_count">0</span> /255</div>
                                    @error('product_name')
                                        <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col col-1 d-flex align-items-center"></div>
                            <div class="col col-5 d-flex align-items-center">
                                <div class="form-group m-0">
                                    <label for="is_active" class="m-0">Is Active?<font color="red">*</font></label>
                                    <input type="checkbox" id="is_active" name="is_active"  value="1" {{isset($product['is_active']) && $product['is_active'] == 1 ? 'checked' : ''}}>
                                </div>
                            </div>
                        </div>
                        <div id="selected-categories-add-div">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Selected Categories</h5>
                                <a id="add_another_category_btn" class="btn btn-primary mr-2" href="javascript:void(0);" style="display: {{isset($product['categories']) && $product['categories']->count() > 0 ? 'block' : 'none'}}">Add Category</a>
                            </div>

                            @if(isset($product['categories']) && $product['categories']->count() > 0)
                                <div id="selected-categories-text-div" style="display: {{isset($product['categories']) && $product['categories']->count() > 0 ? 'none' : 'block'}}">
                                    <p>No category is selected</p>
                                </div>
                            @endif

                            <div class="my-2 w-100 d-flex gifter_scrollbar" id="categories-add-div" style="height: 50px">
                                @foreach($product['categories'] as $category)
                                    <div class="mx-1 selected_category_tag d-flex justify-content-between" data-id="{{$category['id']}}" data-level="{{$category['level']}}" data-parent="{{$category['parent_id']}}" data-index="0" data-text="{{$category['name']}}"  style="max-width: 150px; height: 33px; background-color: #4B49AC; border-radius: 10px; color: white; padding: 5px 5px 5px 5px; "><p class="text-center text-truncate" style="max-width: 120px;" data-toggle="tooltip" title="{{$category['name']}}">{{$category['name']}}</p><i class="ml-1 ti-close remove_selected_category" style="color: white; cursor: pointer; font-size: 10px; "></i></div>
                                @endforeach
                            </div>
                            
                        </div>
                        <div class="row" id="categories-parent-div" style="display: {{isset($product['categories']) && $product['categories']->count() > 0 ? 'none' : 'block'}}">
                            @php
                                $categories_ids = $product['categories']->pluck('id')->toArray();
                            @endphp
                            <div class="col col-6 category-div">
                                <div class="form-group">
                                    <label for="parent_category">Parent Category</label>
                                    <select class="select form-control input-field sumoSelect_search category" data-level="0"  id="parent_category" name="parent_category">
                                        <option value="0">Select a category if it's child</option>
                                        @if(isset($categories) && count($categories) > 0)
                                            @foreach($categories as $category)
                                                <option value="{{$category['id']}}" {{in_array($category['id'], $categories_ids) ? 'disabled' : ''}}>{{$category['name']}}</option>
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
                    </div>
                </div>
            </div>
            <br>
            <div class="card p-2">
                <div class="card-body">
                    <h3 class="card-title">Product Details</h3>
                    <div class="row">
                        <div class="col col-12">
                            <div class="form-group">
                                <label for="details">Description<font color="red">*</font></label>
                                <textarea type="text" placeholder="Product details..." class="form-control" name="details" rows="5" id="description" value="{{ $product['details']  }}">{!! $product['details'] !!}</textarea>
                                @error('details')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group uploads_images">
                                <label><strong> Product Image</strong><font color="red">*</font> </label> <i class="dripicons-question" data-toggle="tooltip" title="{{trans('file.You can upload multiple image. Only .jpeg, .jpg, .png, .gif file can be uploaded. First image will be base image.')}}"></i>
                                <p style="color:lightslategray">Drag and drop pictures below to upload. Add at least 1 images of your product from different angles. Size between 500x500 and 2000x2000 px. Obscene image is strictly prohibited.</p>
                                <div id="imageUpload" class="dropzone"></div>
                                <span class="validation-msg text-danger" id="image-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Image</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->media as $image)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                @php
                                                    getThumbnailAndOriginalImage($image, 'product_image');
                                                @endphp
                                                <a href="{{url($image->original_image)}}" target="_blank"><img src="{{url($image->thumb_image)}}" height="60" width="60"></a>
                                                <input type="hidden" name="prev_img[]" id="prevImg" value="{{$image->id}}">
                                            </td>
                                            <td><button type="button" class="btn btn-sm remove-img"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-6">
                            <div class="form-group m-0">
                                <label for="product_box">What is in the box?<font color="red">*</font></label>
                                <input type="text" class="form-control text_typed_value_count" name="product_box" id="product_box" value="{{isset($product['product_box']) ? $product['product_box'] : ' '}}" maxlength="255"/>
                                <div class="text_value_count"><span class="text_count">0</span> /255</div>
                                @error('product_box')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="form-group m-0">
                                <label for="sku">SKU / UPC<font color="red">*</font></label>
                                <input type="text" class="form-control" name="sku" id="sku" value="{{isset($product['sku']) ? $product['sku'] : ' '}}" />
                                @error('product_box')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title">Pricing & Stock</h5>
                    <div class="row">
                        <div class="col col-6">
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price<font color="red">*</font>  ($)</label>
                                <input type="number" class="form-control" name="purchase_price" id="purchase_price" min="0" value="{{isset($product['purchase_price']) ? $product['purchase_price'] : ' '}}"/>
                                @error('purchase_price')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="form-group">
                                <label for="product_price">Selling Price<font color="red">*</font>  ($)</label>
                                <input type="text" class="form-control gifter_js_validation" name="product_price" id="product_price" min="0" value="{{isset($product['price']) ? $product['price'] : ' '}}"/>
                                @error('product_price')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-6">
                            <div class="form-group">
                                <label for="quantity">Quantity<font color="red">*</font></label>
                                <input type="number" class="form-control gifter_js_validation" name="quantity" id="quantity" min="0" value="{{isset($product['quantity']) ? $product['quantity'] : ' '}}"/>
                                @error('quantity')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="form-group">
                                <label for="stock_limit">Low Quantity Warning<font color="red">* </font></label>
                                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="buttom" title='{{__("The system will alert you that you are running low when you reach this number.")}}'></i>
                                <input type="number" class="form-control gifter_js_validation" name="stock_limit" id="stock_limit" min="0" value="{{isset($product['stock_limit']) ? $product['stock_limit'] : ' '}}"/>
                                @error('stock_limit')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-6">
                            <div class="form-group is_promotion_checkbox">
                                <input type="checkbox" id="is_promotion" name="is_promotion" value="1" {{isset($product['is_promotion']) && $product['is_promotion'] == 1 ? 'checked' : ''}}/>
                                <label for="is_promotion">Is Promotion</label><br>
                            </div>
                            <div class="promotion_price_inputbox " style="{{isset($product['is_promotion']) && $product['is_promotion'] == 1 ? 'display:block' : 'display:none'}}">
                                <div class="form-group">
                                    <label for="promotion_price">Promotion Price<font color="red">*</font>  ($)</label>
                                    <input type="number" class="form-control gifter_js_validation" name="promotion_price" id="promotion_price" min="0" value="{{isset($product['promotion_price']) ? $product['promotion_price'] : ''}}"/>
                                    @error('promotion_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-check form-check-info">
                                    <label class="form-check-label" for="run_continue">Run Continuously
                                        <input class="form-check-input" type="checkbox" id="run_continue" name="run_continue" value="1" {{isset($product['run_continuously']) && $product['run_continuously'] == 1 ? 'checked' : ''}}/>
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col col-6">
                                        <div class="form-group">
                                            <label for="start_date">Start Date<font color="red">*</font></label>
                                            <input type="text" class="form-control __promotion_start_date" name="start_date" id="start_date" value="{{isset($product['promotion_start_date']) ? $product['promotion_start_date'] : ' '}}"/>
                                            @error('start_date')
                                                <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col col-6 promotion_end_date" style="{{$product['run_continuously'] == 1 ? 'display:none' : 'display:block'}}">
                                        <div class="form-group">
                                            <label for="end_date">End Date<font color="red">*</font></label>
                                            <input type="text" class="form-control __promotion_end_date" name="end_date" id="end_date" value="{{isset($product['promotion_end_date']) ? $product['promotion_end_date'] : ' '}}"/>
                                            @error('end_date')
                                                <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col col-6">
                            <div class="row px-4">
                                <div class="col col-6">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="product_stock_owner" id="vendor_stock_owner" value="Vendor" {{isset($product['product_stock_owner']) && $product['product_stock_owner'] == 'Vendor' ? 'checked' : ''}}/>
                                        <label for="vendor_stock_owner" class="form-check-label ml-1">Vendor</label>
                                    </div>
                                </div>
                                <div class="col col-6">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="product_stock_owner" id="product_inWarehouse" value="inWarehouse" {{isset($product['product_stock_owner']) && $product['product_stock_owner'] == 'inWarehouse' ? 'checked' : ''}}/>
                                        <label for="product_inWarehouse" class="form-check-label ml-1">In Warehouse</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group select_vendor_name" style="{{$product['product_stock_owner'] == 'Vendor' ? 'display:block' : 'display:none'}}">
                                <label>Vendor<font color="red">*</font></label>
                                <select class="form-control" name="stock_vendor_id" id="stock_vendor_name" style="color: dimgray;">
                                    @if(isset($vendors) && count($vendors) > 0)
                                        @foreach($vendors as $vendor)
                                            <option value="{{$vendor['id']}}" {{$product['vendor_id'] == $vendor['id'] ? 'selected="selected"' : ''}}>{{$vendor['first_name ']}}{{$vendor['last_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('stock_vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <br>
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title">Shipping Details</h5>
                    <div class="row gifter_shipping_type">
                        <div class="col col-6">
                            <div class="form-group">
                                <label for="shipping_type">Shipping Type<font color="red">*</font></label>
                                <select class="form-control" name="shipping_type_id" id="shipping_type" style="color: dimgray;">
                                    <option>Select type...</option>
                                    @if(isset($shipping_types) && count($shipping_types) > 0)
                                        @foreach($shipping_types as $shipping_type)
                                        <option value="{{$shipping_type['id']}}" {{$shipping_type['id'] == $product['shipping_type_id'] ? 'selected="selected"' :''}}>{{$shipping_type['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('shipping_type')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="form-group">
                                <label for="shipping_cost">Shipping Cost<font color="red">* </font> ($)</label>
                                <input type="number" class="form-control" name="shipping_cost" id="shipping_cost" min="0" value="{{isset($product['shipping_cost']) ? $product['shipping_cost'] : 0}}"/>
                                @error('shipping_cost')
                                    <div class="invalid-feedback {{ isset($message)?'d-block':'' }}">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-6">
                            <button type="submit" id="update_product_btn" class="btn btn-primary mr-2 update-button" data-text="Update">Update</button>
                            {{-- <button class="btn btn-secondary text-white">Cancel</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stack('scripts')
@endsection

@section('scripts')
<script src="{{asset('assets/custom/admin/categories/create_category.js')}}"></script>
<script src="{{asset('assets/custom/admin/products/update_product.js')}}"></script>
@endsection
