<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OpenApiSpecBuilder;
use Illuminate\Http\JsonResponse;

class OpenApiController extends Controller
{
    /**
     * GET /api/openapi.json
     *
     * Live OpenAPI 3.0 spec generated from the app's actual registered
     * routes — point Apidog's scheduled URL import at this endpoint.
     */
    public function spec(OpenApiSpecBuilder $builder): JsonResponse
    {
        return response()->json($builder->build());
    }
}
