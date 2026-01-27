<?php

namespace Alamia\RestApi\Docs\Controllers\Contact\Persons;

class PersonController
{
    /**
     * @OA\Get(
     *     path="/api/v1/contacts/persons",
     *     operationId="personsList",
     *     tags={"Contacts - Persons"},
     *     summary="Get paginated list of persons",
     *     description="Retrieve persons in JSON:API format with pagination and filtering",
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
     *      path="/api/v1/contacts/persons/{id}",
     *      operationId="getParticularPerson",
     *      tags={"Contacts - Persons"},
     *      summary="Get a single person by ID",
     *      description="Retrieve a specific person in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Person ID",
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
     *                  @OA\Property(property="type", type="string", example="persons"),
     *                  @OA\Property(property="id", type="string", example="1"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Person"),
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
     *          description="Person not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *      path="/api/v1/contacts/persons",
     *      operationId="storePerson",
     *      tags={"Contacts - Persons"},
     *      summary="Create a new person",
     *      description="Store a new person and return in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(
     *                      property="emails",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="label", type="string", example="work"),
     *                          @OA\Property(property="value", type="string", example="john@example.com")
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="contact_numbers",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="label", type="string", example="work"),
     *                          @OA\Property(property="value", type="string", example="+1234567890")
     *                      )
     *                  ),
     *                  @OA\Property(property="job_title", type="string", example="Sales Manager"),
     *                  @OA\Property(property="organization_id", type="integer", example=5)
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Person created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Person created successfully."),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="persons"),
     *                  @OA\Property(property="id", type="string"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Person")
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
     *      path="/api/v1/contacts/persons/{id}",
     *      operationId="updatePerson",
     *      tags={"Contacts - Persons"},
     *      summary="Update an existing person",
     *      description="Update person details and return in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Person ID",
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
     *                      property="emails",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="label", type="string"),
     *                          @OA\Property(property="value", type="string")
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="contact_numbers",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(property="label", type="string"),
     *                          @OA\Property(property="value", type="string")
     *                      )
     *                  ),
     *                  @OA\Property(property="job_title", type="string"),
     *                  @OA\Property(property="organization_id", type="integer")
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Person updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Person updated successfully."),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="persons"),
     *                  @OA\Property(property="id", type="string"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Person")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Person not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *      path="/api/v1/contacts/persons/{id}",
     *      operationId="deletePerson",
     *      tags={"Contacts - Persons"},
     *      summary="Delete a person",
     *      description="Remove a person from the system",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Person ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Person deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Person deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Person not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function destroy() {}
}
