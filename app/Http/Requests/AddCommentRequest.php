<?php

declare(strict_types=1);

namespace App\Http\Requests;

class AddCommentRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("add comments");
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
            'post_id'         => 'required|exists:posts,id',
            'text'            => 'required|string|max:400',
        ];
    }
}
