@if(in_array('scooter_create', $role) && isset($role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.pricetype.title_singular') }}
        </a>
    </div>
@endif
<div class="main-card">
    <div class="header">
        {{ trans('cruds.pricetype.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.pricetype.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricetype.fields.name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricetypes as $key => $pricetype)
                        <tr data-entry-id="{{ $pricetype->id }}">
                            <td align="center">
                                {{ $pricetype->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $pricetype->name ?? '' }}
                            </td>
                            <td align="center">
                                @if(in_array('scooter_show', $role) && isset($role))
                                    <a class="btn-sm btn-indigo" href="#">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif
                                @if(in_array('scooter_edit', $role) && isset($role))
                                    <a class="btn-sm btn-blue" href="#">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif
                                @if(in_array('scooter_delete', $role) && isset($role))
                                    <a class="btn-sm btn-red" href="#">
                                        {{ trans('global.delete') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable()
        $("#selectedFile").change(function () {
            if (confirm('Are you sure ?')) {
                $("#import_form").submit()
            }
        });
    });

</script>