<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'    => [
                'string',
                'required',
            ],
            'email'   => [
                'required',
                'unique:users,email,' . request()->route('user')->id,
            ],
            'roles.*' => [
                'integer',
            ],
            'roles'   => [
                'required',
                'array',
            ],
            'multiplier'   => [
                'required',
            ],
            'company_name'   => [
                'required',
                'string',
            ],
            'company_address'   => [
                'required',
                'string',
            ],
            'company_post_code'   => [
                'required',
                'string',
            ],
            'company_city'   => [
                'required',
                'string',
            ],
            'company_tel'   => [
                'required',
                'string',
            ],
            'company_mobile'   => [
                'required',
                'string',
            ],
            'company_web_address'   => [
                'required',
                'string',
            ],
            'company_state'   => [
                'required',
                'string',
            ],
            'company_country'   => [
                'required',
                'string',
            ],
            'company_vat'   => [
                'required',
                'string',
            ],
            'delivery_time'   => [
                'required',
                'string',
            ],
            'delivery_time_type'   => [
                'string',
            ],
            'delivery_address'   => [
                'required',
                'integer',
            ],
            'delivery_condition'   => [
                'required',
                'integer',
            ],
        ];
    }
}
