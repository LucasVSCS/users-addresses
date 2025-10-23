<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $externalId = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($externalId, 'external_id')
            ],
            'cpf' => [
                'sometimes',
                'string',
                'numeric',
                'digits:11',
                Rule::unique('users', 'cpf')->ignore($externalId, 'external_id')
            ],
            'type' => ['sometimes', 'string', Rule::in(['admin', 'user'])],
            'existing_addresses' => 'sometimes|array',
            'existing_addresses.*' => 'integer|exists:addresses,id',
            'new_addresses' => 'sometimes|array',
            'new_addresses.*' => 'array',
            'new_addresses.*.street' => 'required|string|max:255',
            'new_addresses.*.postal_code' => 'required|string|max:255',
        ];
    }
}