<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class ByTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offset' => ['sometimes','integer','min:0'],
            'limit'  => ['sometimes','integer','min:1','max:5000'],
        ];
    }
}
