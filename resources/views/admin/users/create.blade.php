@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <form method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="name" class="text-xs required">{{ trans('cruds.user.fields.name') }}</label>

                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" required>
                </div>
                @if($errors->has('name'))
                    <p class="invalid-feedback">{{ $errors->first('name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="email" class="text-xs required">{{ trans('cruds.user.fields.email') }}</label>

                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required>
                </div>
                @if($errors->has('email'))
                    <p class="invalid-feedback">{{ $errors->first('email') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="password" class="text-xs required">{{ trans('cruds.user.fields.password') }}</label>

                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}">
                </div>
                @if($errors->has('password'))
                    <p class="invalid-feedback">{{ $errors->first('password') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="roles" class="text-xs required">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn-sm btn-indigo select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn-sm btn-indigo deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="select2{{ $errors->has('roles') ? ' is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <p class="invalid-feedback">{{ $errors->first('roles') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="multiplier" class="text-xs required">{{ trans('cruds.user.fields.multiplier') }}</label>
                <select id="multiplier" class="custom-select" name="multiplier">
                    <option value=""></option>
                    @foreach($pricing_class as $row)
                        <option value="{{ $row->id }}_{{ $row->multiplier }}">{{ $row->description}} - {{ $row->multiplier }}</option>
                    @endforeach
                </select>
                @if($errors->has('multiplier'))
                    <p class="invalid-feedback">{{ $errors->first('multiplier') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.multiplier_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="pricetypes" class="text-xs required">{{ trans('cruds.user.fields.price_list') }}</label>
                <div class="row">
                    <div class="col-md-4">
                        <select id="pricelist" class="custom-select" name="">
                            <option value="0"></option>
                            @foreach($pricetypes as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        <label for="price_multiplier">Multiplier:</label>
                        <input type="number" step="0.0001" class="form-control mb-3" id="price_multiplier" min="0" value="1.3">
                        <button type="button" class="btn btn-success" onclick="addPrice();">Add</button>
                    </div>
                    <div class="col-md-4">
                        <select class="select2{{ $errors->has('pricetypes') ? ' is-invalid' : '' }}" name="pricetypes[]" id="pricetypes" multiple>
                            
                        </select>
                    </div>
                </div>
                <span class="block">{{ trans('cruds.user.fields.price_list_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        var pricelist = [];
        function addPrice() {
            var pricetype = document.getElementById('pricelist');
            var selectedOption = pricetype.options[pricetype.selectedIndex];
            var price_multiplier = parseFloat(document.getElementById('price_multiplier').value);
            if (selectedOption.value != 0 && price_multiplier != 0 && price_multiplier != ''){
                var isExist = false;
                pricelist.map((row, index) => {
                    if (row.pricetype == selectedOption.value) {
                        pricelist.splice(index, 1);
                        isExist = true;
                    }
                });
                if (isExist) {
                    document.getElementById(`pricetype_${selectedOption.value}`).remove();
                }
                pricelist.push({
                    pricetype: selectedOption.value,
                    multiplier: price_multiplier,
                });
                var select = document.getElementById("pricetypes");
                var option = document.createElement("option");
                option.value = `${selectedOption.value}_${price_multiplier}`;
                option.text = `${selectedOption.text} - ${price_multiplier}`;
                option.id = `pricetype_${selectedOption.value}`;
                option.selected = true;
                select.add(option);
                document.getElementById('price_multiplier').value = 1.3;
                document.getElementById('pricelist').value = 0;
            }
        }
    </script>
@endsection