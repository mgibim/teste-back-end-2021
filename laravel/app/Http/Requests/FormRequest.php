<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            
            $errors = (new ValidationException($validator))->errors();
            
            $transformed = [];

            foreach ($errors as $field => $message) {
                $transformed[] = [
                    'fieldname' => $field,
                    'message'   => $message[0]
                ];
            }
            
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Ocorreu um erro de validação',
                    'errors'   => $transformed
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}