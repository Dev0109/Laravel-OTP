@extends('layouts.admin')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<style>
    table .price-image {
        max-width: 80px;
        max-height: 80px;
    }
</style>
@can('scooter_create')
    <div class="block my-4">
        @can('price_add')
        <a class="btn-md btn-green" href="{{ route('admin.scooters.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.price.title_singular') }}
        </a>
        @endcan
        @can('price_import')
        <a class="btn-md btn-blue" href="{{ route('admin.scooters-excel') }}">
            Import Excel
        </a>
        @endcan
        @can('price_export')
        <a class="btn-md btn-success" href="#" onclick="export2Excel()">
            Export Excel
        </a>
        @endcan
        @can('price_delete')
        <a class="btn-md btn-danger" href="#" onclick="deleteAll()">
            Delete All Price
        </a>
        @endcan
    </div>
@endcan
<div class="main-card">
    <div class="header">
        {{$price_type->name ?? trans('cruds.price.title_singular') . ' ' . trans('global.list')}}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter" style="display: none;">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.image') }}
                        </th>
                        @if(request()->is('admin/scooters'))
                            <th>
                                {{ trans('cruds.price.fields.typename') }}
                            </th>
                        @endif
                        <th>
                            {{ trans('cruds.price.fields.itemcode') }}
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.primary_desc') }}
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.second_desc') }}
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.price') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prices as $key => $price)
                        <tr data-entry-id="{{ $price->id }}">
                            <td align="center">
                                <img src="{{asset('/uploads/price/' . ($price->image ?? 'default_price.jpg')) }}" class="price-image"/>
                            </td>
                            @if(request()->is('admin/scooters'))
                                <td align="center">
                                    {{ $price->name ?? '' }}
                                </td>
                            @endif
                            <td align="center">
                                {{ $price->itemcode ?? '' }} <input type="hidden" value="{{ $price->id_model ?? '' }}">
                            </td>
                            <td align="center">
                                {{ $price->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ $price->description2 ?? '' }}
                            </td>
                            @if (isset($price_multiplier))
                            <td align="center" data-price="{{ number_format($price->price * $price_multiplier, 2) ?? '' }}">
                                {{ number_format($price->price * $price_multiplier * $multiplier, 2) ?? '' }} €
                            </td>
                            @else
                            <td align="center" data-price="{{ number_format($price->price * $price->multiplier, 2) ?? '' }}">
                                {{ number_format($price->price * $price->multiplier * $multiplier, 2) ?? '' }} €
                            </td>
                            @endif
                            <td align="center">
                                @can('scooter_show')
                                    <a class="btn-sm btn-indigo" href="{{ route('admin.scooters.show', $price->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('scooter_edit')
                                    <a class="btn-sm btn-blue" href="{{ route('admin.scooters.edit', $price->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('scooter_delete')
                                    <form action="{{ route('admin.scooters.destroy', $price->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
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
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable();
        $('.datatable-Scooter:not(.ajaxTable)').show();
        
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
    function export2Excel() {
        var tbl = $('.datatable-Scooter:not(.ajaxTable)').DataTable();
        var rows = tbl.rows().data();
        var data = [];
        rows.each(function(row, index) {
            var rd = {
                // '{{ trans('cruds.price.fields.id') }}' : row[0]
            };
            var image = $(row[0]).attr('src').replace('{{asset('/uploads/price/')}}/', '');            
            var regex = /[^0-9.]/g;
            @if(!request()->is('admin/scooter*'))           
            var price = parseFloat(row[5].replace(regex, ''));
            var pos = row[2].indexOf(' ');
            var temp1 = row[2].substr(0, pos + 1);
            pos = row[2].indexOf('value="');
            var temp2 = row[2].substr(pos);
            pos = temp2.indexOf('">');
            temp2 = temp2.substring(7, pos);
            rd['{{ trans('cruds.price.fields.typename') }}'] = row[1];
            rd['{{ trans('cruds.price.fields.itemcode') }}'] = temp1;
            rd['{{ trans('cruds.price.fields.primary_desc') }}'] = row[3];
            rd['{{ trans('cruds.price.fields.second_desc') }}'] = row[4];
            rd['{{ trans('cruds.price.fields.price') }}'] = price;
            rd['{{ trans('cruds.price.fields.image') }}'] = image;
            rd['{{ trans('cruds.price.fields.id_model') }}'] = temp2;
            @else
            var price = parseFloat(row[4].replace(regex, ''));
            var pos = row[1].indexOf(' ');
            var temp1 = row[1].substr(0, pos + 1);
            pos = row[1].indexOf('value="');
            var temp2 = row[1].substr(pos);
            pos = temp2.indexOf('">');
            temp2 = temp2.substring(7, pos);
            rd['{{ trans('cruds.price.fields.itemcode') }}'] = temp1;
            rd['{{ trans('cruds.price.fields.primary_desc') }}'] = row[2];
            rd['{{ trans('cruds.price.fields.second_desc') }}'] = row[3];
            rd['{{ trans('cruds.price.fields.price') }}'] = price;
            rd['{{ trans('cruds.price.fields.image') }}'] = image;
            rd['{{ trans('cruds.price.fields.id_model') }}'] = temp2;
            @endif
            data.push(rd);
        });        
        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.json_to_sheet(data);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Price');
        XLSX.writeFile(workbook, 'price_' + (new Date().getTime()) + '.xlsx');
    }
    function deleteAll() {
        if(confirm("Are you sure to delete all?")) {
            var tbl = $('.datatable-Scooter:not(.ajaxTable)').DataTable();
            $.ajax({
                method: 'POST',
                url: '{{route('admin.scooters.deleteAll')}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: '{{$id ?? "0"}}'}
            }).done(function (res) {
                if(res.result > 0)                
                    tbl.clear().draw();
            });  
        }
    }
</script>
@endsection