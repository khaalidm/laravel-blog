<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ListPostsByCategoryRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("list posts");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['category_id'] = $this->route('category_id');
        return $data;
    }
}
