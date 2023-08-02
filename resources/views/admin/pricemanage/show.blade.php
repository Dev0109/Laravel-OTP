@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} {{ trans('cruds.pricemanage.title') }}
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.pricemanage.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div id="scooterItemTable">
            <table class="striped bordered show-table">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pricemanage.fields.id') }}
                        </th>
                        <td>
                            {{ $pricemanage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricemanage.fields.itemcode') }}
                        </th>
                        <td>
                            @foreach($prices as $key => $price)
                                @if($price->id == $pricemanage->price_id)
                                    {{ $price->itemcode }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricemanage.fields.desc') }}
                        </th>
                        <td>
                            @foreach($prices as $key => $price)
                                @if($price->id == $pricemanage->price_id)
                                    {{ $price->description }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricemanage.fields.price') }}
                        </th>
                        <td>
                            @foreach($prices as $key => $price)
                                @if($price->id == $pricemanage->price_id)
                                    {{ number_format($price->price, 2) }} €
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @foreach($pricings as $key => $pricing)
                        <tr>
                            <th>
                                {{ $pricing->description }} ({{ $pricing->multiplier }})
                            </th>
                            <td>
                                @foreach($prices as $key => $price)
                                    @if($price->id == $pricemanage->price_id)
                                        {{ number_format((float)$price->price * (float)$pricing->multiplier, 2) }} €
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mb-3">
               
            </div>
        </div>

        @can('scooter_list_edit')
        <div class="block pt-4">
            <a class="btn-md btn-green" href="{{ route('admin.pricemanage.edit', $pricemanage->id) }}">{{ trans('global.edit') }}</a>
        </div>
        @endcan
    </div>
</div>
@endsection
