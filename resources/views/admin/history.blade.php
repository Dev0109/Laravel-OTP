@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        Action History
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter" style="display: none;">
                <thead>
                    <tr>
                        <th>
                            Email
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            TABLE Name
                        </th>
                        <th>
                            ACTION
                        </th>
                        <th>
                            DATE/TIME
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loghistories as $key => $history)
                        <tr data-entry-id="{{ $history->id }}">
                            <td align="center">
                                {{ $history->email ?? '' }}
                            </td>
                            <td align="center">
                                {{ $history->name ?? '' }}
                            </td>
                            <td align="center">
                                {{ $history->table_name ?? '' }}
                            </td>
                            <td align="center">
                                {{ $history->action ?? '' }}
                            </td>
                            <td align="center">
                                {{ date_format(date_create($history->created_at), "Y/m/d H:i:s") ?? '' }}
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
    $(function () {
        $('.datatable').show();
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 4, 'desc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable()
        
        
        $(".import_btn").click(function (e) {
            e.preventDefault();
            $('#selectedFile').click();
        });
        $("#selectedFile").change(function () {
            if (confirm('Are you sure ?')) {
                $("#import_form").submit()
            }
        });
    });

</script>
@endsection