@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} {{ trans('cruds.scooterStatus.title') }}
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.scooter-statuses.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <table class="striped bordered show-table">
            <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.scooterStatus.fields.id') }}
                    </th>
                    <td>
                        {{ $scooterStatus->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.scooterStatus.fields.name') }}
                    </th>
                    <td>
                        {{ $scooterStatus->name }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="block pt-4">
            <a class="btn-md btn-gray" href="{{ route('admin.scooter-statuses.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection