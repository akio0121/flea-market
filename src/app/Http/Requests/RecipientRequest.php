<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipientRequest extends FormRequest
{
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
            'recipient_post' => 'required',
            'recipient_address' => 'required',
            'recipient_building' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'recipient_post.required' => '配送先の郵便番号を入力してください',
            'recipient_address.required' => '配送先の住所を入力してください',
            'recipient_building.required' => '配送先の建物名を入力してください'
        ];
    }
}
