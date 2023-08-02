@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} {{ trans('cruds.pricing.title') }}
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.pricings.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div id="scooterItemTable">
            <table class="striped bordered show-table">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pricing.fields.id') }}
                        </th>
                        <td>
                            {{ $pricing->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricing.fields.desc') }}
                        </th>
                        <td>
                            {{ $pricing->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pricing.fields.multiplier') }}
                        </th>
                        <td>
                            {{ $pricing->multiplier }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-3">
               
            </div>
        </div>

        @can('scooter_edit')
            <div class="block pt-4">
                <a class="btn-md btn-green" href="{{ route('admin.pricings.edit', $pricing->id) }}">{{ trans('global.edit') }}</a>
            </div>
        @endcan
    </div>
</div>
@endsection
