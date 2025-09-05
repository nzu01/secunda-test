<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class WithinRadiusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lat'    => ['required','numeric','between:-90,90'],
            'lng'    => ['required','numeric','between:-180,180'],
            'radius' => ['required','numeric','min:1','max:1000000'],
        ];
    }
}
