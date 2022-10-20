<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{Route::currentRouteName() == 'admin.dashboard' ? 'active' : ''}}">
            <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.categories.index' ? 'active' : (Route::currentRouteName() == 'admin.categories.create' ? 'active' : (Route::currentRouteName() == 'admin.categories.show' ? 'active' : (Route::currentRouteName() == 'admin.categories.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="icon-layers menu-icon"></i>
                <span class="menu-title">Categories</span>
            </a>
        </li>

        <li class="nav-item {{Route::currentRouteName() == 'admin.shippingtype.index' ? 'active' : (Route::currentRouteName() == 'admin.shippingtype.create' ? 'active' : (Route::currentRouteName() == 'admin.shippingtype.show' ? 'active' : (Route::currentRouteName() == 'admin.shippingtype.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.shippingtype.index')}}">
                <i class="icon-location menu-icon"></i>
                <span class="menu-title">Shipping Types</span>
            </a>
        </li>

        <li class="nav-item {{Route::currentRouteName() == 'admin.vendor.index' ? 'active' : (Route::currentRouteName() == 'admin.vendor.create' ? 'active' : (Route::currentRouteName() == 'admin.vendor.show' ? 'active' : (Route::currentRouteName() == 'admin.vendor.edit' ? 'active' : '')))}}">
            <a class="nav-link"  href="{{route('admin.vendor.index')}}">
                <i class="ti-user menu-icon"></i>
                <span class="menu-title">Vendors</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.products.index' ? 'active' : (Route::currentRouteName() == 'admin.products.create' ? 'active' : (Route::currentRouteName() == 'admin.products.show' ? 'active' : (Route::currentRouteName() == 'admin.products.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.products.index')}}">
                <i class="icon-bag menu-icon"></i>
                <span class="menu-title">Products</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.banner.index' ? 'active' : (Route::currentRouteName() == 'admin.banner.create' ? 'active' : (Route::currentRouteName() == 'admin.banner.show' ? 'active' : (Route::currentRouteName() == 'admin.banner.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.banner.index')}}">
            <i class="ti-image menu-icon"></i>
            <span class="menu-title">Banners</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.events.index' ? 'active' : (Route::currentRouteName() == 'admin.events.create' ? 'active' : (Route::currentRouteName() == 'admin.events.show' ? 'active' : (Route::currentRouteName() == 'admin.events.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.events.index')}}">
            <i class="ti-calendar menu-icon"></i>
            <span class="menu-title">Events</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.privacy.index' ? 'active' : (Route::currentRouteName() == 'admin.privacy.create' ? 'active' : (Route::currentRouteName() == 'admin.privacy.show' ? 'active' : (Route::currentRouteName() == 'admin.privacy.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.privacy.index')}}">
            <i class="ti-lock menu-icon"></i>
            <span class="menu-title">Privacy</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.faq.index' ? 'active' : (Route::currentRouteName() == 'admin.faq.create' ? 'active' : (Route::currentRouteName() == 'admin.faq.show' ? 'active' : (Route::currentRouteName() == 'admin.faq.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.faq.index')}}">
            <i class="fa fa-question-circle menu-icon"></i>
            <span class="menu-title">FAQs</span>
            </a>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.helpcenter.index' ? 'active' : (Route::currentRouteName() == 'admin.helpcenter.create' ? 'active' : (Route::currentRouteName() == 'admin.helpcenter.show' ? 'active' : (Route::currentRouteName() == 'admin.helpcenter.edit' ? 'active' : '')))}}">
            <a class="nav-link" href="{{route('admin.helpcenter.index')}}">
            <i class="fa fa-handshake-o menu-icon"></i>
            <span class="menu-title">Help Center</span>
            </a>
        </li>


        {{-- <li class="nav-item {{Route::currentRouteName() == 'admin.units.index' ? 'active' : (Route::currentRouteName() == 'admin.units.create' ? 'active' : '')}}">
            <a class="nav-link" data-toggle="collapse" href="#ui-units" aria-expanded="false" aria-controls="ui-units">
            <i class="icon-layout menu-icon"></i>
            <span class="menu-title">Units</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{Route::currentRouteName() == 'admin.units.index' ? 'show' : (Route::currentRouteName() == 'admin.units.create' ? 'show' : '')}}" id="ui-units">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.units.index') }}">All Units</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.units.create') }}">Add Unit</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{Route::currentRouteName() == 'admin.brands.index' ? 'active' : (Route::currentRouteName() == 'admin.brands.create' ? 'active' : '')}}">
            <a class="nav-link" data-toggle="collapse" href="#ui-brand" aria-expanded="false" aria-controls="ui-brand">
            <i class="icon-layout menu-icon"></i>
            <span class="menu-title">Brands</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{Route::currentRouteName() == 'admin.brands.index' ? 'show' : (Route::currentRouteName() == 'admin.brands.create' ? 'show' : '')}}" id="ui-brand">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.brands.index') }}">All Brands</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.brands.create') }}">Add Brands</a></li>
                </ul>
            </div>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-order" aria-expanded="false" aria-controls="form-order">
            <i class="icon-columns menu-icon"></i>
            <span class="menu-title">Orders</span>
            <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-order">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="#">Completed Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pending Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cancelled Order</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
            <i class="icon-grid menu-icon"></i>
            <span class="menu-title">Help Center</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
            <i class="icon-grid menu-icon"></i>
            <span class="menu-title">FAQ's</span>
            </a>
        </li> --}}
    </ul>
</nav>
