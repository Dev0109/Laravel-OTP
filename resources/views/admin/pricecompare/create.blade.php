@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.price.title_singular') }} Competitor
    </div>

    <form method="POST" action="{{ route('admin.pricecompare.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="block pb-4">
                <a class="btn-md btn-gray" href="{{ url()->previous() }}">
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
                                {{ trans('cruds.price.fields.price') }}
                            </th>
                            <td>
                                {{ number_format($price->price, 2) }} €
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mb-3">
                
                </div>
            </div>

            <div class="mb-10"></div>
            
            <div class="mb-3">
                <input type="hidden" id="id" name="id" value="{{ $price->id }}" required />
                <input type="hidden" id="pid" name="pid" value="{{ $pid }}" required />
            </div>

            <div class="mb-3">
                <label for="description" class="text-xs required">{{ trans('cruds.price.fields.desc') }}</label>

                <div class="form-group">
                    <input type="text" id="description" name="description" class="{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') }}" required>
                </div>
                @if($errors->has('description'))
                    <p class="invalid-feedback">{{ $errors->first('description') }}</p>
                @endif
                <span class="block"></span>
            </div>

            <div class="mb-3">
                <label for="price" class="text-xs required">{{ trans('cruds.price.fields.price') }}</label>

                <div class="form-group">
                    <input type="text" id="price" name="price" class="{{ $errors->has('price') ? ' is-invalid' : '' }}" value="{{ old('price') }}" required>
                </div>
                @if($errors->has('price'))
                    <p class="invalid-feedback">{{ $errors->first('price') }}</p>
                @endif
                <span class="block"></span>
            </div>

            <div class="mb-3">
                <label for="price_id" class="text-xs required">COMPETITOR</label>

                <div class="form-group">
                    <select name="competitor_id" class="{{ $errors->has('competitor_id') ? ' is-invalid' : '' }}" required>
                        <option value="">Choose Item</option>
                        @foreach($pricecompetitors as $key => $pricecompetitor)
                            <option value="{{ $pricecompetitor->id }}">
                                {{ $pricecompetitor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($errors->has('competitor_id'))
                    <p class="invalid-feedback">{{ $errors->first('competitor_id') }}</p>
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
