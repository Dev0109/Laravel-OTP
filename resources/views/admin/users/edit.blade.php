@extends('layouts.admin')
@section('content')
<script>
    var temp_pricelist = '<?php echo json_encode($pricetypes_user_array); ?>';
    temp_pricelist = JSON.parse(temp_pricelist);
</script>
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <form method="POST" action="{{ route('admin.users.update', [$user->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="name" class="text-xs required">{{ trans('cruds.user.fields.name') }}</label>

                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', $user->name) }}" required>
                </div>
                @if($errors->has('name'))
                    <p class="invalid-feedback">{{ $errors->first('name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="email" class="text-xs required">{{ trans('cruds.user.fields.email') }}</label>

                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', $user->email) }}" required>
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
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <p class="invalid-feedback">{{ $errors->first('roles') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <?php
                $pricing_class_id = 0;
                if ($user->multiplier) {
                    $pricing_class_id = explode('_', $user->multiplier)[0];
                }
            ?>
            <div class="mb-3">
                <label for="multiplier" class="text-xs required">{{ trans('cruds.user.fields.multiplier') }}</label>
                <select id="multiplier" class="custom-select" name="multiplier">
                    <option value=""></option>
                    @foreach($pricing_class as $row)
                    @if($row->id == $pricing_class_id)
                        <option value="{{ $row->id }}_{{ $row->multiplier }}" selected>{{ $row->description}} - {{ $row->multiplier }}</option>
                    @else
                        <option value="{{ $row->id }}_{{ $row->multiplier }}">{{ $row->description}} - {{ $row->multiplier }}</option>
                    @endif
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
                        <select class="select2{{ $errors->has('pricetypes') ? ' is-invalid' : '' }}" name="pricetypes[]" id="pricetypes" multiple required>
                            @foreach($pricetypes_user_array as $row)
                                <option id="pricetype_{{$row['id']}}" value="{{$row['id']}}_{{$row['multiplier']}}" selected>{{$row['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($errors->has('pricetypes'))
                    <p class="invalid-feedback">{{ $errors->first('pricetypes') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.price_list_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_name" class="text-xs required">{{ trans('cruds.user.fields.company_name') }}</label>

                <div class="form-group">
                    <input type="text" id="company_name" name="company_name" class="{{ $errors->has('company_name') ? ' is-invalid' : '' }} form-control" value="{{ old('company_name', $user->company_name) }}" required>
                </div>
                @if($errors->has('company_name'))
                    <p class="invalid-feedback">{{ $errors->first('company_name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_name_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_address" class="text-xs required">{{ trans('cruds.user.fields.company_address') }}</label>

                <div class="form-group">
                    <input type="text" id="company_address" name="company_address" class="{{ $errors->has('company_address') ? ' is-invalid' : '' }} form-control" value="{{ old('company_address', $user->company_address) }}" required>
                </div>
                @if($errors->has('company_address'))
                    <p class="invalid-feedback">{{ $errors->first('company_address') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_address_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_post_code" class="text-xs required">{{ trans('cruds.user.fields.company_post_code') }}</label>

                <div class="form-group">
                    <input type="text" id="company_post_code" name="company_post_code" class="{{ $errors->has('company_post_code') ? ' is-invalid' : '' }} form-control" value="{{ old('company_post_code', $user->company_post_code) }}" required>
                </div>
                @if($errors->has('company_post_code'))
                    <p class="invalid-feedback">{{ $errors->first('company_post_code') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_post_code_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_city" class="text-xs required">{{ trans('cruds.user.fields.company_city') }}</label>

                <div class="form-group">
                    <input type="text" id="company_city" name="company_city" class="{{ $errors->has('company_city') ? ' is-invalid' : '' }} form-control" value="{{ old('company_city', $user->company_city) }}" required>
                </div>
                @if($errors->has('company_city'))
                    <p class="invalid-feedback">{{ $errors->first('company_city') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_city_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_tel" class="text-xs required">{{ trans('cruds.user.fields.company_tel') }}</label>

                <div class="form-group">
                    <input type="text" id="company_tel" name="company_tel" class="{{ $errors->has('company_tel') ? ' is-invalid' : '' }} form-control" value="{{ old('company_tel', $user->company_tel) }}" required>
                </div>
                @if($errors->has('company_tel'))
                    <p class="invalid-feedback">{{ $errors->first('company_tel') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_tel_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_mobile" class="text-xs required">{{ trans('cruds.user.fields.company_mobile') }}</label>

                <div class="form-group">
                    <input type="text" id="company_mobile" name="company_mobile" class="{{ $errors->has('company_mobile') ? ' is-invalid' : '' }} form-control" value="{{ old('company_mobile', $user->company_mobile) }}" required>
                </div>
                @if($errors->has('company_mobile'))
                    <p class="invalid-feedback">{{ $errors->first('company_mobile') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_mobile_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_web_address" class="text-xs required">{{ trans('cruds.user.fields.company_web_address') }}</label>

                <div class="form-group">
                    <input type="text" id="company_web_address" name="company_web_address" class="{{ $errors->has('company_web_address') ? ' is-invalid' : '' }} form-control" value="{{ old('company_web_address', $user->company_web_address) }}" required>
                </div>
                @if($errors->has('company_web_address'))
                    <p class="invalid-feedback">{{ $errors->first('company_web_address') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_web_address_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_state" class="text-xs required">{{ trans('cruds.user.fields.company_state') }}</label>

                <div class="form-group">
                    <input type="text" id="company_state" name="company_state" class="{{ $errors->has('company_state') ? ' is-invalid' : '' }} form-control" value="{{ old('company_state', $user->company_state) }}" required>
                </div>
                @if($errors->has('company_state'))
                    <p class="invalid-feedback">{{ $errors->first('company_state') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_state_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_country" class="text-xs required">{{ trans('cruds.user.fields.company_country') }}</label>

                <div class="form-group">
                    <input type="text" id="company_country" name="company_country" class="{{ $errors->has('company_country') ? ' is-invalid' : '' }} form-control" value="{{ old('company_country', $user->company_country) }}" required>
                </div>
                @if($errors->has('company_country'))
                    <p class="invalid-feedback">{{ $errors->first('company_country') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_country_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="company_vat" class="text-xs required">{{ trans('cruds.user.fields.company_vat') }}</label>

                <div class="form-group">
                    <input type="text" id="company_vat" name="company_vat" class="{{ $errors->has('company_vat') ? ' is-invalid' : '' }} form-control" value="{{ old('company_vat', $user->company_vat) }}" required>
                </div>
                @if($errors->has('company_vat'))
                    <p class="invalid-feedback">{{ $errors->first('company_vat') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.company_vat_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="delivery_time" class="text-xs required">{{ trans('cruds.user.fields.delivery_time') }}</label>
                <div class="form-group row">
                    <div class="col-md-3">
                        <input type="number" class="form-control" id="delivery_time" name="delivery_time" value="1" min="1" max="320" maxlength="3" required>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="delivery_time_type" name="delivery_time_type">
                            <option value="1">Days</option>
                            <option value="2">Weeks</option>
                        </select>
                    </div>
                </div>
                @if($errors->has('delivery_time'))
                    <p class="invalid-feedback">{{ $errors->first('delivery_time') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.delivery_time_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="delivery_address" class="text-xs required">{{ trans('cruds.user.fields.delivery_address') }}</label>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <select id="delivery_address" name="delivery_address">
                                <option value="0">Select an Address</option>
                                @foreach($delivery_address as $row)
                                    <option value="{{$row->id}}">{{$row->address}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="delivery_address_input" placeholder="Type a new item">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" onclick="addAddressItem()">Add</button>
                        </div>
                    </div>
                </div>
                @if($errors->has('delivery_address'))
                    <p class="invalid-feedback">{{ $errors->first('delivery_address') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.delivery_address_helper') }}</span>
            </div>
            <div class="mb-3">
                <label for="delivery_condition" class="text-xs required">{{ trans('cruds.user.fields.delivery_condition') }}</label>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <select id="delivery_condition" name="delivery_condition">
                                <option value="0">Select a Condition</option>
                                @foreach($delivery_condition as $row)
                                    <option value="{{$row->id}}">{{$row->cond}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="delivery_condition_input" placeholder="Type a new item">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" onclick="addConditionItem()">Add</button>
                        </div>
                    </div>
                </div>
                @if($errors->has('delivery_condition'))
                    <p class="invalid-feedback">{{ $errors->first('delivery_condition') }}</p>
                @endif
                <span class="block">{{ trans('cruds.user.fields.delivery_condition_helper') }}</span>
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
        var _token = null;
        var delivery_time = "{{$user->delivery_time ?? '1_1'}}";
        var delivery_address = {{$user->delivery_address ?? 0}};
        var delivery_condition = {{$user->delivery_condition ?? 0}};
        var pricelist = [];
        $(document).ready(() => {
            _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var temp = delivery_time.split('_');
            $('#delivery_time').val(temp[0]);
            $('#delivery_time_type').val(temp[1]);
            $('#delivery_address').val(delivery_address);
            $('#delivery_condition').val(delivery_condition);
            for (row of temp_pricelist) {
                pricelist.push({
                    pricetype: row.id,
                    multiplier: row.multiplier,
                });
            }
        });
        function addAddressItem() {
            var input = document.getElementById("delivery_address_input");
            var item = input.value;

            if (item !== "") {
                var select = document.getElementById("delivery_address");
                var option = document.createElement("option");
                $.ajax({
                    url: "{{route('admin.users.add.DeliveryAddress')}}",
                    method: 'POST',
                    headers: {'x-csrf-token': _token},
                    data: {
                        uid: '{{$user->id}}',
                        address: item,
                    },
                    success: (res) => {
                        option.text = item;
                        option.value = res;
                        select.add(option);
                        input.value = "";
                    }
                });
            }
        }
        function addConditionItem() {
            var input = document.getElementById("delivery_condition_input");
            var item = input.value;

            if (item !== "") {
                var select = document.getElementById("delivery_condition");
                var option = document.createElement("option");
                $.ajax({
                    url: "{{route('admin.users.add.DeliveryCondition')}}",
                    method: 'POST',
                    headers: {'x-csrf-token': _token},
                    data: {
                        uid: '{{$user->id}}',
                        condition: item,
                    },
                    success: (res) => {
                        option.text = item;
                        option.value = res;
                        select.add(option);
                        input.value = "";
                    }
                });
            }
        }
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