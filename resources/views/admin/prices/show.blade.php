@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} {{ trans('cruds.price.title') }}
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.scooters.index') }}">
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
                            {{ trans('cruds.price.fields.desc') }}2
                        </th>
                        <td>
                            {{ $price->description2 }}
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
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.id_model') }}
                        </th>
                        <td>
                            {{ $price->id_model }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.typename') }}
                        </th>
                        <td>
                            @foreach($pricetypes as $key => $pricetype)
                                @if($pricetype->id == $price->pricetype_id)
                                    {{ $pricetype->name }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.price.fields.image') }}
                        </th>
                        <td>
                            <img src="{{asset('/uploads/price/' . ($price->image ?? 'default_price.jpg')) }}" width="300px"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-3">
               
            </div>
        </div>

        @can('scooter_edit')
            <div class="block pt-4">
                <a class="btn-md btn-green" href="{{ route('admin.scooters.edit', $price->id) }}">{{ trans('global.edit') }}</a>
            </div>
        @endcan
    </div>
</div>
@endsection
