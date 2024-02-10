<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->type === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user_id = $this->user ? $this->user->id : null;
        return [
            'prefixname' => ['nullable', 'in:Mr,Mrs,Ms'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffixname' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user_id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user_id)],
            'password' => $this->isMethod('put') ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'type' => ['nullable', 'string', 'max:255'],
        ];
    }
}
