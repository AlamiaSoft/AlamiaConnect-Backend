<?php

namespace Alamia\RestApi\Http\Controllers\V1\System;

use Alamia\RestApi\Http\Controllers\V1\Controller;
use App\Helpers\FeatureHelper;
use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    /**
     * Get the current system status and features.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'plan'     => FeatureHelper::getCurrentPlan(),
                'features' => FeatureHelper::getEnabledFeatures(),
                'version'  => '1.0.0',
            ]
        ]);
    }
}
