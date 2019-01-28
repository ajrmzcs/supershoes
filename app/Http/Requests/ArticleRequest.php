<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
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
            'description' => 'required',
            'name' => 'required | max:100',
            'price' => 'required | numeric',
            'total_in_shelf' => 'required | integer',
            'total_in_vault' => 'required | integer',
            'store_name' => 'required | exists:stores,name'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        throw new HttpResponseException(response()->json([
            'error_msg' => 'Bad Request',
            'error_code' => 400,
            'success' => false,
            'errors' => $validator->errors()
        ], 400));
    }
}
