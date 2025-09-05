<?php

namespace App\Http\Requests\OrganizationType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'       => ['required','string','min:1','max:255'],
            'name'        => ['required','string','min:1','max:255'],
            'parent_uuid' => [
                'nullable','string',
                function($attr,$val,$fail){
                    if (!\Illuminate\Support\Str::isUuid($val)) {
                        $fail('The '.$attr.' must be a valid UUID.');
                    }
                },
                Rule::exists('organization_types','uuid')
            ],
        ];
    }
}
