<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthController
 *
 * This class is responsible for handling authentication operations.
 */
class AuthController extends Controller
{
    /**
     * @var AuthService Authentication service
     */
    private AuthService $service;

    /**
     * AuthController class constructor
     *
     * @param AuthService $service Authentication service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *      path="/token",
     *      operationId="generateToken",
     *      tags={"Authentication"},
     *      summary="Generate the token for authentication.",
     *      description="Endpoint to generate the token used for authenticating requests. It is necessary to first create a user and then generate the token with their respective data.",
     *		@OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     * 				type="object",
     * 				@OA\Property(property="email", type="string", example="yuricaparelliofc@gmail.com"),
     * 				@OA\Property(property="password", type="string", example="142536")
     * 			)
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success, token generated.",
     *          @OA\JsonContent(
     * 				@OA\Property(property="data", type="string", example="{{token}}")
     * 			)
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Not found.",
     * 			@OA\JsonContent(
     * 				@OA\Property(property="error", type="string", example="User doesn't exists.")
     * 			)
     *       ),
     *       @OA\Response(
     *          response=422,
     *          description="Invalid password.",
     * 			@OA\JsonContent(
     * 				@OA\Property(property="error", type="string", example="Invalid password.")
     * 			)
     *       ),
     * 		 @OA\Response(
     *     	    response=500,
     *     		description="Internal error.",
     *     	 	@OA\JsonContent(
     *         		@OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *     	 	)
     * 		 )
     * )
     */
    public function generateToken(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->service->generateToken($request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="disconnectUser",
     *      tags={"Authentication"},
     *      summary="Log out the authenticated user.",
     *      description="Endpoint to disconnect the user.",
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Success, user disconnected.",
     *          @OA\JsonContent(
     * 				@OA\Property(property="data", type="string", example="Successfully disconnected.")
     * 			)
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Invalid token.",
     * 			@OA\JsonContent(
     * 				@OA\Property(property="error", type="string", example="Invalid token.")
     * 			)
     *       ),
     * 		 @OA\Response(
     *     	    response=500,
     *     		description="Internal error.",
     *     	 	@OA\JsonContent(
     *         		@OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
     *     	 	)
     * 		 )
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            $data = $this->service->logout();

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }
}