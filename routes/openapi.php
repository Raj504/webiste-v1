<?php

use App\Http\Controllers\Api\OpenApiController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// OpenAPI spec — no auth, it documents the API's shape, not any data.
//
// GET /api/openapi.json → live spec generated from the app's registered
// routes, for Apidog's scheduled URL import to pull.
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/openapi.json', [OpenApiController::class, 'spec']);
