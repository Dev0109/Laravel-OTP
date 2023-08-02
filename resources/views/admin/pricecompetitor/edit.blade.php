@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} Competitor
    </div>

    <form method="POST" action="{{ route('admin.pricecompetitor.update', [$pricetype->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="name" class="text-xs required">{{ trans('cruds.pricecompetitor.fields.name') }}</label>

                <div class="form-group">
                    <input type="text" id="name" name="name" class="{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', $pricetype->name) }}" required>
                </div>
                @if($errors->has('name'))
                    <p class="invalid-feedback">{{ $errors->first('name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricetype.fields.name_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="pricelink" class="text-xs required">{{ trans('cruds.pricecompetitor.fields.pricing_class') }}</label>

                <div class="form-group">
                    <input type="text" id="pricelink" name="pricelink" class="{{ $errors->has('pricelink') ? ' is-invalid' : '' }}" value="{{ old('pricelink', $pricetype->pricelink) }}">
                </div>
                @if($errors->has('pricelink'))
                    <p class="invalid-feedback">{{ $errors->first('pricelink') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricecompetitor.fields.pricing_class_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="name" class="text-xs">{{ trans('cruds.pricecompetitor.fields.website') }}</label>

                <div class="form-group">
                    <input type="text" id="website" name="website" class="{{ $errors->has('website') ? ' is-invalid' : '' }}"  value="{{ old('website', $pricetype->website) }}">
                </div>
                @if($errors->has('webiste'))
                    <p class="invalid-feedback">{{ $errors->first('website') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricecompetitor.fields.website_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="username" class="text-xs">{{ trans('cruds.pricecompetitor.fields.username') }}</label>

                <div class="form-group">
                    <input type="text" id="username" name="username" class="{{ $errors->has('username') ? ' is-invalid' : '' }}"  value="{{ old('username', $pricetype->username) }}">
                </div>
                @if($errors->has('username'))
                    <p class="invalid-feedback">{{ $errors->first('username') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricecompetitor.fields.username_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="userpwd" class="text-xs">{{ trans('cruds.pricecompetitor.fields.userpwd') }}</label>

                <div class="form-group">
                    <input type="text" id="userpwd" name="userpwd" class="{{ $errors->has('userpwd') ? ' is-invalid' : '' }}"  value="{{ old('userpwd', $pricetype->userpwd) }}">
                </div>
                @if($errors->has('userpwd'))
                    <p class="invalid-feedback">{{ $errors->first('userpwd') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricecompetitor.fields.userpwd_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="file" class="text-xs">{{ trans('cruds.pricecompetitor.fields.pricelist') }}</label>

                <div class="form-group">
                    <input type="file" id="pricelist" name="pricelist" class="{{ $errors->has('pricelist') ? ' is-invalid' : '' }}" accept="application/pdf">
                </div>
                @if($errors->has('pricelist'))
                    <p class="invalid-feedback">{{ $errors->first('pricelist') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricecompetitor.fields.pricelist_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="file" class="text-xs">{{ trans('cruds.pricecompetitor.fields.datasheet') }}</label>

                <div class="form-group">
                    <input type="file" id="datasheet" name="datasheet" class="{{ $errors->has('datasheet') ? ' is-invalid' : '' }}" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv">
                </div>
                @if($errors->has('datasheet'))
                    <p class="invalid-feedback">{{ $errors->first('datasheet') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricecompetitor.fields.datasheet_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <a href="javascript:history.go(-1)" class="btn btn-default mr-3">{{ trans('global.back_to_list') }}</a>
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection