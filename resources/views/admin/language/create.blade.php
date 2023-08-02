@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        @lang('Create New Language')
    </div>

    <form method="POST" action="{{ route('admin.language.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="name" class="text-xs required">@lang('Language Name')</label>

                <div class="form-group">
                    <input type="text" id="name" name="name" class="{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" required>
                </div>
                @if($errors->has('name'))
                    <p class="invalid-feedback">{{ $errors->first('name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricetype.fields.name_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="code" class="text-xs required">@lang('Language Code')</label>

                <div class="form-group">
                    <input type="text" id="code" name="code" class="{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('code') }}" required>
                </div>
                @if($errors->has('code'))
                    <p class="invalid-feedback">{{ $errors->first('code') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricetype.fields.name_helper') }}</span>
            </div>
            
            <div>
                <input type="hidden" value="0" name="is_default" id="is_default" />
            </div>
        </div>

        <div class="footer">
            <a href="javascript:history.go(-1);" class="btn btn-default mr-3">{{ trans('global.back_to_list') }}</a>
            <button type="submit" class="submit-button">@lang('Save')</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@parent
<script>
    function changecheckboxfunc() {
        alert(document.getElementById('checkboxexample').value);
    }
</script>
@endsection