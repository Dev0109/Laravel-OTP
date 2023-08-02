@if(in_array('user_create', $role) && isset($role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
    </div>
@endif
<div class="main-card">
    <div class="header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-User" style="overflow-x: scroll; display: block;">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.password') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_post_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_city') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_tel') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_mobile') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_web_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_state') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_country') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.company_vat') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.delivery_address') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.delivery_condition') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td align="center">
                                {{ $user->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->name ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->email ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->password ? '****' : ''}}
                            </td>
                            <td align="center">
                                @foreach($user->roles as $key => $item)
                                    <span class="badge blue">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td align="center">
                                {{ $user->company_name ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_address ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_post_code ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_city ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_tel ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_mobile ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_web_address ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_state ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_country ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->company_vat ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->delivery_address_data ?? '' }}
                            </td>
                            <td align="center">
                                {{ $user->delivery_condition_data ?? '' }}
                            </td>
                            <td align="center">
                                @if(in_array('user_show', $role) && isset($role))
                                    <a class="btn-sm btn-indigo" href="#">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif
                                @if(in_array('user_edit', $role) && isset($role))
                                    <a class="btn-sm btn-blue" href="#">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif
                                @if(in_array('user_delete', $role) && isset($role))
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
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    @if(in_array('user_delete', $role) && isset($role))
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.users.massDestroy') }}",
        className: 'btn-red',
        action: function (e, dt, node, config) {
        var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
            return $(entry).data('entry-id')
        });

        if (ids.length === 0) {
            alert('{{ trans('global.datatables.zero_selected') }}')

            return
        }

        if (confirm('{{ trans('global.areYouSure') }}')) {
            $.ajax({
            headers: {'x-csrf-token': _token},
            method: 'POST',
            url: config.url,
            data: { ids: ids, _method: 'DELETE' }})
            .done(function () { location.reload() })
        }
        }
    }
    dtButtons.push(deleteButton)
    @endif

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>