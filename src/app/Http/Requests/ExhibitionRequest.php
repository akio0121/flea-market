<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required',
            'condition_id' => ['required', 'exists:conditions,id'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'description' => 'required|max:255',
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['required', 'regex:/\.(jpe?g|png)$/i'], // ←ファイルではなくパス文字列
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'condition_id.required' => '商品の状態を選択してください',
            'category_ids.required' => 'カテゴリーを選択してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.numeric' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.regex' => '画像は jpg または png 形式でアップロードしてください'

        ];
    }
}
