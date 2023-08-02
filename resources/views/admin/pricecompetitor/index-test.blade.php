@if(in_array('scooter_create', $role) && isset($role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.pricecompetitor.title_singular') }}
        </a>
    </div>
@endif
<div class="main-card">
    <div class="header">
    {{ trans('cruds.pricecompetitor.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter" style="overflow-x: scroll; display: block;">
                <thead>
                    <tr>
                        <th rowspan="2">
                            {{ trans('cruds.pricecompetitor.fields.id') }}
                        </th>
                        <th rowspan="2">
                            {{ trans('cruds.pricecompetitor.fields.name') }}
                        </th>
                        <th rowspan="2">
                            {{ trans('cruds.pricecompetitor.fields.pricing_class') }}
                        </th>
                        <th rowspan="2">
                            {{ trans('cruds.pricecompetitor.fields.website') }}
                        </th>
                        <th rowspan="2">
                            {{ trans('cruds.pricecompetitor.fields.username') }}
                        </th>
                        <th rowspan="2">
                            {{ trans('cruds.pricecompetitor.fields.userpwd') }}
                        </th>
                        <th colspan="2" class="text-center">
                            {{ trans('cruds.pricecompetitor.fields.reference') }}
                        </th>
                        <th rowspan="2">
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.pricelist') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.datasheet') }}
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
                                {{ $pricetype->pricelink ?? '' }}
                            </td>
                            <td align="center">
                                @if($pricetype->website)
                                <a href="#">{{$pricetype->website}}</a>
                                @endif                                
                            </td>
                            <td align="center">
                                {{ $pricetype->username ?? '' }}
                            </td>
                            <td align="center">
                                {{ $pricetype->userpwd ?? '' }}
                            </td>
                            <td align="center">
                                @if($pricetype->pricelist)
                                <a href="{{asset('/uploads/' . $pricetype->pricelist)}}"  target="_blank"><i class="fa fa-file-pdf"></i> {{$pricetype->pricelist}}</a>
                                @endif                                
                            </td>
                            <td align="center">
                                @if($pricetype->datasheet)
                                <a href="{{asset('/uploads/' . $pricetype->datasheet)}}"  target="_blank"><i class="fa fa-file-excel"></i> {{$pricetype->datasheet}}</a>
                                @endif                                
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