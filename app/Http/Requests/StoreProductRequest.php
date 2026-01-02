<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sku' => ['required','string','max:50'],
            'name' => ['required','string','max:200'],
            'price_cents' => ['required','integer','min:0'],
        ];
    }
}
