@extends('layouts.admin')
@section('content')
<style>
    
</style>
<div class="main-card projects">
    <div class="body">        
        <div class="row">
            <div class="col-md-3">
                <label for="keyword_project" class="text-xs">@lang('Find a project')</label>
                <div class="form-group">
                    <div class="input-group">
                        <input type="search" id="keyword_project" name="keyword_project" class="form-control" placeholder="@lang('Project name, reference, description...')">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button" onclick="searchProject()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="text-xs">@lang('Statuts filter')</label>
                <div class="form-group">
                    <label class="label-chb"><input type="checkbox" name="p_status" value="in progress"/>@lang('In progress')</label>
                    <label class="label-chb"><input type="checkbox" name="p_status" value="won"/>@lang('Won')</label>
                    <label class="label-chb"><input type="checkbox" name="p_status" value="lost"/>@lang('Lost')</label>
                </div>
            </div>
        </div>
        
        <div class="w-full">
            <table class="display compact project-table datatable-project">
                <thead>
                    <tr>
                        <th>@lang('Statuts')</th>
                        <th>@lang('Customer')</th>
                        <th>@lang('Contact')</th>
                        <th>@lang('Project Name')</th>
                        <th>@lang('Reference')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Modification Date')</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                    
                    if(isset($project_list) && is_array($project_list)) {
                        $arr_status = [
                            ['text' => "@lang('In progress')", 'class' => 'primary'],
                            ['text' => "@lang('Won')", 'class' => 'success'],
                            ['text' => "@lang('Lost')", 'class' => 'danger'],
                        ];
                        
                        foreach($project_list as $p) {
                            ?>
                            <tr data-id="{{$p->id}}" data-company="{{$p->company}}" data-contact="{{$p->contact}}">
                                <td class="nowrap" data-value="{{$arr_status[$p->status]['text']}}">
                                    <select class="form-control" onchange="onProjectStatusChange({{$p->id}}, this)">
                                        <option value="0" {{$p->status == 0 ? "selected" : ""}}>@lang('In progress')</option>
                                        <option value="1" {{$p->status == 1 ? "selected" : ""}}>@lang('Won')</option>
                                        <option value="2" {{$p->status == 2 ? "selected" : ""}}>@lang('Lost')</option>
                                    </select>                                    
                                </td>
                                <td class="nowrap">{{$p->customer}}</td>
                                <td class="nowrap">Mr. {{$p->contact_name}}</td>
                                <td class="nowrap">{{$p->project_name}}</td>
                                <td class="nowrap">{{$p->reference}}</td>
                                <td class="nowrap">{{$p->description}}</td>
                                <td class="nowrap">{{date("m/d/Y", strtotime($p->updated_at))}}</td>
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
@endsection
@section('scripts')
    @parent
    <script>
        var dTable;
        $(function () {
            $.extend(true, $.fn.dataTable.defaults, {
                dom:'tp',
                orderCellsTop: true,
                order: [[ 1, 'desc' ]],
                pageLength: 10,
                // responsive: true,
                select: {
                    style: 'single', // or 'multi',
                    selector: 'td:not(:first-child)'
                },
                rowCallback: function(row, data) {
                    $(row).on('dblclick', function() {
                        var selectedRow = dTable.row('.selected');
                        if (selectedRow.any()) {  // check if any row is currently selected
                            selectedRow.deselect();  // deselect the selected row
                        }
                        $(row).toggleClass('selected');

                        const pid = $(row).data('id');
                        const cid = $(row).data('company');
                        const uid = $(row).data('contact');
                        location.href = '{{route('admin.projects.detail')}}/' + pid + '/' + cid + '/' + uid + '?o=readonly';
                    });
                }
            });

            // Add custom search function
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {                    
                    var searchValue1 = $('#keyword_project').val().toLowerCase();
                    
                    let selected_status = [];
                    $('input[name=p_status]:checked').each(function(){selected_status.push($(this).val())});
                    if(selected_status.length > 0)  {
                        var row_status = $(dTable.row(dataIndex).node()).find('td:first-child').attr('data-value');
                        row_status = row_status.slice(7, -2);
                        if(selected_status.filter(item => item.toLowerCase() === row_status.toLowerCase()).length < 1)
                            return false;
                    }                       
                    
                    // Perform search by other columns with other value
                    if (data.slice(1).join(' ').toLowerCase().indexOf(searchValue1) === -1) {
                        return false;
                    }

                    return true;
                }
            );
            dTable = $('.datatable-project').DataTable();
        });

        function modify() {
            var selected_tr = dTable.row('.selected').node();
            console.log(selected_tr);
            if(selected_tr === null){
                return false;
            }
            var pid = $(selected_tr).data('id');
            var cid = $(selected_tr).data('company');
            var uid = $(selected_tr).data('contact');

            // console.log(pid, cid, uid);
            location.href = '{{route('admin.projects.profile')}}/' + pid + '/' + cid + '/' + uid;
        }

        function del() {
            var selected_tr = dTable.row('.selected').node();
            if(selected_tr === null)
                return false;
            if(confirm("@lang('Are you sure?')")) {
                var pid = $(selected_tr).data('id');
                $.ajax({
                    method: 'POST',
                    url: '{{route('admin.projects.delete.project')}}',
                    headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                    data: {id: pid}
                }).done(function (res) {
                    if(res.result == true)                
                        dTable.row('.selected').remove().draw(false);
                });
            }            
        }

        function duplicate() {
            var selected_tr = dTable.row('.selected').node();
            if(selected_tr === undefined)
                return false;

            var pid = $(selected_tr).data('id');
            $.ajax({
                method: 'POST',
                url: '{{route('admin.projects.duplicate.project')}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: pid}
            }).done(function (res) {
                if(res.result > 0) {
                    // dTable.row('.selected').remove().draw(false);
                    var cloned_tr = $(selected_tr).clone();
                    cloned_tr.attr('data-id', res.result);
                    cloned_tr.removeClass('selected');
                    dTable.row.add(cloned_tr);
                    dTable.draw(false);
                }   
            });
        }

        function searchProject() {
            dTable.draw(false);
        }

        function onProjectStatusChange(pid, obj) {
            $.ajax({
                method: 'POST',
                url: '{{route('admin.projects.status.change')}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: pid, status: obj.value}
            }).done(function(res) {
                $(obj).closest('td').attr('data-value', $(obj).find('option:selected').text());
            });
        }

        $('#keyword_project').on('keyup', function(e) {
            if(e.keyCode === 13) {
                dTable.draw(false);
            }
        });

        $('input[name=p_status]').on('change', function() {
            dTable.draw(false);
        });
    </script>
@endsection