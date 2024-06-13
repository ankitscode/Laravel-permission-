<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                {{-- Admin panel --}}
                <img src="{{ URL::asset('assets/images/logo.jpg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                {{-- Admin panel --}}
                <img src="{{ URL::asset('assets/images/logo.jpg') }}" alt="" height="" class="w-50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                {{-- Admin panel --}}
                <img src="{{ URL::asset('assets/images/logo.jpg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                {{-- Admin panel --}}
                <img src="{{ URL::asset('assets/images/svgviewer-output.svg') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                @canany(['all permission'])
                <li class="nav-item">
                    
                    <a class="nav-link {{ (request()->is('dashboard*')) ? 'menu-link active' : 'menu-link' }}" href="{{route('dashboard')}}">
                        <i class="ri-dashboard-2-line"></i> <span>@lang('main.dashboard')</span>
                    </a>
                </li> <!-- end Dashboard Menu -->
                @endcanany

                @canany(['all permission'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ (request()->is('user*')) ? 'active collaspe' : 'collapsed' }}" href="#users"  data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->is('user*')) ? 'true' : 'false' }}" aria-controls="users">
                            <i class="ri-account-circle-line"></i> <span>@lang('main.users')</span>
                        </a>
                        <div class="menu-dropdown {{ (request()->is('user*')) ? 'collapse show' : 'collapse' }}" id="users">
                            <ul class="nav nav-sm flex-column">
                                @can('all permission')
                                    <li class="nav-item">
                                        <a href="{{route('userindex')}}" class="nav-link  {{ (request()->is('user/userindex*')) ? 'active' : '' }}">@lang('All Users')</a>
                                    </li>
                                @endcan
                                @can('all permission')
                                    <li class="nav-item">
                                        <a href="{{route('admin.roleList')}}" class=" nav-link {{ request()->is('user/role*') ? 'active' : '' }}">@lang('User Roles')</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li> <!-- end Users Menu -->
                @endcanany
                <!--for doctor guard-->
                <li class="nav-item">
                        @auth('doctor')
                        <a class="nav-link menu-link {{ (request()->is('doctor*')) ? 'active collaspe' : 'collapsed' }}" href="#catalog"  data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->is('catalog*')) ? 'true' : 'false' }}" aria-controls="catalog" data-key="t-email">
                            <i class="ri-apps-2-line"></i> <span>@lang('Doctor')</span>
                        </a>
                        <div class="menu-dropdown {{ (request()->is('doctor*')) ? 'collapse show' : 'collapse' }}" id="catalog">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route('doctorindex')}}" class="nav-link {{ (request()->is('doctor/doctorindex*')) ? 'active' : '' }}">@lang('All Doctors')</a>
                                </li>
                                    <li class="nav-item">
                                        <a href="{{route('doctor.roleList')}}" class="nav-link {{ (request()->is('doctorrole*')) ? 'active' : '' }}">@lang('Doctor Roles')</a>
                                    </li>
                              
                            </ul>
                        </div>
                     @endauth
                    </li> <!--- Catalog -->

                {{-- <li class="nav-item">
                    <a href="#attribute" class="nav-link menu-link {{ (request()->is('stores/attributes*')) ? 'active collaspe' : 'collapsed' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->is('stores/attributes*')) ? 'true' : 'false' }}" aria-controls="attribute" data-key="t-email">
                        <i class="ri-stack-line"></i> <span>@lang('main.attributes')</span>
                    </a>
                    <div class="menu-dropdown {{ (request()->is('stores/attributes*')) ? 'collapse show' : 'collapse' }}" id="attribute">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.attributeProductList')}}" class="nav-link {{ (request()->is('stores/attributes/product*')) ? 'active' : '' }}">@lang('main.products')</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.indexVariationType')}}" class="nav-link {{ (request()->is('stores/attributes/attributeSet*')) ? 'active' : '' }}">@lang('main.attribute_set')</a>
                            </li>
                        </ul>
                    </div>
                </li> <!--- Attributes --> --}}

                {{-- <li class="nav-item">
                    <a href="#settings" class="nav-link menu-link {{ (request()->is('settings*')) ? 'active collaspe' : 'collapsed' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ (request()->is('settings*')) ? 'true' : 'false' }}" aria-controls="settings" data-key="t-email">
                        <i class="ri-stack-line"></i> <span>@lang('main.setting')</span>
                    </a>
                    <div class="menu-dropdown {{ (request()->is('settings*')) ? 'collapse show' : 'collapse' }}" id="settings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{route('admin.indexListType')}}" class="nav-link {{ (request()->is('settings/list-type*')) ? 'active' : '' }}">@lang('main.lockup')</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.indexConfiguration')}}" class="nav-link {{ (request()->is('settings/configuration*')) ? 'active' : '' }}">@lang('main.configuration')</a>
                            </li>
                        </ul>
                    </div>
                </li> <!--- setting --> --}}
                <!-- end store Menu -->
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
