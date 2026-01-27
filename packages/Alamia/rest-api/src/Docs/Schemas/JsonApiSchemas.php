<?php

namespace Alamia\RestApi\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="JsonApiResource",
 *     description="JSON:API single resource wrapper",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="Resource object",
 *         required={"type", "id"},
 *         @OA\Property(
 *             property="type",
 *             type="string",
 *             description="Resource type",
 *             example="users"
 *         ),
 *         @OA\Property(
 *             property="id",
 *             type="string",
 *             description="Resource ID",
 *             example="1"
 *         ),
 *         @OA\Property(
 *             property="attributes",
 *             type="object",
 *             description="Resource attributes"
 *         ),
 *         @OA\Property(
 *             property="relationships",
 *             type="object",
 *             description="Resource relationships"
 *         ),
 *         @OA\Property(
 *             property="links",
 *             type="object",
 *             @OA\Property(
 *                 property="self",
 *                 type="string",
 *                 format="uri",
 *                 description="Link to this resource",
 *                 example="http://localhost:8000/api/v1/users/1"
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="JsonApiCollection",
 *     description="JSON:API collection wrapper",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         description="Array of resource objects",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="type", type="string", description="Resource type"),
 *             @OA\Property(property="id", type="string", description="Resource ID"),
 *             @OA\Property(property="attributes", type="object", description="Resource attributes"),
 *             @OA\Property(property="relationships", type="object", description="Resource relationships"),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(property="self", type="string", format="uri")
 *             )
 *         )
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         description="Pagination metadata",
 *         @OA\Property(property="total", type="integer", description="Total number of resources", example=100),
 *         @OA\Property(property="page", type="integer", description="Current page number", example=1),
 *         @OA\Property(property="perPage", type="integer", description="Items per page", example=10),
 *         @OA\Property(property="lastPage", type="integer", description="Last page number", example=10),
 *         @OA\Property(property="from", type="integer", description="First item index", example=1),
 *         @OA\Property(property="to", type="integer", description="Last item index", example=10)
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         description="Pagination links",
 *         @OA\Property(property="first", type="string", format="uri", description="First page URL"),
 *         @OA\Property(property="last", type="string", format="uri", description="Last page URL"),
 *         @OA\Property(property="prev", type="string", format="uri", nullable=true, description="Previous page URL"),
 *         @OA\Property(property="next", type="string", format="uri", nullable=true, description="Next page URL"),
 *         @OA\Property(property="self", type="string", format="uri", description="Current page URL")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="JsonApiError",
 *     description="JSON:API error response",
 *     type="object",
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         description="Array of error objects",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="string", description="Unique error identifier"),
 *             @OA\Property(property="status", type="string", description="HTTP status code", example="400"),
 *             @OA\Property(property="code", type="string", description="Application-specific error code"),
 *             @OA\Property(property="title", type="string", description="Short error summary", example="Validation Error"),
 *             @OA\Property(property="detail", type="string", description="Detailed error message", example="The email field is required."),
 *             @OA\Property(
 *                 property="source",
 *                 type="object",
 *                 description="Error source information",
 *                 @OA\Property(property="pointer", type="string", description="JSON Pointer to error location", example="/data/attributes/email"),
 *                 @OA\Property(property="parameter", type="string", description="Query parameter that caused error")
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 description="Additional error metadata"
 *             )
 *         )
 *     )
 * )
 */
class JsonApiSchemas
{
    // This class only exists to hold OpenAPI annotations
    // No actual code is needed
}
