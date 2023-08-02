@if(in_array('role_create', $user_role) && isset($user_role))
    <div class="block my-4">
        <a class="btn-md btn-green" href="#">
            {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>
    </div>
@endif
<div class="main-card">
    <div class="header">
        {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Role">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.permissions') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td align="center">
                                {{ $role->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $role->title ?? '' }}
                            </td>
                            <td align="center">
                                @foreach($role->permissions as $key => $item)
                                    <span class="badge blue">{{ $item->display }}</span>
                                @endforeach
                            </td>
                            <td align="center">
                                @if(in_array('role_show', $user_role) && isset($user_role))
                                    <a class="btn-sm btn-indigo" href="#">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif
                                @if(in_array('role_edit', $user_role) && isset($user_role))
                                    <a class="btn-sm btn-blue" href="#">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif
                                @if(in_array('role_delete', $user_role) && isset($user_role))
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
        @if(in_array('role_delete', $user_role) && isset($user_role))
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.roles.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>