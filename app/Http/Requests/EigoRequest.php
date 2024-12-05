<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EigoRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_released' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'required|integer|min:1|max:5',
            'times_watched' => 'required|integer|min:0',
            'recommended' => 'required|boolean'
        ];
    }
}
