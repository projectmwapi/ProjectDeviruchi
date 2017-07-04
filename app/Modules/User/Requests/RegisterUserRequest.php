<?php

namespace App\Modules\User\Requests;

use Response;
use StatusHelper;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * @return boolean
     */
    public function wantsJson()
    {
        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|email',
            'employee_number'   => 'required'
        ];
    }

    public function response(array $errors)
    {
        return [
            'code'      => '422',
            'status'    => StatusHelper::getErrorResponseStatus(),
            'data'      => $errors
        ];
    }
}
