<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateCommentRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can("update comments");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => 'sometimes|string|max:400',
        ];
    }

    public function prepareForValidation()
    {
        $comment = Comment::where('id', $this->route('comment'))->get()->first();

        if (auth()->user()->id !== $comment->user_id) {
            return response()
                ->json(
                    [
                        'error' => 'Unauthorized'
                    ],
                    Response::HTTP_FORBIDDEN);
        }
    }
}
