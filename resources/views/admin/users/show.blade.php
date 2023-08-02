@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.users.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <table class="striped bordered show-table">
            <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.id') }}
                    </th>
                    <td>
                        {{ $user->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <td>
                        {{ $user->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.email') }}
                    </th>
                    <td>
                        {{ $user->email }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.email_verified_at') }}
                    </th>
                    <td>
                        {{ $user->email_verified_at }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <td>
                        @foreach($user->roles as $key => $roles)
                            <span class="label label-info">{{ $roles->title }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.multiplier') }}
                    </th>
                    <td>
                        {{ $multiplier }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.price_list') }}
                    </th>
                    <td>
                        {{ $pricetypes }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_name') }}
                    </th>
                    <td>
                        {{ $user->company_name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_address') }}
                    </th>
                    <td>
                        {{ $user->company_address }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_post_code') }}
                    </th>
                    <td>
                        {{ $user->company_post_code }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_city') }}
                    </th>
                    <td>
                        {{ $user->company_city }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_tel') }}
                    </th>
                    <td>
                        {{ $user->company_tel }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_mobile') }}
                    </th>
                    <td>
                        {{ $user->company_mobile }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_web_address') }}
                    </th>
                    <td>
                        {{ $user->company_web_address }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_state') }}
                    </th>
                    <td>
                        {{ $user->company_state }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_country') }}
                    </th>
                    <td>
                        {{ $user->company_country }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.company_vat') }}
                    </th>
                    <td>
                        {{ $user->company_vat }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.delivery_time') }}
                    </th>
                    <td>
                        {{ $user->delivery_time ?? ''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.delivery_address') }}
                    </th>
                    <td>
                        {{ $delivery_address->address ?? ''}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.user.fields.delivery_condition') }}
                    </th>
                    <td>
                        {{ $delivery_condition->cond ?? ''}}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="block pt-4">
            <a class="btn-md btn-gray" href="{{ route('admin.users.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection