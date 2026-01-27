<?php

namespace Alamia\RestApi\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Alamia\RestApi\Traits\JsonApiResponse;

class RestApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonApiResponse;
}
