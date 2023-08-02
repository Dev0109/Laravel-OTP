@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.show') }} Language
    </div>

    <div class="body">
        <div class="block pb-4">
            <a class="btn-md btn-gray" href="{{ route('admin.language.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div id="scooterItemTable">
            <table class="striped bordered show-table">
                <tbody>
                    <tr>
                        <th>
                            Language Name
                        </th>
                        <td>
                            {{ $language->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Language Code
                        </th>
                        <td>
                            {{ $language->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Default
                        </th>
                        <td>
                            @if($language->is_default == 0)
                                <span class="badge red">Selectable</span>
                            @else
                                <span class="badge green">Default</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-3"></div>
        </div>

        @can('scooter_edit')
            <div class="block pt-4">
                <a class="btn-md btn-green" href="{{ route('admin.language.edit', $language->id) }}">{{ trans('global.edit') }}</a>
            </div>
        @endcan
    </div>
</div>
@endsection
