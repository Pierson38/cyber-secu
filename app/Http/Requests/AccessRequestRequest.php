<?php

namespace App\Http\Requests;

use App\Models\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Contracts\Service\Attribute\Required;

class AccessRequestRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

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
            'name' => 'required|min:4',
            'email' => 'required|email:rfc|unique:App\Models\User,email',
            'password' => 'required|min:4|max:24',
            'role' => ['required', Rule::enum(RoleEnum::class)]
        ];
    }


}
