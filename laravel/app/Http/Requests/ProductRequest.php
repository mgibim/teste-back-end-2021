<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest as RequestsFormRequest;

class ProductRequest extends RequestsFormRequest
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
            'name'   => 'required',
            'price'  => 'required|numeric',
            'weight' => 'sometimes|numeric',
            'image'  => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    
    }

}
