<?php

declare(strict_types=1);

namespace App\Http\Requests;

class UpdatePostRequest extends BaseFormRequest
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
            'post'            => 'required|exists:posts,id',
            'category_id'     => 'sometimes|exists:categories,id',
            'title'           => 'sometimes|string|max:255',
            'description'     => 'sometimes|string|max:255',
            'body'            => 'sometimes'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['post'] = $this->route('post');
        return $data;
    }
}
