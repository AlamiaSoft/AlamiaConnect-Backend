<?php

namespace Alamia\RestApi\Docs\Controllers\Contact\Organizations;

class OrganizationController
{
    /**
     * @OA\Get(
     *     path="/api/v1/contacts/organizations",
     *     operationId="organizationsList",
     *     tags={"Contacts - Organizations"},
     *     summary="Get paginated list of organizations",
     *     description="Retrieve organizations in JSON:API format with pagination and filtering",
     *     security={ {"sanctum_admin": {} }},
     *
     *     @OA\Parameter(
     *          name="page",
     *          description="Page number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Parameter(
     *          name="perPage",
     *          description="Items per page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          description="Search query",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiCollection")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *      path="/api/v1/contacts/organizations/{id}",
     *      operationId="getParticularOrganization",
     *      tags={"Contacts - Organizations"},
     *      summary="Get a single organization by ID",
     *      description="Retrieve a specific organization in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Organization ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="organizations"),
     *                  @OA\Property(property="id", type="string", example="1"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Organization"),
     *                  @OA\Property(
     *                      property="links",
     *                      type="object",
     *                      @OA\Property(property="self", type="string", format="uri")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Organization not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *      path="/api/v1/contacts/organizations",
     *      operationId="storeOrganization",
     *      tags={"Contacts - Organizations"},
     *      summary="Create a new organization",
     *      description="Store a new organization and return in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(property="name", type="string", example="Acme Corporation"),
     *                  @OA\Property(
     *                      property="address",
     *                      type="object",
     *                      @OA\Property(property="address", type="string", example="123 Business St"),
     *                      @OA\Property(property="city", type="string", example="New York"),
     *                      @OA\Property(property="state", type="string", example="NY"),
     *                      @OA\Property(property="country", type="string", example="US"),
     *                      @OA\Property(property="postcode", type="string", example="10001")
     *                  ),
     *                  @OA\Property(property="website", type="string", format="uri", example="https://acme.com"),
     *                  @OA\Property(property="phone", type="string", example="+1234567890"),
     *                  @OA\Property(property="email", type="string", format="email", example="info@acme.com")
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Organization created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Organization created successfully."),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="organizations"),
     *                  @OA\Property(property="id", type="string"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Organization")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     *      path="/api/v1/contacts/organizations/{id}",
     *      operationId="updateOrganization",
     *      tags={"Contacts - Organizations"},
     *      summary="Update an existing organization",
     *      description="Update organization details and return in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Organization ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(
     *                      property="address",
     *                      type="object",
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="city", type="string"),
     *                      @OA\Property(property="state", type="string"),
     *                      @OA\Property(property="country", type="string"),
     *                      @OA\Property(property="postcode", type="string")
     *                  ),
     *                  @OA\Property(property="website", type="string", format="uri"),
     *                  @OA\Property(property="phone", type="string"),
     *                  @OA\Property(property="email", type="string", format="email")
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Organization updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Organization updated successfully."),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="organizations"),
     *                  @OA\Property(property="id", type="string"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Organization")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Organization not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *      path="/api/v1/contacts/organizations/{id}",
     *      operationId="deleteOrganization",
     *      tags={"Contacts - Organizations"},
     *      summary="Delete an organization",
     *      description="Remove an organization from the system",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Organization ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Organization deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Organization deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Organization not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function destroy() {}
}
