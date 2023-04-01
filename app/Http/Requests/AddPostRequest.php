<?php

declare(strict_types=1);

namespace App\Http\Requests;

class AddPostRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("create posts");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id'         => 'required|exists:users,id',
            'category_id'     => 'required|exists:categories,id',
            'title'           => 'required|unique:posts|max:255',
            'description'     => 'required|string|max:255',
            'body'            => 'required'
        ];
    }
}
