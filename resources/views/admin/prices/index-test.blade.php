<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<style>
    table .price-image {
        max-width: 80px;
        max-height: 80px;
    }
</style>
@if(in_array('scooter_create', $role) && isset($role))
    <div class="block my-4">
        @if(in_array('price_add', $role) && isset($role))
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.price.title_singular') }}
        </a>
        @endif
        @if(in_array('price_import', $role) && isset($role))
        <a class="btn-md btn-blue" href="#">
            Import Excel
        </a>
        @endif
        @if(in_array('price_export', $role) && isset($role))
        <a class="btn-md btn-success" href="#">
            Export Excel
        </a>
        @endif
        @if(in_array('price_delete', $role) && isset($role))
        <a class="btn-md btn-danger" href="#">
            Delete All Price
        </a>
        @endif
    </div>
@endif
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
                        @if(!isset($id))
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
                            @if(!isset($id))
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
</script>