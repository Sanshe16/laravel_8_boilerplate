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
                    <div id="categories" class="">
                        <h4 class="card-title">Categories</h4>
                        <div>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary ml-2" style="float: right">Add Category</a>
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
<script src="{{asset('assets/custom/admin/categories/delete_category.js')}}"></script>
@endsection
