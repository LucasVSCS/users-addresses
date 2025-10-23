<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|numeric|digits:11|unique:users,cpf',
            'email' => 'required|string|email|max:255|unique:users,email',
            'type' => ['sometimes', 'string', Rule::in(['admin', 'user'])],
            // --- Validação dos Endereços ---
            'existing_addresses' => 'sometimes|array',
            'existing_addresses.*' => 'integer|exists:addresses,id',
            'new_addresses' => 'sometimes|array',
            'new_addresses.*' => 'array',
            'new_addresses.*.street' => 'required|string|max:255',
            'new_addresses.*.postal_code' => 'required|string|max:255',
        ];
    }
}
