# CRITICAL: Update OpenAPI/Swagger Annotations for JSON:API Responses

## Priority: HIGH
## Status: TODO
## Estimated Effort: 4-6 hours

---

## Problem Statement

The REST API responses have been updated to be JSON:API compliant, but the Swagger/OpenAPI annotations have not been updated accordingly. This causes a mismatch between:
1. **Actual API Response**: JSON:API format with proper structure
2. **OpenAPI Specification**: Old format that doesn't match actual responses

### Example Discrepancy

**Actual Login Response** (JSON:API compliant):
```json
{
  "data": {
    "data": {
      "type": "users",
      "id": "1",
      "attributes": {
        "name": "Admin",
        "email": "amr.shah@gmail.com",
        "status": 1,
        "view_permission": "global",
        "role_id": 1,
        "created_at": "2025-12-15T17:31:48.000000Z",
        "updated_at": "2026-01-10T22:04:34.000000Z",
        "image": null,
        "image_url": null
      },
      "links": {
        "self": "http://localhost:8000/api/v1/users/1"
      }
    }
  },
  "message": "Login successful.",
  "token": "5|GqQdlJYVhOAD7bTR5LtzENH8oUauZqVWfUKZNQPiba4dddbe"
}
```

**Current OpenAPI Spec** (Incorrect):
```yaml
responses:
  200:
    content:
      application/json:
        schema:
          properties:
            message:
              type: string
            data:
              $ref: '#/components/schemas/User'
```

**Missing in OpenAPI**:
- `token` field (critical for authentication)
- JSON:API `data.data` wrapper structure
- `type` field
- `attributes` wrapper
- `links` object

---

## Impact

### Frontend Impact:
1. **Type Generation**: `openapi-typescript` generates incorrect TypeScript types
2. **Developer Experience**: IDE autocomplete shows wrong structure
3. **Runtime Errors**: Type mismatches at runtime
4. **Maintenance**: Manual type overrides needed (technical debt)

### Documentation Impact:
1. **Swagger UI**: Shows incorrect request/response examples
2. **API Consumers**: Third-party developers get wrong information
3. **Testing**: Automated tests based on OpenAPI spec will fail

---

## Affected Endpoints

### High Priority (Authentication & Core):
1. ✅ **POST /api/v1/login** - Missing `token` field and JSON:API structure
2. ⚠️ **GET /api/v1/get** (getCurrentUser) - Check JSON:API compliance
3. ⚠️ **DELETE /api/v1/logout** - Verify response structure

### Medium Priority (CRUD Operations):
4. ⚠️ **GET /api/v1/leads** - List responses
5. ⚠️ **GET /api/v1/leads/{id}** - Single resource responses
6. ⚠️ **POST /api/v1/leads** - Create responses
7. ⚠️ **PUT /api/v1/leads/{id}** - Update responses
8. ⚠️ **GET /api/v1/contacts/persons** - List responses
9. ⚠️ **GET /api/v1/contacts/organizations** - List responses
10. ⚠️ **GET /api/v1/sales-visits** - List responses

### Low Priority (Other Endpoints):
11. All other endpoints in `packages/Alamia/rest-api/src/Routes/`

---

## Solution Approach

### Phase 1: Create JSON:API Schema Components (2 hours)

**File**: `packages/Alamia/rest-api/src/Docs/Schemas/JsonApi.php`

```php
<?php

/**
 * @OA\Schema(
 *     schema="JsonApiResource",
 *     description="JSON:API single resource wrapper",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="type", type="string", example="users"),
 *         @OA\Property(property="id", type="string", example="1"),
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
 *             @OA\Property(property="self", type="string", format="uri")
 *         )
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiCollection",
 *     description="JSON:API collection wrapper",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="type", type="string"),
 *             @OA\Property(property="id", type="string"),
 *             @OA\Property(property="attributes", type="object"),
 *             @OA\Property(property="relationships", type="object"),
 *             @OA\Property(property="links", type="object")
 *         )
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="total", type="integer"),
 *         @OA\Property(property="page", type="integer"),
 *         @OA\Property(property="perPage", type="integer")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiError",
 *     description="JSON:API error response",
 *     @OA\Property(
 *         property="errors",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="code", type="string"),
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="detail", type="string"),
 *             @OA\Property(
 *                 property="source",
 *                 type="object",
 *                 @OA\Property(property="pointer", type="string"),
 *                 @OA\Property(property="parameter", type="string")
 *             )
 *         )
 *     )
 * )
 */
```

### Phase 2: Update Authentication Endpoints (1 hour)

**File**: `packages/Alamia/rest-api/src/Docs/Controllers/V1/AuthController.php`

```php
<?php

/**
 * @OA\Post(
 *     path="/api/v1/login",
 *     tags={"Authentication"},
 *     summary="Login admin user",
 *     description="Authenticate user and return access token with user data in JSON:API format",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"email", "password", "device_name"},
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     format="email",
 *                     example="admin@example.com"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     format="password",
 *                     example="admin123"
 *                 ),
 *                 @OA\Property(
 *                     property="device_name",
 *                     type="string",
 *                     example="web-app"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="type", type="string", example="users"),
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(
 *                     property="attributes",
 *                     ref="#/components/schemas/User"
 *                 ),
 *                 @OA\Property(
 *                     property="links",
 *                     type="object",
 *                     @OA\Property(property="self", type="string", format="uri")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Login successful."
 *             ),
 *             @OA\Property(
 *                 property="token",
 *                 type="string",
 *                 example="5|GqQdlJYVhOAD7bTR5LtzENH8oUauZqVWfUKZNQPiba4dddbe",
 *                 description="Sanctum API token for authentication"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid Email or Password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(ref="#/components/schemas/JsonApiError")
 *     )
 * )
 */
public function login(Request $request) {}

/**
 * @OA\Get(
 *     path="/api/v1/get",
 *     tags={"Authentication"},
 *     summary="Get logged in admin user's details",
 *     description="Retrieve current authenticated user in JSON:API format",
 *     security={{"sanctum_admin":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="type", type="string", example="users"),
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(
 *                     property="attributes",
 *                     ref="#/components/schemas/User"
 *                 ),
 *                 @OA\Property(
 *                     property="links",
 *                     type="object",
 *                     @OA\Property(property="self", type="string", format="uri")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(ref="#/components/schemas/JsonApiError")
 *     )
 * )
 */
public function get() {}
```

