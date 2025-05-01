<?php

namespace App\Http\Requests\v1\stores;

use Illuminate\Foundation\Http\FormRequest;

class CreateStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimetypes:image/jpeg,image/png,image/jpg,image/gif', 'max:2048'],
            'description' => ['required', 'string'],
            'university_id' => ['required', 'exists:universities,id'],
        ];
    }
}
