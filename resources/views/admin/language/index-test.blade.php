@if(in_array('scooter_create', $role) && isset($role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            @lang('Add New Language')
        </a>
    </div>
@endif
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
                                @if(in_array('scooter_show', $role) && isset($role))
                                    <a class="btn-sm btn-green" href="#">
                                        Translate
                                    </a>
                                @endif
                                @if(in_array('scooter_show', $role) && isset($role))
                                    <a class="btn-sm btn-indigo" href="#">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif
                                @if(in_array('scooter_edit', $role) && isset($role))
                                    <a class="btn-sm btn-blue" href="#">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif
                                @if(in_array('scooter_delete', $role) && isset($role))
                                    <a class="btn-sm btn-red" href="#">
                                        {{ trans('global.delete') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
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