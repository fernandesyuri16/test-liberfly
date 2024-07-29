<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use Http;

    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsers",
     *      tags={"Users"},
     *      summary="Get registered users",
     *      description="Endpoint to retrieve all users.",
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Success, users found.",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Yuri Fernandes"),
     *                      @OA\Property(property="email", type="string", example="yuricaprelliofc1@gmail.com"),
     *                      @OA\Property(property="email_verified_at", type="string", example="null"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z")
     *                  )
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Not found.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User doesn't exists.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Invalid token.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid token.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=500,
     *          description="Internal error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *          )
     *       )
     * )
     */

    public function index(): JsonResponse
    {
        try {
            $data = $this->service->getUsers();

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Post(
     *      path="/users",
     *      operationId="createUser",
     *      tags={"Users"},
     *      summary="Create a user",
     *      description="Endpoint to create a user.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Yuri Fernandes"),
     *              @OA\Property(property="email", type="string", example="yuricaparelliofc@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success, user created.",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Yuri Fernandes"),
     *                  @OA\Property(property="email", type="string", example="yuricaparelliofc@gmail.com"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z")
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *          response=500,
     *          description="Internal error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *          )
     *       )
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $this->service->createUser($request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUser",
     *      tags={"Users"},
     *      summary="Get user by id",
     *      description="Endpoint to consult a user.",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success, user found.",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Yuri Fernandes"),
     *                  @OA\Property(property="email", type="string", example="yuricaprelliofc@gmail.com"),
     *                  @OA\Property(property="email_verified_at", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z")
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Not found.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User doesn't exists.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Invalid token.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid token.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=500,
     *          description="Internal error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *          )
     *       )
     * )
     */
    public function show(string $userEmail): JsonResponse
    {
        try {
            $data = $this->service->getUser($userEmail);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update user by id",
     *      description="Endpoint to update a user.",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Yuri Fernandes 123"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success, user updated.",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Yuri Fernandes 123"),
     *                  @OA\Property(property="email", type="string", example="yuricaparelliofc@gmail.com"),
     *                  @OA\Property(property="email_verified_at", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-14T01:57:56.000000Z")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User doesn't exists.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Insufficient permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="You don't have permission to update or delete this user.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid token.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid token.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *          )
     *      )
     * )
     */
    public function update(string $userId, UpdateUserRequest $request): JsonResponse
    {
        try {
            $data = $this->service->updateUser($userId, $request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete user by id",
     *      description="Endpoint to delete a user.",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success, user deleted.",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", example="User successfully deleted.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Not found.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User doesn't exists.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=403,
     *          description="Insufficient permission.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="You don't have permission to update or delete this user.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Invalid token.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid token.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=500,
     *          description="Internal error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *          )
     *       )
     * )
     */
    public function destroy(string $userId): JsonResponse
    {
        try {
            $data = $this->service->deleteUser($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }
}
