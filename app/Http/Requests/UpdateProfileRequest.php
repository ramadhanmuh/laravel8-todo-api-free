<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = $this->get('user_id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:191',
                Rule::unique('users')->ignore($user_id)
            ],
            'password' => [
                'required', 'string',
                function ($attribute, $value, $fail) use ($user_id) {
                    $user = User::find($user_id);

                    if (!Hash::check($value, $user->password)) {
                        $fail('Kata Sandi tidak valid.');
                    }
                }
            ],
        ];
    }
}
