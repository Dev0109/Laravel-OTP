@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.pricing.title_singular') }}
    </div>

    <form method="POST" action="{{ route('admin.pricings.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="body">

            <div class="mb-3">
                <label for="description" class="text-xs required">{{ trans('cruds.pricing.fields.desc') }}</label>

                <div class="form-group">
                    <input type="text" id="description" name="description" class="{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') }}" required>
                </div>
                @if($errors->has('description'))
                    <p class="invalid-feedback">{{ $errors->first('description') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricing.fields.desc_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="multiplier" class="text-xs required">{{ trans('cruds.pricing.fields.multiplier') }}</label>

                <div class="form-group">
                    <input type="text" id="multiplier" name="multiplier" class="{{ $errors->has('multiplier') ? ' is-invalid' : '' }}" value="{{ old('multiplier') }}" required>
                </div>
                @if($errors->has('multiplier'))
                    <p class="invalid-feedback">{{ $errors->first('multiplier') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricing.fields.multiplier_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection
