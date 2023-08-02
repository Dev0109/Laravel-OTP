<div class="main-card projects">
    <div class="body">        
        <button type="button" class="btn btn-primary">@lang('Add new')</button>

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
                                <a class="btn btn-sm btn-success">
                                    @lang('Edit')
                                </a>                                
                                <a class="btn btn-sm btn-danger">
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
<script>
    let dTable;
    
    $.extend(true, $.fn.dataTable.defaults, {
        orderCellsTop: true,
        order: [[ 0, 'asc' ]],
        pageLength: 10,
    });
    dTable = $('.datatable-job:not(.ajaxTable)').DataTable();
</script>