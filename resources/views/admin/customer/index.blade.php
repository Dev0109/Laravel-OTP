@extends('layouts.admin')
@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<div class="row">
    <div class="col-md-6">
        <div class="main-card">
            <div class="header">
                @lang('Company selection')
            </div>
            <div class="body">
                <div class="w-full">
                    <table class="display compact project-table datatable-customer">
                        <thead>
                            <tr>
                                <th>@lang('Corporate name')</th>
                                <th>@lang('Address')</th>
                                <th>@lang('Tel. No.')</th>
                                <th>@lang('VAT')</th>
                                <th>@lang('Description')</th>                        
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(isset($company_list)) {
                                foreach($company_list as $company) {
                                    ?>
                            <tr data-id="{{$company->id}}">
                                <td class="nowrap">{{$company->name}}</td>
                                <td class="nowrap">{{$company->address}}</td>
                                <td class="nowrap">{{$company->phone}}</td>                        
                                <td class="nowrap">{{$company->VAT}}</td>
                                <td class="nowrap">{{$company->description}}</td>
                            </tr>
                                    <?php
                                }
                            }                    
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="main-card">
            <div class="header">
                @lang('Contact list')
            </div>
            <div class="body">
                <div class="w-full">
                    <table class="display compact project-table datatable-contact">
                        <thead>
                            <tr>
                                <th>@lang('First Name')</th>
                                <th>@lang('Last Name')</th>
                                <th>@lang('Tel. No.')</th>
                                <th>@lang('Mobile Phone Number')</th>
                                <th width="30%">@lang('Email')</th>
                                <th>@lang('Job Position')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="companyModal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">@lang('Company')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" id="company_id" name="company_id" class="form-control">                
            <div class="form-group">
                <label for="company_name" class="text-xs">@lang('Corporate name')</label>
                <input type="text" id="company_name" name="company_name" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_address" class="text-xs">@lang('Address')</label>
                <input type="text" id="company_address" name="company_address" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_phone" class="text-xs">@lang('Tel. No.')</label>
                <input type="text" id="company_phone" name="company_phone" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_VAT" class="text-xs">@lang('VAT')</label>
                <input type="text" id="company_VAT" name="company_VAT" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_desc" class="text-xs">@lang('Description')</label>
                <input type="text" id="company_desc" name="company_desc" class="form-control">                
            </div>            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
            <button type="button" class="btn btn-primary" onclick="saveCompany()">@lang('Save')</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">@lang('Contact People')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" id="contact_id" name="contact_id" class="form-control">                
            <div class="form-group">
                <label for="first_name" class="text-xs">@lang('First Name')</label>
                <input type="text" id="first_name" name="first_name" class="form-control">                
            </div>
            <div class="form-group">
                <label for="last_name" class="text-xs">@lang('Last Name')</label>
                <input type="text" id="last_name" name="last_name" class="form-control">                
            </div>
            <div class="form-group">
                <label for="tel_no" class="text-xs">@lang('Tel. No.')</label>
                <input type="text" id="tel_no" name="tel_no" class="form-control">                
            </div>
            <div class="form-group">
                <label for="mobile_no" class="text-xs">@lang('Mobile Phone Number')</label>
                <input type="text" id="mobile_no" name="mobile_no" class="form-control">                
            </div>
            <div class="form-group">
                <label for="email" class="text-xs">@lang('Email')</label>
                <input type="text" id="email" name="email" class="form-control">                
            </div>
            <div class="form-group">
                <label for="job_position" class="text-xs">@lang('Job Position')</label>                
                <select class="form-control" id="job_position" name="job_position">
                    @foreach($job_list as $key => $job)
                    <option value="{{$job->name}}">{{$job->name}}</option>
                    @endforeach
                </select>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
            <button type="button" class="btn btn-primary" onclick="saveContact()">@lang('Save')</button>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
    @parent
    <script>
        var table1, table2;
        var contact_list = null;
        var temp_func = null;
        var _token = null;
        $(function () {            
            table1 = $('.datatable-customer').DataTable({
                dom:'ftlp',
                pageLength: 10,
                scrollCollapse: true,
                scrollX: false,
                responsive: true,
                select: {
                    style: 'single' // or 'multi'
                }
            });
            table1.on('select', function(e, dt, type, indexes) {
                if(type === 'row') {
                    // console.log('Row '+indexes[0]+' selected');
                    var id = $(dt.row(indexes[0]).node()).data('id');
                    $('#customer_manager_company_buttons').show();
                    $('#customer_manager_contact_buttons').hide();
                    drawContactTable(id);
                    table1.draw(false);
                }
            });
            
            
            table2 = $('.datatable-contact').DataTable({
                dom:'ftlp',
                pageLength: 10,
                scrollCollapse: true,
                responsive: true,
                select: {
                    style: 'single' // or 'multi'
                }
            });
            table2.on('select', function(e, dt, type, indexes) {
                if(type === 'row') {
                    $('#customer_manager_company_buttons').hide();
                    $('#customer_manager_contact_buttons').show();
                }
            });
        });
        $(document).on('click', 'tbody tr', (e) => {
            var classlist = $(e.target).parents('table').attr('class').split(' ');
            if (classlist.includes('datatable-customer')) {
                $('#customer_manager_company_buttons').show();
                $('#customer_manager_contact_buttons').hide();
            } else {
                $('#customer_manager_company_buttons').hide();
                $('#customer_manager_contact_buttons').show();
            }
        });

        function drawContactTable(company_id) {
            $.ajax({
                method: 'GET',
                url: '{{route('admin.customer.get.contactlist')}}',
                data: {id: company_id}
            }).done(function (res) { 
                contact_list = res.result;
                table2.rows().remove();
                for(var i=0;i<res.result.length;i++) {                            
                    var $tr = $(`<tr data-id="${res.result[i].id}">\
                    <td>${res.result[i].firstname}</td>\
                    <td>${res.result[i].secondname}</td>\
                    <td>${res.result[i].phone}</td>\
                    <td>${res.result[i].mobile}</td>\
                    <td>${res.result[i].email}</td>\
                    <td>${res.result[i].job_position}</td>\
                    </tr>`);                            
                    table2.row.add($tr);
                }
                table2.draw(false);
                if(temp_func !== null) {
                    temp_func();
                    temp_func = null;
                }
            });
        }

        function newContact() {
            var company_id = $(table1.row({selected: true}).node()).data('id');
            if(company_id === undefined) {
                alert("@lang('You must select Company first')");
                return false;
            }
            $('#contactModal .modal-title').text("@lang('Create Contact People')");
            $('#contactModal #contact_id').val(0);
            $('#contactModal #first_name').val("");
            $('#contactModal #last_name').val("");
            $('#contactModal #tel_no').val("");
            $('#contactModal #mobile_no').val("");
            $('#contactModal #email').val("");
            $('#contactModal #job_position').val("");
            $('#contactModal').modal('show');
        }

        function updateContact() {
            var contact_id = $(table2.row({selected: true}).node()).data('id');
            if(contact_id === undefined) {
                alert("@lang('You must select Contact people to update')");
                return false;
            }
            $('#contactModal .modal-title').text("@lang('Edit Contact People')");

            let contact_user = contact_list.filter(c => c.id == contact_id)[0];            

            $('#contactModal #contact_id').val(contact_user.id);
            $('#contactModal #first_name').val(contact_user.firstname);
            $('#contactModal #last_name').val(contact_user.secondname);
            $('#contactModal #tel_no').val(contact_user.phone);
            $('#contactModal #mobile_no').val(contact_user.mobile);
            $('#contactModal #email').val(contact_user.email);
            $('#contactModal #job_position').val(contact_user.job_position);
            $('#contactModal').modal('show');
        }

        function saveContact() {
            var flag = true;
            var company_id = $(table1.row({selected: true}).node()).data('id');
            var postData = {company_id: company_id};
            $('#contactModal .modal-body input,#contactModal .modal-body select').each(function() {
                const _v = $(this).val().trim();
                if(_v === "") {
                    alert("@lang('You must input all fields.')");
                    $(this).focus();
                    flag = false;
                    return false;
                }
                postData[$(this).attr('name')] = _v;
            });
            if(!flag)
                return false;
            
            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.customer.store.contact')}}',
                data: postData
            }).done(function (res) { 
                drawContactTable(company_id);
                $('.modal').modal('hide');
            });
        }

        function deleteContact() {
            var company_id = $(table1.row({selected: true}).node()).data('id');
            var contact_id = $(table2.row({selected: true}).node()).data('id');
            if(contact_id === undefined) {
                alert("@lang('You must select Contact people to delete')");
                return false;
            }

            if(!confirm("@lang('Are you sure?')"))
                return false;

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.customer.delete.contact')}}' + '/' + contact_id,
            }).done(function (res) { 
                drawContactTable(company_id);
            });
        }

        function newCompany() {
            $('#companyModal .modal-title').text("@lang('Create Company')");
            $('#companyModal #company_id').val(0);
            $('#companyModal #company_name').val("");
            $('#companyModal #company_address').val("");
            $('#companyModal #company_phone').val("");
            $('#companyModal #company_VAT').val("");
            $('#companyModal #company_desc').val("");
            $('#companyModal').modal('show');
        }

        function editCompany() {
            var company_id = $(table1.row({selected: true}).node()).data('id');
            
            if(company_id === undefined) {
                alert("@lang('You must select company to update')");
                return false;
            }
            $('#companyModal .modal-title').text("@lang('Edit Company')");

            var company_data = table1.row({selected: true}).data();            

            $('#companyModal #company_id').val(company_id);
            $('#companyModal #company_name').val(company_data[0]);
            $('#companyModal #company_address').val(company_data[1]);
            $('#companyModal #company_phone').val(company_data[2]);
            $('#companyModal #company_VAT').val(company_data[3]);
            $('#companyModal #company_desc').val(company_data[4]);
            $('#companyModal').modal('show');
        }

        function saveCompany() {
            var flag = true;
            var postData = {};
            $('#companyModal .modal-body input').each(function() {
                const _v = $(this).val().trim();
                if(_v === "") {
                    alert("@lang('You must input all fields.')");
                    $(this).focus();
                    flag = false;
                    return false;
                }
                postData[$(this).attr('name')] = _v;
            });
            if(!flag)
                return false;
            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.customer.save.company')}}',
                data: postData
            }).done(function (res) { 
                $('.modal').modal('hide');
                let data = [postData.company_name,postData.company_address,postData.company_phone,postData.company_VAT,postData.company_desc];
                if(postData.company_id > 0) {
                    table1.row({selected:true}).data(data).draw(false);
                } else {
                    var new_tr = $('<tr data-id="' + res.result.id + '">' + data.map((item) => '<td>' + item + '</td>').join("") + '<tr>');
                    table1.row.add(new_tr).draw(false);
                }
            });
        }

        function deleteCompany() {
            var company_id = $(table1.row({selected: true}).node()).data('id');
            if(company_id === undefined) {
                alert("@lang('You must select company to delete')");
                return false;
            }

            if(!confirm("@lang('Are you sure?')"))
                return false;

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.customer.delete.company')}}',
                data: {id: company_id}
            }).done(function (res) { 
                if(res.result == true) {
                    table1.row('.selected').remove().draw(false);
                }
            });
        }

        $('.modal [data-dismiss]').on('click', function() {
            $(this).closest('.modal').modal('hide');
        });

        function searchCustomer() {
            const keyword = $('#keyword_customer').val().trim();
            table1.search(keyword).draw(false);
        }
        $(document).ready(function(){            
            _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            table1.draw(false);
            table2.draw(false);

            $('#keyword_customer').on('keyup', function(e) {
                if(e.keyCode === 13)
                    searchCustomer();
            })
            
        });
    </script>
@endsection