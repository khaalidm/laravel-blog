<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class UpdateCategoryRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("add categories");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'sometimes|string|max:160',
            'description'   => 'sometimes|string|max:220'
        ];
    }
}
