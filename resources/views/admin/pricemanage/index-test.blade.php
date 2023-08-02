@if(in_array('scooter_list_create', $role) && isset($role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.pricemanage.title_singular') }}
        </a>
    </div>
@endif
<div class="main-card">
    <div class="header">
        {{ trans('cruds.pricemanage.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter" style="overflow-x: scroll; display: block;">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.pricemanage.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricemanage.fields.itemcode') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricemanage.fields.desc') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricemanage.fields.price') }}
                        </th>
                        @foreach($pricelist as $key => $priceitem)
                            <th>
                                {{ $priceitem->description }} ({{ $priceitem->multiplier }})
                            </th>
                        @endforeach
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricemanas as $key => $pricemana)
                        <tr data-entry-id="{{ $pricemana->pid }}">
                            <td align="center">
                                {{ $pricemana->pid ?? '' }}
                            </td>
                            <td align="center">
                                {{ $pricemana->itemcode ?? '' }}
                            </td>
                            <td align="center">
                                {{ $pricemana->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ number_format($pricemana->price, 2) ?? '' }} €
                            </td>
                            @foreach($pricelist as $key => $pricelists)
                                <td align="center">
                                    {{ number_format((float)$pricelists->multiplier * (float)$pricemana->price, 2) }} €
                                </td>
                            @endforeach
                            <td>
                                @if(in_array('scooter_list_show', $role) && isset($role))
                                    <a class="btn-sm btn-indigo" href="#">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif
                                @if(in_array('scooter_list_edit', $role) && isset($role))
                                    <a class="btn-sm btn-blue" href="#">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif
                                @if(in_array('scooter_list_delete', $role) && isset($role))
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