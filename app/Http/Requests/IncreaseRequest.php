<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncreaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => 'required|numeric|min:000000.001|max:999999|decimal:0,3' // max_digits rule had a bug
        ];
    }
}
