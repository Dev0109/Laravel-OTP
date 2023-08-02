<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />    
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-xWMNpZa8lWg10fGXGQe2NRwHngbpf91Nh0aDFlZjX9A1+OzJrWSeYCM+y2/pFBNyhuertM6rzOlU6+NlOIbRg==" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <!-- For Modal -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- End For Modal -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    @yield('styles')
</head>

<body>
    <div class="flex h-screen bg-gray-200">
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="main-header">
                <div>
                    @if(request()->is('admin/projects/detail*'))
                    <div class="d-inline-block mr-3">
                        <a href="{{route('admin.projects.profile')}}/{{$pid}}/{{$cid}}/{{$uid}}" class="btn btn-outline-danger button-boxed">
                            <i class="fa fa-reply"></i>
                            <small>@lang('Back')</small>
                        </a>
                        <a class="btn btn-outline-success button-boxed btn-first-unit-next" onclick="onNewUnit()" style="display: none;">
                            <i class="fa fa-check"></i>
                            <small>@lang('Next')</small>
                        </a>
                        <a class="btn btn-outline-info button-boxed btn-preview" onclick="preview2PDF()" style="display: none;">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <small>@lang('Preview')</small>
                        </a>
                        <a class="btn btn-outline-success button-boxed btn-unit-save" onclick="onSaveUnit()" style="display: none;">
                            <i class="fa fa-save"></i>
                            <small>@lang('Save')</small>
                        </a>
                        <a class="btn btn-outline-info button-boxed btn-offer-save" onclick="showMultipleModal()" style="display: none;">
                            <i class="fa fa-save"></i>
                            <small>@lang('Save Offer')</small>
                        </a>
                        <a href="#" class="btn btn-outline-primary button-boxed btn-report" onclick="pdfReport()" style="display: none;">
                            <i class="fa fa-book"></i>
                            <small>@lang('PDF Report')</small>
                        </a>
                    </div>
                    @endif
                    @if(request()->is('admin/projects'))
                    <div class="d-inline-block mr-3">
                        <a href="{{route('admin.projects.profile')}}" class="btn btn-outline-success button-boxed">
                            <i class="fa fa-plus"></i>
                            <small>@lang('New')</small>
                        </a>
                        <a class="btn btn-outline-success button-boxed" onclick="modify()">
                            <i class="fa fa-edit"></i>
                            <small>@lang('Modify')</small>
                        </a>
                        <a class="btn btn-outline-info button-boxed" onclick="duplicate()">
                            <i class="fa fa-paste"></i>
                            <small>@lang('Duplicate')</small>
                        </a>
                        <a class="btn btn-outline-danger button-boxed" onclick="del()">
                            <i class="fa fa-trash"></i>
                            <small>@lang('Delete')</small>
                        </a>
                    </div>
                    @endif
                    @if(request()->is('admin/customer') || request()->is('admin/projects/profile*'))
                    <div class="d-inline-block mr-3">
                        <div id="customer_manager_company_buttons">
                            <h5 class="d-inline-block mr-2">Company</h5>
                            <div class="d-inline-block mr-3">
                                <a class="btn btn-outline-success button-boxed" onclick="newCompany()">
                                    <i class="fa fa-plus"></i>
                                    <small>@lang('New')</small>
                                </a>
                                <a class="btn btn-outline-success button-boxed" onclick="editCompany()">
                                    <i class="fa fa-edit"></i>
                                    <small>@lang('Modify')</small>
                                </a>
                                <a class="btn btn-outline-danger button-boxed mr-3" onclick="deleteCompany()">
                                    <i class="fa fa-trash"></i>
                                    <small>@lang('Delete')</small>
                                </a>
                            </div>
                        </div>
                        <div id="customer_manager_contact_buttons" style="display: none;">
                            <h5 class="d-inline-block mr-2">Contact</h5>
                            <div class="d-inline-block mr-3">
                                <a href="#" class="btn btn-outline-success button-boxed" onclick="newContact()">
                                    <i class="fa fa-plus"></i>
                                    <small>@lang('New')</small>
                                </a>
                                <a href="#" class="btn btn-outline-success button-boxed" onclick="updateContact()">
                                    <i class="fa fa-edit"></i>
                                    <small>@lang('Modify')</small>
                                </a>
                                <!-- <a href="#" class="btn btn-outline-danger button-boxed" onclick="deleteContact()">
                                    <i class="fa fa-trash"></i>
                                    <small>@lang('Delete')</small>
                                </a>                     -->
                            </div>
                        </div>
                    </div>
                    
                        @if(request()->is('admin/projects/profile*'))
                        <a href="{{route('admin.projects')}}" class="btn btn-outline-danger button-boxed">
                            <i class="fa fa-reply"></i>
                            <small>@lang('Back')</small>
                        </a>
                        <a class="btn btn-outline-success button-boxed" onclick="goToDetail()">
                            <i class="fa fa-check"></i>
                            <small>@lang('Next')</small>
                        </a>
                        @endif
                    @endif
                    <select class="language langSel">
                        @foreach($languagelists as $item)
                            <option value="{{ $item->code }}" @if(session('lang') == $item->code) selected @endif>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(count(config('panel.available_languages', [])) > 1)
                    <div class="flex items-center">
                        <div class="languages">
                            <select onchange="window.location.href = $(this).val()">
                                @foreach(config('panel.available_languages') as $langLocale => $langName)
                                    <option
                                        value="{{ url()->current() }}?change_language={{ $langLocale }}"
                                        @if(strtoupper($langLocale) ==strtoupper(app()->getLocale())) selected @endif
                                    >{{ strtoupper($langLocale) }} ({{ $langName }})</option>
                                @endforeach
                            </select>
                            <div class="icon">
                                <i class="fa fa-caret-down fill-current h-4 w-4" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="mx-auto px-6 py-8">
                    @if(session('message'))
                        <div class="alert success">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert danger">
                            <ul class="list-none">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                </div>
            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>    
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <!-- For Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.min.js"></script>    
    <!-- end For Modal -->
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')

    <script>
        $(".langSel").on("change", function() {
            window.location.href = "{{route('admin.home')}}/languages/change/"+$(this).val();
            // $.ajax({
            //     url: "{{route('admin.home')}}/languages/change/"+$(this).val(),
            //     method: "GET",
            //     success: (res) => {
            //         console.log(res);
            //     },
            //     error: (error) => {
            //         console.error(error);
            //     }
            // })
        });
    </script>
</body>

</html>
