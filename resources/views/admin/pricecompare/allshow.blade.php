@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.price.title_singular') }} Competitor
    </div>
    
    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div id="scooterItemTable">
            <table class="striped bordered show-table">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.id') }}
                        </th>
                        <td>
                            {{ $price->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.itemcode') }}
                        </th>
                        <td>
                            {{ $price->itemcode }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.desc') }}
                        </th>
                        <td>
                            {{ $price->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.price') }}
                        </th>
                        <td>
                            {{ number_format($price->price, 2) }} €
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-3">
            
            </div>
        </div>

        <div class="mb-10"></div>
        
        <div class="mb-3">
            <input type="hidden" id="id" name="id" value="{{ $price->id }}" required />
            <input type="hidden" id="pid" name="pid" value="{{ $pid }}" required />
        </div>

        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th>
                            COMPETITOR NAME
                        </th>
                        <th>
                            {{ trans('cruds.price.fields.desc') }}
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
                    @foreach($pricecompares as $key => $pricecompare)
                        <tr data-entry-id="{{ $pricecompare->id }}">
                            <td align="center">
                                {{ $pricecompare->name ?? ''}}
                            </td>
                            <td align="center">
                                {{ $pricecompare->description ?? '' }}
                            </td>
                            <td align="center">
                                {{ number_format($pricecompare->price, 2) ?? '' }} €
                            </td>
                            <td align="center">
                                <a class="btn-sm btn-blue" href="{{ route('admin.pricecompare.edit', [$pid, $pricecompare->id]) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                <form action="{{ route('admin.pricecompare.destroy', $pricecompare->id) }}" method="GET" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="cid" value="{{ $pricecompare->id }}" />
                                    <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                </form>
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