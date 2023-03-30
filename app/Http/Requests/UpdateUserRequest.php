<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("update users");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'     => 'sometimes|string|max:50',
            'last_name'      => 'sometimes|string|max:50',
            'mobile_number'  => 'sometimes|string',
            'password'   => [
                'sometimes',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }
}
