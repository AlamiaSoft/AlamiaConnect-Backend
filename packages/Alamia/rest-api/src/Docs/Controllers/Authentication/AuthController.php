<?php

namespace Alamia\RestApi\Docs\Controllers\Authentication;

class AuthController
{
    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      operationId="adminLogin",
     *      tags={"Authentication"},
     *      summary="Login admin user",
     *      description="Authenticate user and return access token with user data in JSON:API format",
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"email", "password", "device_name"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="User email address",
     *                      example="admin@example.com"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="User password",
     *                      example="admin123"
     *                  ),
     *                  @OA\Property(
     *                      property="device_name",
     *                      type="string",
     *                      description="Device identifier for token",
     *                      example="web-app"
     *                  )
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful login",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  description="Outer data wrapper",
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      description="JSON:API resource",
     *                      @OA\Property(
     *                          property="type",
     *                          type="string",
     *                          example="users",
     *                          description="Resource type"
     *                      ),
     *                      @OA\Property(
     *                          property="id",
     *                          type="string",
     *                          example="1",
     *                          description="User ID"
     *                      ),
     *                      @OA\Property(
     *                          property="attributes",
     *                          ref="#/components/schemas/User",
     *                          description="User attributes"
     *                      ),
     *                      @OA\Property(
     *                          property="links",
     *                          type="object",
     *                          @OA\Property(
     *                              property="self",
     *                              type="string",
     *                              format="uri",
     *                              example="http://localhost:8000/api/v1/users/1"
     *                          )
     *                      )
     *                  )
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Login successful.",
     *                  description="Success message"
     *              ),
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="5|GqQdlJYVhOAD7bTR5LtzENH8oUauZqVWfUKZNQPiba4dddbe",
     *                  description="Sanctum API token for authentication"
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request - Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Invalid Email or Password"
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function login() {}
}
