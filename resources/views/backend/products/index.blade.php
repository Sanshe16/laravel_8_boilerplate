@extends('backend.layouts.master-layout')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/custom/admin/page/admin_profile_settings.css')}}">
@endsection

@section('body')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card p-2">
            <div class="card-body">
                   {{-- Categories Index --}}
                    <div id="products" class="all_product_table">
                        <h4 class="card-title">Products</h4>
                        <div>
                            <a href="{{route('admin.products.create')}}" class="btn btn-primary ml-2" style="float: right;width: 140px;">Add Product</a>
                        </div>

                        {{$dataTable->table()}}

                    </div>
            </div>
        </div>
    </div>
</div>
@stack('scripts')
@endsection

@section('scripts')
{{$dataTable->scripts()}}
<script src="{{asset('assets/custom/admin/products/delete_products.js')}}"></script>
@endsection
