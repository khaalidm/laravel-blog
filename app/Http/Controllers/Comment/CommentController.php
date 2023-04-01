<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;

use App\Http\Requests\AddCommentRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Serialisers\JsonSerialiser;
use App\Http\Transformers\CategoryTransformer;
use App\Http\Transformers\CommentTransformer;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $commentsPaginator = Comment::paginate(request('limit',10));

            return response()
                ->json(
                    fractal()
                        ->collection($commentsPaginator->getCollection())
                        ->transformWith(CommentTransformer::class)
                        ->withResourceName('data')
                        ->serializeWith(JsonSerialiser::class)
                        ->paginateWith(new IlluminatePaginatorAdapter($commentsPaginator))
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * @param  AddCommentRequest  $request
     * @return JsonResponse
     */
    public function store(AddCommentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $comment = $this->commentRepository->createComment($validated);

            return response()
                ->json(
                    fractal()
                        ->item($comment)
                        ->transformWith(CommentTransformer::class)
                        ->toArray(),
                    Response::HTTP_CREATED
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $comment = $this->commentRepository->getById($id);

            return response()
                ->json(
                    fractal()
                        ->item($comment)
                        ->transformWith(CommentTransformer::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondNotFound($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  UpdateCommentRequest  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->commentRepository->updateComment($id, $validated);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(CommentTransformer::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = $this->commentRepository->getById($id);
        $comment->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
