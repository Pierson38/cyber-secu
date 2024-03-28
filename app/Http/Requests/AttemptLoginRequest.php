<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttemptLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => 'required|max:255',
            "password" => 'required|max:255',
        ];
    }
}
