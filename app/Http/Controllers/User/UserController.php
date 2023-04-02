<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Serialisers\JsonSerialiser;
use App\Http\Transformers\UserTransformer;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $usersPaginator = User::paginate(request('limit',10));

            return response()
                ->json(
                    fractal()
                        ->collection($usersPaginator->getCollection())
                        ->transformWith(UserTransformer::class)
                        ->withResourceName('data')
                        ->serializeWith(JsonSerialiser::class)
                        ->paginateWith(new IlluminatePaginatorAdapter($usersPaginator))
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
    public function store(AddUserRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->createUser($validated);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(UserTransformer::class)
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
            $user = $this->userRepository->getById($id);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(UserTransformer::class)
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
     * @param UpdateUserRequest $request
     * @param int               $id
     *
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->updateUser($id, $validated);

            return response()
                ->json(
                    fractal()
                        ->item($user)
                        ->transformWith(UserTransformer::class)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $user = $this->userRepository->getById($id);
        $user->delete();
        return response()->json('', Response::HTTP_NO_CONTENT);
    }
}
