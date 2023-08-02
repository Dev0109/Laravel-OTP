@extends('layouts.admin')
@section('content')
@can('scooter_create')
    <div class="block my-4">
        <a class="btn-md btn-green" href="{{ route('admin.pricecompetitor.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.pricecompetitor.title_singular') }}
        </a>
    </div>
@endcan
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
                                <a href="{{$pricetype->website}}" target="_blank">{{$pricetype->website}}</a>
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
                                @can('scooter_show')
                                    <a class="btn-sm btn-indigo" href="{{ route('admin.pricecompetitor.show', $pricetype->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('scooter_edit')
                                    <a class="btn-sm btn-blue" href="{{ route('admin.pricecompetitor.edit', $pricetype->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('scooter_delete')
                                    <form action="{{ route('admin.pricecompetitor.destroy', $pricetype->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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