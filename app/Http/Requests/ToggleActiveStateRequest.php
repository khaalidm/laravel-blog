<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ToggleActiveStateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("edit posts");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'post' => 'required|exists:posts,id'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['post'] = $this->route('post');
        return $data;
    }
}
