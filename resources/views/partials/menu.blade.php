<div id="sidebar-disable" class="sidebar-disable hidden"></div>

<div id="sidebar" class="sidebar-menu transform -translate-x-full ease-in">
    <div class="flex flex-shrink-0 items-center justify-center mt-4">
        <a href="{{ route('admin.home') }}">
            <img class="responsive" src="{{ asset('img/logo.png') }}" style="width: 150px;" alt="logo">
        </a>
    </div>
    <nav class="mt-4">
        <a class="nav-link border-warning{{ request()->is('admin') ? ' active' : '' }}" href="{{ route('admin.home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span class="mx-4">@lang('Dashboard')</span>
        </a>

        @can('user_management_access')
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-users"></i>
                    <span class="mx-4">@lang('User management')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">
                    @can('permission_access')
                        <a class="nav-link{{ request()->is('admin/permissions*') ? ' active' : '' }}" href="{{ route('admin.permissions.index') }}">
                            <i class="fa-fw fas fa-unlock-alt"></i>
                            <span class="mx-4">@lang('Permissions')</span>
                        </a>
                    @endcan
                    @can('role_access')
                        <a class="nav-link{{ request()->is('admin/roles*') ? ' active' : '' }}" href="{{ route('admin.roles.index') }}">
                            <i class="fa-fw fas fa-briefcase"></i>
                            <span class="mx-4">@lang('User profiles')</span>
                        </a>
                    @endcan
                    @can('user_access')
                        <a class="nav-link{{ request()->is('admin/users*') ? ' active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fa-fw fas fa-user"></i>
                            <span class="mx-4">@lang('Customers')</span>
                        </a>
                    @endcan
                    @can('customer_verify_access')
                    <a class="nav-link{{ request()->is('admin/test-users') ? ' active' : '' }}" href="{{ route('admin.users.test') }}">
                        <i class="fas fa-user-check"></i>
                        <span class="mx-4">@lang('Customer Profile Verification')</span>
                    </a>
                    @endcan
                    @can('project_job_position')
                    <a class="nav-link{{ request()->is('admin/projects/job*') ? ' active' : '' }}" href="{{ route('admin.projects.job') }}">
                        <i class="fa-fw fas fa-building"></i>
                        <span class="mx-4">@lang('User Categories')</span>
                    </a>
                    @endcan
                </div>
            </div>
        @endcan
        @can('scooter_list_access')
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-tree"></i>
                    <span class="mx-4">@lang('Price List')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">
                    @if(isset($menu_pricetypelist))
                    @foreach($menu_pricetypelist as $key => $pricetypelist)
                        <a class="nav-link{{ request()->is('admin/scooter/'.$pricetypelist['id']) ? ' active' : '' }}" href="{{ route('admin.scooters-filter', $pricetypelist['id']) }}">
                            <i class="fa-fw fas fa-bars"></i>
                            <span class="mx-4">{{$pricetypelist['name']}}</span>
                        </a>
                    @endforeach
                    @endif
                </div>
            </div>
        @endcan
        @can('scooter_management_access')
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-project-diagram"></i>
                    <span class="mx-4">@lang('Price management')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">
                    @can('scooter_status_access')
                        <a class="nav-link{{ request()->is('admin/pricetype*') ? ' active' : '' }}" href="{{ route('admin.pricetype.index') }}">
                            <i class="fa-fw fas fa-tree"></i>
                            <span class="mx-4">@lang('Price Type')</span>
                        </a>
                    @endcan
                    @can('scooter_access')
                        <a class="nav-link{{ request()->is('admin/scooters*') ? ' active' : '' }}" href="{{ route('admin.scooters.index') }}">
                            <i class="fa-fw fas fa-project-diagram"></i>
                            <span class="mx-4">@lang('Price List')</span>
                        </a>
                    @endcan
                    @can('scooter_status_access')
                        <a class="nav-link{{ request()->is('admin/pricings*') ? ' active' : '' }}" href="{{ route('admin.pricings.index') }}">
                            <i class="fa-fw fas fa-briefcase"></i>
                            <span class="mx-4">@lang('Pricing Class')</span>
                        </a>
                    @endcan
                    @can('scooter_list_access')
                        <a class="nav-link{{ request()->is('admin/pricemanage*') ? ' active' : '' }}" href="{{ route('admin.pricemanage.index') }}">
                            <i class="fa-fw fas fa-rocket"></i>
                            <span class="mx-4">@lang('Price Management')</span>
                        </a>
                    @endcan
                </div>
            </div>
        @endcan
        @can('scooter_management_access')
            <div class="nav-dropdown">
                <a class="nav-link border-danger" href="#">
                    <i class="fa-fw fas fa-bullseye"></i>
                    <span class="mx-4">@lang('Price List Comparation')</span>
                    <i class="fa fa-caret-down ml-auto" aria-hidden="true"></i>
                </a>
                <div class="dropdown-items mb-1 hidden">                    
                    <a class="nav-link{{ request()->is('admin/pricecompetitor*') ? ' active' : '' }}" href="{{ route('admin.pricecompetitor.index') }}">
                        <i class="fa-fw fas fa-tree"></i>
                        <span class="mx-4">@lang('Competitor Management')</span>
                    </a>
                    <a class="nav-link{{ request()->is('admin/pricecompares/allcom*') ? ' active' : '' }}" href="{{ route('admin.pricecompares.allcom') }}">
                        <i class="fa-fw fas fa-list"></i>
                        <span class="mx-4">@lang('All Comparation')</span>
                    </a>
                </div>
            </div>
        @endcan
        @can('projects_access')                        
            <a class="nav-link border-success{{ request()->is('admin/projects*') ? ' active' : '' }}" href="{{ route('admin.projects') }}">
                <i class="fas fa-fw fa-folder"></i>
                <span class="mx-4">@lang('Offer list')</span>
            </a>
        @endcan
        @can('customer_access')
            <a class="nav-link border-success{{ request()->is('admin/customer*') ? ' active' : '' }}" href="{{ route('admin.customer') }}">
                <i class="fas fa-fw fa-folder"></i>
                <span class="mx-4">@lang('Customer Manager')</span>
            </a>
        @endcan
        @can('language_access')
        <a class="nav-link border-white{{ request()->is('admin/language*') ? ' active' : '' }}" href="{{ route('admin.language.index') }}">
            <i class="fas fa-fw fa-language"></i>
            <span class="mx-4">@lang('Language Setting')</span>
        </a>
        @endcan
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            <a class="nav-link border-white{{ request()->is('profile/password') ? ' active' : '' }}" href="{{ route('profile.password.edit') }}">
                <i class="fa-fw fas fa-key"></i>
                <span class="mx-4">@lang('Change password')</span>
            </a>
        @endif
        @can('settings_access')
            <a class="nav-link border-white{{ request()->is('admin/settings') ? ' active' : '' }}" href="{{ route('admin.settings') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span class="mx-4">@lang('Settings')</span>
            </a>
        @endcan
        <a class="nav-link border-white" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="fa-fw fas fa-sign-out-alt"></i>
            <span class="mx-4">@lang('Logout')</span>
        </a>
    </nav>
</div>
