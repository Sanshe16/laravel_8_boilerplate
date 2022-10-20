@extends('backend.layouts.master-layout')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/custom/product_view.css')}}">
@endsection


@section('body')

<div class="product_view">
    <div class="row">
        <div class="col-md-12">
            <div class="body">
                <div class="card d-block" style="border-radius: 20px 20px 0px 0px;">
                    <div class="d-flex justify-content-between align-items-center px-2">
                        <div>
                            <button class="btn btn-secondary m-2 p-2" style=><a href="{{route('admin.products.index')}}" class="gifter_back_btn"><i class="fa fa-angle-left pr-1" style="font-size:12px"></i>Back</a></button>
                        </div>
                        <div style="margin-left: -7%;">
                            <h3 class="card-title text-center pt-3">Product</h3>
                        </div>
                        <div></div>
                    </div>
                </div>
                {{-- left card  --}}
                <div class="card p-2" style="border-radius: 0px 0px 20px 20px;">
                    
                    <div class="product-imgs ">
                        <div class="d-flex " style="height:370px">
                            <div style="width: 100%;">
                                <img  src="{{ !is_null($product['media'][0]['product_image']) ? url($product['media'][0]['product_image']) : asset('assets/images/placeholder-image.png') }}" id="product_view_img" style="height: 100%; width: 100%; object-fit: contain">
                            </div>
                        </div>
                        <br>
                        <div class="thumbnail">
                            @foreach ($product->media as $image)
                                @php
                                    getThumbnailAndOriginalImage($image, 'product_image');
                                @endphp
                                <div class="pr-2">
                                    <img src="{{ !is_null($image['product_image']) ? url($image['product_image']) : asset('assets/images/placeholder-image.png') }}" style="height: 100%; width: 100%; object-fit: contain">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- card right -->
                    <div class="product-content pt-0">
                        <h4 class="product_name">{{ isset($product->name) ? $product->name : '' }}</h4>
                        <li class="product-link">{{__('Category ')}}:  
                            @if(isset($product['categories']) && $product['categories']->count())
                                @foreach($product['categories'] as $category)
                                    <span class=" p-2" data-id="{{$category['id']}}" data-level="{{$category['level']}}" data-parent="{{$category['parent_id']}}">{!! $category['name'] !!}</span>
                                @endforeach
                            @else
                                <span class=" p-2">{{trans('admin.NO_CATEGORY_SELECTED')}} <i class="ti-info-alt ml-2" style="cursor: pointer;" data-toggle="tooltip" title="{{trans('admin.NO_CATEGORY_SELECTED_DEATIL')}}"></i></span>
                            @endif
                        </li>
            
                        <div class="product-price">
                            <p class="last-price">{{__('Purchase Price: ')}}<span>${{ isset($product->purchase_price) ? $product->purchase_price : '' }}</span></p>
                            
                            @if (isset($product->is_promotion) && $product->is_promotion == 1)
                                <p class="new-price selling_price">{{__('Selling Price: ')}}<span>${{ isset($product->price) ? $product->price : '' }}</5span></p>
                                <p class="new-price promotion_price">{{__('Promotion Price: ')}}<span>${{ isset($product['promotion_price']) ? $product['promotion_price'] : ''}}</5span></p>
                            @else
                                <p class="new-price">{{__('Selling Price: ')}}<span>${{ isset($product->price) ? $product->price : '' }}</span></p>
                            @endif
                        </div>
            
                        <div class="product-detail">
                            
                            <ul>
                                @if (isset($product['is_active']))
                                    <li>
                                        <div style="display: flex;">
                                            <i class="fa fa-check-circle"></i>
                                            {{__('Available: ')}}<span>{{isset($product['is_active']) && $product['is_active'] == 1 ? 'In stock' : 'Out of stock'}}</span>
                                        </div>
                                    </li>
                                @endif
                                
            
                                @if(isset($product['sku']) && !is_null($product['sku']))
                                    <li>
                                        <div style="display: flex;">
                                            <i class="fa fa-check-circle"></i>{{__('SKU / UPC: ')}} <span>{{$product['sku']}}</span>
                                        </div>
                                    </li>
                                @endif

                                @if (isset($shipping_type['name']) && !is_null($shipping_type['name']))
                                    <li>
                                        <div style="display: flex;">
                                            <i class="fa fa-check-circle"></i>{{__('Shipping Type: ')}} <span>{{$shipping_type['name']}}</span>
                                        </div>
                                    </li>
                                @endif 
            
                                @if(isset($shipping_type['shipping_cost']) && !is_null($shipping_type['shipping_cost']))
                                    <li>
                                        <div style="display: flex;">
                                            <i class="fa fa-check-circle"></i>{{__('Shipping Fee: ')}} <span>${{$shipping_type['shipping_cost']}}</span>
                                        </div>
                                    </li>
                                @endif 
            
                                @if((isset($shipping_type['min_shipping_days']) && !is_null($shipping_type['min_shipping_days'])) && (isset($shipping_type['max_shipping_days']) && !is_null($shipping_type['min_shipping_days'])))
                                    <li>
                                        <div style="display: flex;">
                                            <i class="fa fa-check-circle"></i>{{__('Shipping days: ')}} <span>{{$shipping_type['min_shipping_days']. '  to  '. $shipping_type['max_shipping_days']}}{{__(' days ')}}</span>
                                        </div>
                                    </li>
                                @endif
                                
                                @if(isset($product['product_box']) && !is_null($product['product_box']))
                                    <li>
                                        <div style="">
                                            <i class="fa fa-check-circle"></i>{{__('Product Box: ')}} <span>{{$product['product_box']}}</span>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="card p-2 mt-4 d-block">
                    <div class="card-body gifter_table_style">
                        <h5 class="card-title">{{__('About this product')}} </h5>
                    {!! $product['details'] !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
    
</div>




@stack('scripts')
@endsection

@section('scripts')
<script src="{{asset('assets/custom/admin/products/product_view.js')}}"></script>
@endsection
