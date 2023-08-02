@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} Competitor
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.pricecompetitor.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div id="scooterItemTable">
            <table class="striped bordered show-table">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.id') }}
                        </th>
                        <td>
                            {{ $pricetype->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.name') }}
                        </th>
                        <td>
                            {{ $pricetype->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.pricing_class') }}
                        </th>
                        <td>
                            {{ $pricetype->pricelink }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.website') }}
                        </th>
                        <td>
                            @if($pricetype->website)
                                <a href="{{$pricetype->website}}" target="_blank">{{$pricetype->website}}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.username') }}
                        </th>
                        <td>
                            {{ $pricetype->username }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.userpwd') }}
                        </th>
                        <td>
                            {{ $pricetype->userpwd }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.pricelist') }}
                        </th>
                        <td>
                            @if($pricetype->pricelist)
                                <a href="{{asset('/uploads/' . $pricetype->pricelist)}}" target="_blank"><i class="fa fa-file-pdf"></i> {{$pricetype->pricelist}}</a>
                            @endif 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricecompetitor.fields.datasheet') }}
                        </th>
                        <td>
                            @if($pricetype->datasheet)
                                <a href="{{asset('/uploads/' . $pricetype->datasheet)}}" target="_blank"><i class="fa fa-file-excel"></i> {{$pricetype->datasheet}}</a>
                            @endif 
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-3">
               
            </div>
        </div>

        @can('scooter_edit')
            <div class="block pt-4">
                <a class="btn-md btn-green" href="{{ route('admin.pricecompetitor.edit', $pricetype->id) }}">{{ trans('global.edit') }}</a>
            </div>
        @endcan
    </div>
</div>
@endsection
