@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} {{ trans('cruds.price.title_singular') }}
    </div>

    <form method="POST" action="{{ route('admin.scooters.update', [$price->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="itemcode" class="text-xs required">{{ trans('cruds.price.fields.itemcode') }}</label>

                <div class="form-group">
                    <input type="text" id="itemcode" name="itemcode" class="{{ $errors->has('itemcode') ? ' is-invalid' : '' }} form-control" value="{{ old('itemcode', $price->itemcode) }}" required>
                </div>
                @if($errors->has('itemcode'))
                    <p class="invalid-feedback">{{ $errors->first('itemcode') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.itemcode_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="description" class="text-xs required">{{ trans('cruds.price.fields.desc') }}</label>

                <div class="form-group">
                    <input type="text" id="description" name="description" class="{{ $errors->has('description') ? ' is-invalid' : '' }} form-control" value="{{ old('description', $price->description) }}" required>
                </div>
                @if($errors->has('description'))
                    <p class="invalid-feedback">{{ $errors->first('description') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.desc_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="description2" class="text-xs">{{ trans('cruds.price.fields.desc') }}2</label>

                <div class="form-group">
                    <input type="text" id="description2" name="description2" class="{{ $errors->has('description2') ? ' is-invalid' : '' }} form-control" value="{{ old('description2', $price->description2) }}">
                </div>
                @if($errors->has('description2'))
                    <p class="invalid-feedback">{{ $errors->first('description2') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.desc_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="price" class="text-xs required">{{ trans('cruds.price.fields.price') }}</label>

                <div class="form-group">
                    <input type="text" pattern="[0-9]+(\.[0-9]{0,2})?" id="price" name="price" class="{{ $errors->has('price') ? ' is-invalid' : '' }} form-control" value="{{ old('price', $price->price) }}" required>
                </div>
                @if($errors->has('price'))
                    <p class="invalid-feedback">{{ $errors->first('price') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.price_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="id_model" class="text-xs required">{{ trans('cruds.price.fields.id_model') }}</label>

                <div class="form-group">
                    <input type="text" pattern="[0-9]+" id="id_model" name="id_model" class="{{ $errors->has('price') ? ' is-invalid' : '' }} form-control" value="{{ old('id_model', $price->id_model) }}">
                </div>
                @if($errors->has('id_model'))
                    <p class="invalid-feedback">{{ $errors->first('id_model') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.id_model_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="pricetype_id" class="text-xs required">{{ trans('cruds.price.fields.typename') }}</label>

                <div class="form-group">
                    <select name="pricetype_id" name="pricetype_id" class="{{ $errors->has('pricetype_id') ? ' is-invalid' : '' }}" required>
                        <option value="">Choose Item</option>
                        @foreach($pricetypes as $key => $pricetype)
                            @if($pricetype->id == $price->pricetype_id)
                                <option value="{{ $pricetype->id }}" selected>
                                    {{ $pricetype->name }}
                                </option>
                            @else
                                <option value="{{ $pricetype->id }}">
                                    {{ $pricetype->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @if($errors->has('pricetype_id'))
                    <p class="invalid-feedback">{{ $errors->first('pricetype_id') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.typename_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="image" class="text-xs">{{ trans('cruds.price.fields.image') }}</label>

                <div class="form-group">
                    <img src="{{asset('/uploads/price/' . ($price->image ?? 'default_price.jpg')) }}" width="100px"/>
                    <input type="file" id="image" name="image" class="form-control"/>
                </div>
                @if($errors->has('image'))
                    <p class="invalid-feedback">{{ $errors->first('image') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.desc_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection