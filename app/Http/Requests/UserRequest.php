<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Guard $auth
     * @return bool
     */
    public function authorize(Guard $auth)
    {
        return ($auth->user()) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Guard $auth
     * @return array
     */
    public function rules(Guard $auth)
    {
        $rules =  [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'     => 'required|email|unique:users|max:255',
        ];

        if(!empty($this->segment(2)))
        {
            $rules['email'] = 'required|max:255|unique:users,email,' . $this->segment(2);
        }
        return $rules;
    }
}
