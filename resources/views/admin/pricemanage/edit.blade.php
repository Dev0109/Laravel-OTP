@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} {{ trans('cruds.pricing.title_singular') }}
    </div>

    <form method="POST" action="{{ route('admin.pricemanage.update', [$pricemanage->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="price_id" class="text-xs required">{{ trans('cruds.pricemanage.fields.priceclass') }}</label>

                <div class="form-group">
                    <select name="price_id" name="price_id" class="{{ $errors->has('price_id') ? ' is-invalid' : '' }}" required>
                        <option value="">Choose Item</option>
                        @foreach($prices as $key => $price)
                            @if($price->id == $pricemanage->price_id)
                                <option value="{{ $price->id }}" selected>
                                    {{ $price->itemcode }} ({{ number_format($price->price, 2) }} €)
                                </option>
                            @else
                                <option value="{{ $price->id }}">
                                    {{ $price->itemcode }} ({{ number_format($price->price, 2) }} €)
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @if($errors->has('price_id'))
                    <p class="invalid-feedback">{{ $errors->first('price_id') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricemanage.fields.priceclass_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection