@extends('layouts.admin')
@section('content')
@can('scooter_create')
    <div class="block my-4">
        <a class="btn-md btn-green" href="{{ route('admin.language.create') }}">
            @lang('Add New Language')
        </a>
    </div>
@endcan
<div class="main-card">
    <div class="header">
        @lang('Language Setting')
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th style="text-align: center;">
                            @lang('Name')
                        </th>
                        <th style="text-align: center;">
                            @lang('Code')
                        </th>
                        <th style="text-align: center;">
                            @lang('Default')
                        </th>
                        <th style="text-align: center;">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($languages as $key => $language)
                        <tr data-entry-id="{{ $language->id }}">
                            <td align="center">
                                {{ $language->name ?? '' }}
                            </td>
                            <td align="center">
                                {{ $language->code ?? '' }}
                            </td>
                            <td align="center">
                                @if($language->is_default == 1)
                                    <span class="badge green">Default</span>
                                @else
                                    <span class="badge red">Selectable</span>
                                @endif
                            </td>
                            <td align="center">
                                @can('scooter_show')
                                    <a class="btn-sm btn-green" href="{{ route('admin.language.langedit', $language->id) }}">
                                        Translate
                                    </a>
                                @endcan
                                @can('scooter_show')
                                    <a class="btn-sm btn-indigo" href="{{ route('admin.language.show', $language->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('scooter_edit')
                                    <a class="btn-sm btn-blue" href="{{ route('admin.language.edit', $language->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('scooter_delete')
                                    <form action="{{ route('admin.language.destroy', $language->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable()
    });

</script>
@endsection