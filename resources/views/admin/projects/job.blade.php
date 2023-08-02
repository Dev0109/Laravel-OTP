@extends('layouts.admin')
@section('content')
<div class="main-card projects">
    <div class="body">        
        <button type="button" class="btn btn-primary" onclick="addNew()">@lang('Add new')</button>

        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-job text-center">
                <thead>
                    <tr>
                        <th>@lang('ID')</th>
                        <th>@lang('NAME')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($job_list as $key => $job)
                        <tr data-id="{{ $job->id }}">
                            <td>{{ $job->id ?? '' }}</td>
                            <td>{{ $job->name ?? '' }}</td>
                            <td>
                                <a class="btn btn-sm btn-success" onclick="editJob(this)">
                                    @lang('Edit')
                                </a>                                
                                <a class="btn btn-sm btn-danger" onclick="deleteJob(this)">
                                    @lang('Delete')
                                </a>                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    @parent
    <script>
        let dTable;
        $(document).ready(function() {
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[ 0, 'asc' ]],
                pageLength: 10,
            });
            dTable = $('.datatable-job:not(.ajaxTable)').DataTable();
        });

        function addNew() {
            let job_name = prompt("@lang('Please type new job position')").trim();
            if(job_name !== "") {
                $.ajax({
                    method: 'POST',
                    url: '{{route('admin.projects.store.job')}}',
                    headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                    data: {id: 0, name: job_name}
                }).done(function (res) {                    
                    var newRowHtml = '<tr data-id="' + res.result.id + '">' +
                                        '<td>' + res.result.id + '</td>' +
                                        '<td>' + res.result.name + '</td>' +
                                        '<td>' + 
                                            '<a class="btn btn-sm btn-success" onclick="editJob(this)">@lang("Edit")</a>&nbsp;' +
                                            '<a class="btn btn-sm btn-danger" onclick="deleteJob(this)">@lang("Delete")</a>' +
                                        '</td>' +
                                    '</tr>';

                    dTable.row.add($(newRowHtml)).draw(false);
                });
            }
        }

        function editJob(obj) {            
            var id = $(obj).closest('tr').data('id');
            var old_name = $(obj).closest('tr').find('td:nth-child(2)').text();
            var new_name = prompt("@lang('Please type job position to change')", old_name).trim();
            if(new_name !== "" && new_name !== old_name) {
                $.ajax({
                    method: 'POST',
                    url: '{{route('admin.projects.store.job')}}',
                    headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                    data: {id: id, name: new_name}
                }).done(function (res) {                    
                    $(obj).closest('tr').find('td:nth-child(2)').text(new_name);
                });
            }
        }

        function deleteJob(obj) {
            if(confirm("@lang('Are you sure to delete?')")) {
                var id = $(obj).closest('tr').data('id'); 
                $.ajax({
                    method: 'POST',
                    url: '{{route('admin.projects.delete.job')}}',
                    headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                    data: {id: id}
                }).done(function (res) {                    
                    dTable.row('[data-id=' + id + ']').remove().draw(false);
                });
            }            
        }
    </script>
@endsection