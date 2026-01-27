<?php

namespace Alamia\RestApi\Docs\Controllers\Lead;

class LeadController
{
    /**
     * @OA\Get(
     *     path="/api/v1/leads",
     *     operationId="leadList",
     *     tags={"Leads"},
     *     summary="Get paginated list of leads",
     *     description="Retrieve leads in JSON:API format with pagination, sorting, and filtering",
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
     *          name="sortBy",
     *          description="Sort column",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string", example="created_at")
     *      ),
     *      @OA\Parameter(
     *          name="sortOrder",
     *          description="Sort order",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string", enum={"asc", "desc"}, example="desc")
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
     *      path="/api/v1/leads/{id}",
     *      operationId="getParticularLead",
     *      tags={"Leads"},
     *      summary="Get a single lead by ID",
     *      description="Retrieve a specific lead in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *                  description="JSON:API resource",
     *                  @OA\Property(property="type", type="string", example="leads"),
     *                  @OA\Property(property="id", type="string", example="1"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Lead"),
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
     *          description="Lead not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *      path="/api/v1/leads",
     *      operationId="storeLead",
     *      tags={"Leads"},
     *      summary="Create a new lead",
     *      description="Store a new lead and return it in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"title", "description", "lead_value", "lead_source_id", "lead_type_id"},
     *                  @OA\Property(property="title", type="string", example="New Business Lead"),
     *                  @OA\Property(property="description", type="string", example="Lead from website inquiry"),
     *                  @OA\Property(property="lead_value", type="number", example=50000),
     *                  @OA\Property(property="lead_source_id", type="integer", enum={1,2,3,4,5}, example=2),
     *                  @OA\Property(property="lead_type_id", type="integer", enum={1,2}, example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *                  @OA\Property(property="expected_close_date", type="string", format="date", example="2024-12-31"),
     *                  @OA\Property(property="person_id", type="integer", example=5, description="ID of existing person. If provided, person object is ignored unless updating."),
     *                  @OA\Property(
     *                      property="person",
     *                      type="object",
     *                      description="Person details for creating a new person or updating existing",
     *                      @OA\Property(property="name", type="string", example="John Doe"),
     *                      @OA\Property(
     *                          property="emails",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="value", type="string", example="john@example.com"),
     *                              @OA\Property(property="label", type="string", example="work")
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="contact_numbers",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="value", type="string", example="+1234567890"),
     *                              @OA\Property(property="label", type="string", example="work")
     *                          )
     *                      ),
     *                      @OA\Property(property="organization_id", type="integer", example=1)
     *                  ),
     *                  @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          required={"product_id", "quantity", "price"},
     *                          @OA\Property(property="product_id", type="integer", example=1),
     *                          @OA\Property(property="name", type="string", example="Hosting Plan"),
     *                          @OA\Property(property="quantity", type="integer", example=1),
     *                          @OA\Property(property="price", type="number", example=100.00),
     *                          @OA\Property(property="amount", type="number", example=100.00)
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Lead created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Lead created successfully."),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="leads"),
     *                  @OA\Property(property="id", type="string", example="1"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Lead"),
     *                  @OA\Property(
     *                      property="links",
     *                      type="object",
     *                      @OA\Property(property="self", type="string", format="uri")
     *                  )
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
     *      path="/api/v1/leads/{id}",
     *      operationId="updateLead",
     *      tags={"Leads"},
     *      summary="Update an existing lead",
     *      description="Update lead details and return in JSON:API format",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
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
     *                  @OA\Property(property="title", type="string"),
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="lead_value", type="number"),
     *                  @OA\Property(property="lead_source_id", type="integer", enum={1,2,3,4,5}),
     *                  @OA\Property(property="lead_type_id", type="integer", enum={1,2}),
     *                  @OA\Property(property="user_id", type="integer"),
     *                  @OA\Property(property="expected_close_date", type="string", format="date"),
     *                  @OA\Property(property="person_id", type="integer", description="ID of existing person. If provided, person object is ignored unless updating."),
     *                  @OA\Property(
     *                      property="person",
     *                      type="object",
     *                      description="Person details for creating a new person or updating existing",
     *                      @OA\Property(property="name", type="string", example="John Doe"),
     *                      @OA\Property(
     *                          property="emails",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="value", type="string", example="john@example.com"),
     *                              @OA\Property(property="label", type="string", example="work")
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="contact_numbers",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="value", type="string", example="+1234567890"),
     *                              @OA\Property(property="label", type="string", example="work")
     *                          )
     *                      ),
     *                      @OA\Property(property="organization_id", type="integer", example=1)
     *                  ),
     *                  @OA\Property(
     *                      property="products",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          required={"product_id", "quantity", "price"},
     *                          @OA\Property(property="product_id", type="integer", example=1),
     *                          @OA\Property(property="name", type="string", example="Hosting Plan"),
     *                          @OA\Property(property="quantity", type="integer", example=1),
     *                          @OA\Property(property="price", type="number", example=100.00),
     *                          @OA\Property(property="amount", type="number", example=100.00)
     *                      )
     *                  )
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Lead updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Lead updated successfully."),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="type", type="string", example="leads"),
     *                  @OA\Property(property="id", type="string"),
     *                  @OA\Property(property="attributes", ref="#/components/schemas/Lead")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Lead not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *      path="/api/v1/leads/{id}",
     *      operationId="deleteLead",
     *      tags={"Leads"},
     *      summary="Delete a lead",
     *      description="Remove a lead from the system",
     *      security={ {"sanctum_admin": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Lead ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Lead deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Lead deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Lead not found",
     *          @OA\JsonContent(ref="#/components/schemas/JsonApiError")
     *      )
     * )
     */
    public function destroy() {}
}
