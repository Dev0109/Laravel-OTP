<div id="sidebar-disable" class="sidebar-disable hidden"></div>

<div id="sidebar" class="sidebar-menu transform -translate-x-full ease-in">
    <div class="flex flex-shrink-0 items-center justify-center mt-4">
        <a href="#">
            <img class="responsive" src="{{ asset('img/logo.png') }}" style="width: 150px;" alt="logo">
        </a>
    </div>
    <nav class="mt-4">
        <a class="nav-link border-warning" href="#" data-name="dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span class="mx-4">@lang('Dashboard')</span>
        </a>

        @if(in_array('user_management_access', $role) && isset($role))
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-users"></i>
                    <span class="mx-4">@lang('User management')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">
                    @if(in_array('permission_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="permissions">
                            <i class="fa-fw fas fa-unlock-alt"></i>
                            <span class="mx-4">@lang('Permissions')</span>
                        </a>
                    @endif
                    @if(in_array('role_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="user_profile">
                            <i class="fa-fw fas fa-briefcase"></i>
                            <span class="mx-4">@lang('User profiles')</span>
                        </a>
                    @endif
                    @if(in_array('user_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="customers">
                            <i class="fa-fw fas fa-user"></i>
                            <span class="mx-4">@lang('Customers')</span>
                        </a>
                    @endif
                    @if(in_array('customer_verify_access', $role) && isset($role))
                    <a class="nav-link" href="#" data-name="customer_profile_verification">
                        <i class="fas fa-user-check"></i>
                        <span class="mx-4">@lang('Customer Profile Verification')</span>
                    </a>
                    @endif
                    @if(in_array('project_job_position', $role) && isset($role))
                    <a class="nav-link" href="#" data-name="user_categories">
                        <i class="fa-fw fas fa-building"></i>
                        <span class="mx-4">@lang('User Categories')</span>
                    </a>
                    @endif
                </div>
            </div>
        @endif
        @if(in_array('scooter_list_access', $role) && isset($role))
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-tree"></i>
                    <span class="mx-4">@lang('Price List')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">
                    @if(isset($menu_pricetypelist))
                    @foreach($menu_pricetypelist as $key => $pricetypelist)
                        <a class="nav-link" href="#" data-name="pricelist" data-id="{{$pricetypelist['id']}}">
                            <i class="fa-fw fas fa-bars"></i>
                            <span class="mx-4">{{$pricetypelist['name']}}</span>
                        </a>
                    @endforeach
                    @endif
                </div>
            </div>
        @endif
        @if(in_array('scooter_management_access', $role) && isset($role))
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-project-diagram"></i>
                    <span class="mx-4">@lang('Price management')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">
                    @if(in_array('scooter_status_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="price_type">
                            <i class="fa-fw fas fa-tree"></i>
                            <span class="mx-4">@lang('Price Type')</span>
                        </a>
                    @endif
                    @if(in_array('scooter_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="price_list">
                            <i class="fa-fw fas fa-project-diagram"></i>
                            <span class="mx-4">@lang('Price List')</span>
                        </a>
                    @endif
                    @if(in_array('scooter_status_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="pricing_class">
                            <i class="fa-fw fas fa-briefcase"></i>
                            <span class="mx-4">@lang('Pricing Class')</span>
                        </a>
                    @endif
                    @if(in_array('scooter_list_access', $role) && isset($role))
                        <a class="nav-link" href="#" data-name="price_management">
                            <i class="fa-fw fas fa-rocket"></i>
                            <span class="mx-4">@lang('Price Management')</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif
        @if(in_array('scooter_management_access', $role) && isset($role))
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-bullseye"></i>
                    <span class="mx-4">@lang('Price List Comparation')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">                    
                    <a class="nav-link" href="#" data-name="competitor_management">
                        <i class="fa-fw fas fa-tree"></i>
                        <span class="mx-4">@lang('Competitor Management')</span>
                    </a>
                    <a class="nav-link" href="#" data-name="all_comparation">
                        <i class="fa-fw fas fa-list"></i>
                        <span class="mx-4">@lang('All Comparation')</span>
                    </a>
                </div>
            </div>
        @endif
        @if(in_array('projects_access', $role) && isset($role))
            <a class="nav-link border-success" href="#" data-name="offer_list">
                <i class="fas fa-fw fa-folder"></i>
                <span class="mx-4">@lang('Offer list')</span>
            </a>
        @endif
        @if(in_array('customer_access', $role) && isset($role))
            <a class="nav-link border-success" href="#" data-name="customer_manager">
                <i class="fas fa-fw fa-folder"></i>
                <span class="mx-4">@lang('Customer Manager')</span>
            </a>
        @endif
        @if(in_array('language_access', $role) && isset($role))
        <a class="nav-link border-white" href="#" data-name="language_setting">
            <i class="fas fa-fw fa-language"></i>
            <span class="mx-4">@lang('Language Setting')</span>
        </a>
        @endif
        <a class="nav-link border-white" href="#" data-name="change_password">
            <i class="fa-fw fas fa-key"></i>
            <span class="mx-4">@lang('Change password')</span>
        </a>
        @if(in_array('settings_access', $role) && isset($role))
            <a class="nav-link border-white" href="#" data-name="settings">
                <i class="fas fa-fw fa-cog"></i>
                <span class="mx-4">@lang('Settings')</span>
            </a>
        @endif
        <a class="nav-link border-white" href="#" data-name="logout">
            <i class="fa-fw fas fa-sign-out-alt"></i>
            <span class="mx-4">@lang('Logout')</span>
        </a>
    </nav>
</div>
