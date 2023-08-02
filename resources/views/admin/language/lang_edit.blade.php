@extends('layouts.admin')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
@can('scooter_create')
    <div class="block my-4">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addnewkeymodal">
            {{ trans('global.add') }} New Key
        </button>
        <input type="file" id="file_excel_import" class="hidden" accept=".xlsx, .xls"/>
        <button type="button" class="btn btn-primary" onclick="importExcel()">
            Import Excel
        </button>
        <button type="button" class="btn btn-info" onclick="exportExcel()">
            Export Excel
        </button>
    </div>
@endcan
<div class="main-card">
    <div class="header">
        Language Keywords of {{ $lang->name }}
    </div>
    <div class="body">
        <div class="w-full">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th style="text-align: center;">
                            Key
                        </th>
                        <th style="text-align: center;">
                            {{ $lang->name }}
                        </th>
                        <th style="text-align: center;">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($json as $key => $language)
                        <tr>
                            <td align="center">
                                {{ $key ?? '' }}
                            </td>
                            <td align="center">
                                {{ $language ?? '' }}
                            </td>
                            <td align="center">
                                <button onclick="oneditkey('{{ $key }}', '{{ $language }}')" type="button" class="btn-sm btn-green" data-toggle="modal" data-target="#editkeymodal">
                                    Edit
                                </button>
                                <form action="{{ route('admin.languages.key.delete', $lang->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="key" id="key" value="{{ $key }}" />
                                    <input type="hidden" name="value" id="value" value="{{ $language }}" />
                                    <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit key begin modal -->
    <div class="modal" id="editkeymodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.languages.key.edit', $lang->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Key</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="key" class="text-xs required">Key</label>

                            <div class="form-group">
                                <input type="text" id="editkey" name="key" class="" value="" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="text-xs required">Value</label>

                            <div class="form-group">
                                <input type="text" id="editvalue" name="value" class="" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit key End Modal -->

    <!-- Add new Key Modal Begin -->
    <div class="modal" id="addnewkeymodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.languages.store.key', $lang->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Add New</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="key" class="text-xs required">Key</label>

                            <div class="form-group">
                                <input type="text" id="key" name="key" class="{{ $errors->has('key') ? ' is-invalid' : '' }}" value="{{ old('key') }}" required>
                            </div>
                            @if($errors->has('key'))
                                <p class="invalid-feedback">{{ $errors->first('key') }}</p>
                            @endif
                            <span class="block"></span>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="text-xs required">Value</label>

                            <div class="form-group">
                                <input type="text" id="value" name="value" class="{{ $errors->has('value') ? ' is-invalid' : '' }}" value="{{ old('value') }}" required>
                            </div>
                            @if($errors->has('value'))
                                <p class="invalid-feedback">{{ $errors->first('value') }}</p>
                            @endif
                            <span class="block"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add new Key End Modal -->
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
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable();
    });

    function oneditkey(key, value) {
        document.getElementById('editkey').value = key;
        document.getElementById('editvalue').value = value;
    }

    function importExcel() {
        $('#file_excel_import').trigger('click');
    }

    $('#file_excel_import').on('change', function(e) {
        const file = e.target.files[0];
        console.log(file);

        const formData = new FormData();
        formData.append('file', file);        

        $.ajax({
            type: 'POST',
            url: '{{route('admin.languages.key.import', $lang->id) }}',
            data: formData,
            headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            processData: false,
            contentType: false,
            success: function(data) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.log('An error occurred while uploading the file.');
            }
        })
    });

    function exportExcel() {
        let tbl = $('.datatable-Scooter:not(.ajaxTable)').DataTable();

        var rows = tbl.rows().data();
        var data = [];
        rows.each(function(row) {
            data.push({
                'Key' : row[0],
                'Translation' : row[1]
            });
        });    
        
        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.json_to_sheet(data);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Price');
        XLSX.writeFile(workbook, 'lang_' + '{{ $lang->name }}'.toLowerCase() +'_' + (new Date().getTime()) + '.xlsx');
    }

</script>
@endsection