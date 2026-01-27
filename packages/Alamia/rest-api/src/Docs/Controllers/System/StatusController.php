<?php

namespace Alamia\RestApi\Docs\Controllers\System;

class StatusController
{
    /**
     * @OA\Get(
     *     path="/api/v1/system/status",
     *     operationId="getSystemStatus",
     *     tags={"System"},
     *     summary="Get technical system status and enabled features",
     *     description="Returns the current subscription plan and the list of features enabled for that plan.",
     *     security={ {"sanctum": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="plan", type="string", example="enterprise"),
     *                 @OA\Property(
     *                     property="features",
     *                     type="object",
     *                     @OA\Property(property="leads", type="boolean", example=true),
     *                     @OA\Property(property="contacts", type="boolean", example=true),
     *                     @OA\Property(property="reporting", type="string", example="advanced"),
     *                     @OA\Property(property="automation", type="boolean", example=true)
     *                 ),
     *                 @OA\Property(property="version", type="string", example="1.0.0")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index() {}
}
