<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPostRequest;
use App\Http\Requests\ListPostsByCategoryRequest;
use App\Http\Requests\ListPostsByUserRequest;
use App\Http\Requests\ToggleActiveStateRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Serialisers\JsonSerialiser;
use App\Http\Transformers\PostTransformer;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;
    private UserRepositoryInterface $userRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(PostRepositoryInterface $postRepository, UserRepositoryInterface $userRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->postRepository     = $postRepository;
        $this->userRepository     = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $postsPaginator = Post::paginate(request('limit',10));

            return response()
                ->json(
                    fractal()
                        ->collection($postsPaginator->getCollection())
                        ->transformWith(PostTransformer::class)
                        ->withResourceName('data')
                        ->serializeWith(JsonSerialiser::class)
                        ->paginateWith(new IlluminatePaginatorAdapter($postsPaginator))
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddPostRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $post = $this->postRepository->createPost($validated);

            return response()
                ->json(
                    fractal()
                        ->item($post)
                        ->transformWith(PostTransformer::class)
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
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        try {
            $post = $this->postRepository->getById($id);

            return response()
                ->json(
                    fractal()
                        ->item($post)
                        ->transformWith(PostTransformer::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondNotFound($e);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param int               $id
     *
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $post = $this->postRepository->updatePost($id, $validated);

            return response()
                ->json(
                    fractal()
                        ->item($post)
                        ->transformWith(PostTransformer::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * @param  ListPostsByUserRequest  $request
     * @param $id
     * @return JsonResponse
     */
    public function listByUser(ListPostsByUserRequest $request, $user_id): JsonResponse
    {
        try {
            $request->validated();
            $user = $this->userRepository->getById($user_id);

            return response()
                ->json(
                    fractal()
                        ->collection($user->posts)
                        ->transformWith(PostTransformer::class)
                        ->withResourceName('data')
                        ->serializeWith(JsonSerialiser::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * @param  ListPostsByCategoryRequest  $request
     * @param $category_id
     * @return JsonResponse
     */
    public function listByCategory(ListPostsByCategoryRequest $request, $category_id): JsonResponse
    {
        try {
            $request->validated();
            $category = $this->categoryRepository->getById($category_id);

            return response()
                ->json(
                    fractal()
                        ->collection($category->posts)
                        ->transformWith(PostTransformer::class)
                        ->withResourceName('data')
                        ->serializeWith(JsonSerialiser::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }


    /**
     * @param  ToggleActiveStateRequest  $request
     * @param $post_id
     * @return JsonResponse
     */
    public function toggleActiveState(ToggleActiveStateRequest $request, $post_id): JsonResponse
    {
        try {
            $request->validated();
            $post = $this->postRepository->toggleActiveState($post_id);

            return response()
                ->json(
                    fractal()
                        ->item($post)
                        ->transformWith(PostTransformer::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }
}
