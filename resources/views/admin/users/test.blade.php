@extends('layouts.admin-verify')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="main-card">
    <div class="header">
        <div class="d-flex justify-content-between col-12">
            <div class="col-md-3">
                <select class="form-control" id="userlist" name="userlist" onchange="selectUser()">
                    @if($users)
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div>
                <a class="btn-sm btn-red" href="{{route('admin.home')}}">
                    @lang('Back')
                </a>
            </div>
        </div>
    </div>
    <div class="body">
        <div id="userTestFrame">

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.min.js"></script>
<script>
    var _token = null;
    $(document).ready(() => {
        _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    });
    function selectUser(){
        var selectElement = document.getElementById("userlist");
        var selectedValue = selectElement.value;
        showSwalLoading();
        $.ajax({
            url: "{{route('admin.users.test')}}",
            headers: {'x-csrf-token': _token},
            method: 'POST',
            data: {
                uid: selectedValue,
            },
            success: (res) => {
                $('#userTestFrame').html(res);
                swal.close();
            },
            error: () => {
                swal.close();
            }
        });
    }
    function showSwalLoading(title = "@lang('Please wait...')", text = "@lang('Please wait...')") {
        Swal.fire({
            title: title,
            // text: text,                
            allowOutsideClick: false,
            showConfirmButton:false,
            showCancelButton:false,
            allowEscapeKey: false,
            willOpen: () => {
                Swal.showLoading()
            }
        });   
    }

    $(document).on('click', '.nav-link[data-name]', function() {
        showSwalLoading();
        var selected_link = $(this).data('name');
        var selectElement = document.getElementById("userlist");
        var selectedUserId = selectElement.value;
        $('.dropdown-items').addClass('hidden');
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        $('.emulate-content').html('');
        switch(selected_link) {
            case 'dashboard':
                $.ajax({
                    url: `{{route('admin.dashboard-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'permissions':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.permissions-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'user_profile':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.roles-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'customers':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.users-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'customer_profile_verification':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.users.verify-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'user_categories':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.projects.job-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'pricelist':
                $(this).parents('.dropdown-items').removeClass('hidden');
                var pricelist_id = $(this).data('id');
                $.ajax({
                    url: `{{route('admin.scooters-filter-test')}}/${selectedUserId}/${pricelist_id}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'price_type':
                $(this).parents('.dropdown-items').removeClass('hidden');
                var pricelist_id = $(this).data('id');
                $.ajax({
                    url: `{{route('admin.pricetype-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'price_list':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.scooters-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'pricing_class':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.pricings-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'price_management':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.pricemanage-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'competitor_management':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.pricecompetitor-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'all_comparation':
                $(this).parents('.dropdown-items').removeClass('hidden');
                $.ajax({
                    url: `{{route('admin.pricecompares.allcom-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'language_setting':
                $.ajax({
                    url: `{{route('admin.language-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'change_password':
                $.ajax({
                    url: `{{route('profile.password.edit-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            case 'settings':
                $.ajax({
                    url: `{{route('admin.settings-test')}}/${selectedUserId}`,
                    method: 'GET',
                    success: (res) => {
                        $('.emulate-content').html(res);
                        swal.close();
                    },
                    error: () => {
                        swal.close();
                    }
                });
                break;
            default:
                swal.close();
                break;
        }
    });
</script>
@endsection