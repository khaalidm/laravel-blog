<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ListCategoriesByUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("list categories");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['user_id'] = $this->route('user_id');
        return $data;
    }
}
