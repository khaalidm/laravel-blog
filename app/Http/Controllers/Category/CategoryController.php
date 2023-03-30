<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;

use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Serialisers\JsonSerialiser;
use App\Http\Transformers\CategoryTransformer;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
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
            $categoriesPaginator = Category::paginate(request('limit',10));

            return response()
                ->json(
                    fractal()
                        ->collection($categoriesPaginator->getCollection())
                        ->transformWith(CategoryTransformer::class)
                        ->withResourceName('data')
                        ->serializeWith(JsonSerialiser::class)
                        ->paginateWith(new IlluminatePaginatorAdapter($categoriesPaginator))
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondInternalError($e);
        }
    }

    /**
     * @param  AddCategoryRequest  $request
     * @return JsonResponse
     */
    public function store(AddCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->categoryRepository->createCategory($validated);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(CategoryTransformer::class)
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
            $user = $this->categoryRepository->getById($id);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(CategoryTransformer::class)
                        ->toArray(),
                    Response::HTTP_OK
                );
        } catch (\Throwable $e) {
            return $this->respondNotFound($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  UpdateCategoryRequest  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->categoryRepository->updateCategory($id, $validated);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(CategoryTransformer::class)
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
        $user = $this->categoryRepository->getById($id);
        $user->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
