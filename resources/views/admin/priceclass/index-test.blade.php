@if(in_array('scooter_create', $role) && isset($role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.pricing.title_singular') }}
        </a>
    </div>
@endif
<div class="main-card">
    <div class="header">
        {{ trans('cruds.pricing.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.pricing.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricing.fields.desc') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricing.fields.multiplier') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($priceclasses as $key => $priceclass)
                        <tr data-entry-id="{{ $priceclass->id }}">
                            <td align="center">
                                {{ $priceclass->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $priceclass->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ $priceclass->multiplier ?? '' }}
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
    });
</script>