### Phase 3: Update CRUD Endpoints (2-3 hours)

**Files to Update**:
- `packages/Alamia/rest-api/src/Docs/Controllers/V1/LeadController.php`
- `packages/Alamia/rest-api/src/Docs/Controllers/V1/ContactController.php`
- `packages/Alamia/rest-api/src/Docs/Controllers/V1/SalesVisitController.php`

**Pattern for List Endpoints**:
```php
/**
 * @OA\Get(
 *     path="/api/v1/leads",
 *     @OA\Response(
 *         response=200,
 *         @OA\JsonContent(ref="#/components/schemas/JsonApiCollection")
 *     )
 * )
 */
```

**Pattern for Single Resource**:
```php
/**
 * @OA\Get(
 *     path="/api/v1/leads/{id}",
 *     @OA\Response(
 *         response=200,
 *         @OA\JsonContent(ref="#/components/schemas/JsonApiResource")
 *     )
 * )
 */
```

### Phase 4: Regenerate Documentation (15 minutes)

```bash
cd AlamiaConnect-Backend
php artisan l5-swagger:generate
```

Then regenerate frontend types:
```bash
cd ../AlamiaConnectKTD-nextjs
pnpm generate:api
```

---

## Implementation Checklist

### Preparation:
- [ ] Review all current API responses in Postman/Insomnia
- [ ] Document actual response structures
- [ ] Identify all endpoints that need updates

### Phase 1: JSON:API Schemas
- [ ] Create `packages/Alamia/rest-api/src/Docs/Schemas/JsonApi.php`
- [ ] Define `JsonApiResource` schema
- [ ] Define `JsonApiCollection` schema
- [ ] Define `JsonApiError` schema

### Phase 2: Authentication Endpoints
- [ ] Update `POST /api/v1/login` annotation
- [ ] Update `GET /api/v1/get` annotation
- [ ] Update `DELETE /api/v1/logout` annotation
- [ ] Add `token` field to login response
- [ ] Test Swagger UI displays correctly

### Phase 3: CRUD Endpoints
- [ ] Update all Lead endpoints
- [ ] Update all Contact (Person/Organization) endpoints
- [ ] Update all Sales Visit endpoints
- [ ] Update all Product endpoints
- [ ] Update all Quote endpoints
- [ ] Update all Activity endpoints

### Phase 4: Validation
- [ ] Regenerate Swagger documentation
- [ ] Verify Swagger UI shows correct examples
- [ ] Regenerate frontend TypeScript types
- [ ] Verify generated types match actual responses
- [ ] Test type safety in frontend code
- [ ] Update frontend `lib/api-types.ts` if needed

---

## Testing Strategy

### Backend Testing:
1. **Swagger UI**: Verify all examples match actual responses
2. **Postman**: Compare Swagger examples with real API calls
3. **JSON:API Validator**: Use online validator for response format

### Frontend Testing:
1. **Type Generation**: Ensure `pnpm generate:api` succeeds
2. **Type Imports**: Verify all types import without errors
3. **IDE Autocomplete**: Test autocomplete shows correct structure
4. **Runtime Testing**: Verify actual API calls match types

---

## Success Criteria

1. ✅ All Swagger annotations reflect actual JSON:API responses
2. ✅ `token` field present in login response schema
3. ✅ All list endpoints use `JsonApiCollection` schema
4. ✅ All single resource endpoints use `JsonApiResource` schema
5. ✅ Error responses use `JsonApiError` schema
6. ✅ Generated TypeScript types match actual responses
7. ✅ No manual type overrides needed in frontend
8. ✅ Swagger UI examples are accurate

---

## Related Files

### Backend:
- `packages/Alamia/rest-api/src/Docs/Schemas/` - Schema definitions
- `packages/Alamia/rest-api/src/Docs/Controllers/V1/` - Controller annotations
- `packages/Alamia/rest-api/src/Config/l5-swagger.php` - Swagger config

### Frontend:
- `types/api.d.ts` - Generated types (will be regenerated)
- `lib/api-types.ts` - Type helpers (may need updates)
- `services/auth-service.ts` - Uses login types

---

## Notes

- This is a **breaking change** for any external API consumers
- Consider versioning: `/api/v2/` if needed
- Update API documentation website if exists
- Notify any third-party integrators

---

## Estimated Timeline

- **Phase 1**: 2 hours (JSON:API schemas)
- **Phase 2**: 1 hour (Auth endpoints)
- **Phase 3**: 2-3 hours (CRUD endpoints)
- **Phase 4**: 15 minutes (Regeneration)
- **Testing**: 1 hour
- **Total**: 6-7 hours

---

## Priority

**CRITICAL** - This blocks accurate type generation for the frontend and affects API documentation quality.

**Recommended**: Complete this before Phase 2 of frontend implementation to ensure all types are correct from the start.
