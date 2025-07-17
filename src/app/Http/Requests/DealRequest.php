<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
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
            'name' => 'required|max:400',
            'image' => 'mimes:jpg,png'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '本文を入力してください',
            'name.max' => '本文は400文字以内で入力してください',
            'image.mimes' => '.pngまたは.jpeg形式でアップロードしてください'
        ];
    }
}
