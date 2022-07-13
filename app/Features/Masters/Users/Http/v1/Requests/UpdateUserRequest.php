<?php

namespace App\Features\Masters\Users\Http\v1\Requests;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $passwordRule = '';
        if (!empty($this->password)) {
            $passwordRule = 'string|min:8|confirmed';
        }

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => $passwordRule,
            'role' => 'required|string|in:' . implode(',', User::ROLES),
        ];
    }

    public function toFormData()
    {
        if (!empty($this->password)) {
            return [
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ];
        } else {
            return [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];
        }
    }
}
