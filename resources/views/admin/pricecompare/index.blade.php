@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('cruds.price.title_singular') }} {{ trans('global.list') }} Comparation
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.id') }}
                        </th>
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
                                {{ $price->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $price->itemcode ?? '' }}
                            </td>
                            <td align="center">
                                {{ $price->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ $price->description2 ?? '' }}
                            </td>
                            <td align="center">
                                {{ number_format($price->price, 2) ?? '' }} €
                            </td>
                            <td align="center">
                                <a class="btn-sm btn-green" href="{{ route('admin.pricecompare.create', [$id, $price->id]) }}">
                                    {{ trans('global.add') }} Comp
                                </a>
                                <a class="btn-sm btn-indigo" href="{{ route('admin.pricecompare.show', [$id, $price->id]) }}">
                                    {{ trans('global.view') }}
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
    $(function () {
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
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