@extends('layouts.admin')
@section('content')
@can('scooter_list_create')
    <div class="block my-4">
        <a class="btn-md btn-green" href="{{ route('admin.pricemanage.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.pricemanage.title_singular') }}
        </a>
    </div>
@endcan
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
                                @can('scooter_list_show')
                                    <a class="btn-sm btn-indigo" href="{{ route('admin.pricemanage.show', $pricemana->pid) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('scooter_list_edit')
                                    <a class="btn-sm btn-blue" href="{{ route('admin.pricemanage.edit', $pricemana->pid) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('scooter_list_delete')
                                    <form action="{{ route('admin.pricemanage.destroy', $pricemana->pid) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable()
    });

</script>
@endsection