@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.price.import') }}
    </div>

    <form method="POST" action="{{ route('admin.scooters-import') }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="block pb-4">
                <a class="btn-md btn-gray" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>

            <div class="mb-3">
                <label for="file" class="text-xs required">{{ trans('cruds.price.fields.excel') }}</label>

                <div class="form-group">
                    <input type="file" id="file" name="file" class="{{ $errors->has('file') ? ' is-invalid' : '' }}" value="{{ old('file') }}" required>
                </div>
                @if($errors->has('file'))
                    <p class="invalid-feedback">{{ $errors->first('file') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.excel_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="pricetype_id" class="text-xs required">{{ trans('cruds.price.fields.typename') }}</label>

                <div class="form-group">
                    <select name="pricetype_id" name="pricetype_id" class="{{ $errors->has('pricetype_id') ? ' is-invalid' : '' }}" required>
                        <option value="">Choose Item</option>
                        @foreach($pricetypes as $key => $pricetype)
                            <option value="{{ $pricetype->id }}">
                                {{ $pricetype->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($errors->has('pricetype_id'))
                    <p class="invalid-feedback">{{ $errors->first('pricetype_id') }}</p>
                @endif
                <span class="block">{{ trans('cruds.price.fields.typename_helper') }}</span>
            </div>

            <div class="block pt-4">
                <a class="btn-md btn-gray" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection
