@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} {{ trans('cruds.price.title_singular') }}
    </div>

    

    <form method="POST" action="{{ route('admin.pricecompare.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="block pb-4">
                <a class="btn-md btn-gray" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            
            <div>
                <input type="hidden" id="id" name="id" value="{{ $pricecompare->id }}" />
                <input type="hidden" id="pid" name="pid" value="{{ $pid }}" />
            </div>
            <div class="mb-3">
                <label for="description" class="text-xs required">{{ trans('cruds.price.fields.desc') }}</label>

                <div class="form-group">
                    <input type="text" id="description" name="description" class="{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description', $pricecompare->description) }}" required>
                </div>
                @if($errors->has('description'))
                    <p class="invalid-feedback">{{ $errors->first('description') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.desc_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="price" class="text-xs required">{{ trans('cruds.price.fields.price') }}</label>

                <div class="form-group">
                    <input type="text" id="price" name="price" class="{{ $errors->has('price') ? ' is-invalid' : '' }}" value="{{ old('price', $pricecompare->price) }}" required>
                </div>
                @if($errors->has('price'))
                    <p class="invalid-feedback">{{ $errors->first('price') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.price_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection