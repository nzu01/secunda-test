<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class ByTypeTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'  => ['required','string','min:1'],
            'offset' => ['sometimes','integer','min:0'],
            'limit'  => ['sometimes','integer','min:1','max:5000'],
        ];
    }
}
