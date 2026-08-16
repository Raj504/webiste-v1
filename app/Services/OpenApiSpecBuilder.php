<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

/**
 * Generates a live OpenAPI 3.0 spec from the app's actual registered routes,
 * served at GET /api/openapi.json for Apidog's scheduled URL import to pull.
 *
 * Paths/methods are always accurate because they come straight from
 * Route::getRoutes() at request time — they can't drift out of sync with the
 * real API even if nobody remembers to update this file. A metadata map adds
 * summaries/tags/request schemas on top, with a generic fallback for any
 * route not (yet) listed there.
 *
 * To document a new endpoint well: add one entry to endpointMetadata() keyed
 * by "{ControllerClass}@{method}" — either 'formRequest' => SomeRequest::class
 * (schema auto-derived from its rules()) or 'schema' => [...] (manual, for
 * endpoints without a FormRequest, e.g. BookingController's inline-validated
 * actions).
 */
class OpenApiSpecBuilder
{
    private const TAGS = [
        'Auth', 'Gyms', 'Owner - Plans', 'Owner - Amenities', 'Owner - Members',
        'Bookings', 'Webhooks', 'Health',
    ];

    /**
     * Endpoints whose controllers predate the standard ApiResponse envelope
     * and just return a bare {"data": ...} shape.
     */
    private const SIMPLE_DATA_ACTIONS = [
        'App\Http\Controllers\GymController@show',
        'App\Http\Controllers\GymController@plans',
        'App\Http\Controllers\GymController@update',
        'App\Http\Controllers\GymController@updateOperatingHours',
    ];

    public function build(): array
    {
        $paths = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();

            if (!str_starts_with($uri, 'api/') || $uri === 'api/openapi.json') {
                continue;
            }

            $action = ltrim($route->getActionName(), '\\');
            $action = $action === 'Closure' ? null : $action;

            $meta = $action ? ($this->endpointMetadata()[$action] ?? null) : null;
            $middleware = $route->gatherMiddleware();
            $requiresAuth = in_array('auth:sanctum', $middleware, true);

            $path = '/' . preg_replace('#^api/#', '', $uri);

            foreach ($route->methods() as $method) {
                if ($method === 'HEAD') {
                    continue;
                }

                $operation = [
                    'summary'   => $meta['summary'] ?? $this->fallbackSummary($method, $uri, $action),
                    'tags'      => [$meta['tag'] ?? $this->fallbackTag($uri)],
                    'responses' => $this->responsesFor($action),
                ];

                if ($requiresAuth) {
                    $operation['security'] = [['bearerAuth' => []]];
                }

                $parameters = [];
                if (preg_match_all('/\{([a-zA-Z_]+)\}/', $uri, $m)) {
                    foreach ($m[1] as $paramName) {
                        $parameters[] = [
                            'name'     => $paramName,
                            'in'       => 'path',
                            'required' => true,
                            'schema'   => ['type' => 'integer'],
                        ];
                    }
                }

                $schema = $this->requestSchemaFor($meta, $method);

                if ($schema !== null) {
                    if (in_array($method, ['GET', 'DELETE'], true)) {
                        $parameters = array_merge($parameters, $this->schemaToQueryParams($schema));
                    } else {
                        $operation['requestBody'] = [
                            'content' => ['application/json' => ['schema' => $schema]],
                        ];
                    }
                }

                if (!empty($parameters)) {
                    $operation['parameters'] = $parameters;
                }

                $paths[$path][strtolower($method)] = $operation;
            }
        }

        ksort($paths);

        return [
            'openapi' => '3.0.3',
            'info' => [
                'title'       => 'GymPass India API',
                'version'     => '1.0.0',
                'description' => "Auto-generated from the app's registered routes on every request — always reflects the live API, never a stale hand-maintained copy.",
            ],
            'servers' => [
                ['url' => rtrim(config('app.url'), '/') . '/api'],
            ],
            'tags' => array_map(fn ($t) => ['name' => $t], self::TAGS),
            'paths' => $paths,
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => ['type' => 'http', 'scheme' => 'bearer'],
                ],
                'schemas' => $this->responseSchemas(),
            ],
        ];
    }

    // ── Endpoint metadata ────────────────────────────────────────────────────

    /**
     * Add an entry here whenever a new endpoint should show up in Apidog with
     * a real summary/tag/request schema instead of a generic fallback.
     */
    private function endpointMetadata(): array
    {
        return [
            // ── Auth ─────────────────────────────────────────────────────────
            'App\Http\Controllers\Api\Auth\AuthController@sendOtp' => [
                'summary' => 'Send OTP to a phone number',
                'tag'     => 'Auth',
                'request' => ['formRequest' => \App\Http\Requests\Auth\SendOtpRequest::class],
            ],
            'App\Http\Controllers\Api\Auth\AuthController@verifyOtp' => [
                'summary' => 'Verify OTP, returns a single-use temp_token for registration',
                'tag'     => 'Auth',
                'request' => ['formRequest' => \App\Http\Requests\Auth\VerifyOtpRequest::class],
            ],
            'App\Http\Controllers\Api\Auth\AuthController@registerTraveler' => [
                'summary' => 'Register a traveler using a temp_token',
                'tag'     => 'Auth',
                'request' => ['formRequest' => \App\Http\Requests\Auth\RegisterTravelerRequest::class],
            ],
            'App\Http\Controllers\Api\Auth\AuthController@registerOwner' => [
                'summary' => 'Register a gym owner + their gym (with default plans) using a temp_token',
                'tag'     => 'Auth',
                'request' => ['formRequest' => \App\Http\Requests\Auth\RegisterOwnerRequest::class],
            ],
            'App\Http\Controllers\Api\Auth\LoginController@sendOtp' => [
                'summary' => 'Send OTP for login',
                'tag'     => 'Auth',
                'request' => ['formRequest' => \App\Http\Requests\Auth\SendOtpRequest::class],
            ],
            'App\Http\Controllers\Api\Auth\LoginController@login' => [
                'summary' => 'Verify OTP and log in, returns a Sanctum access_token',
                'tag'     => 'Auth',
                'request' => ['formRequest' => \App\Http\Requests\Auth\LoginRequest::class],
            ],
            'App\Http\Controllers\Api\Auth\LoginController@logout' => [
                'summary' => 'Revoke the current access token',
                'tag'     => 'Auth',
            ],
            'App\Http\Controllers\Api\Auth\LoginController@me' => [
                'summary' => 'Get the current authenticated user',
                'tag'     => 'Auth',
            ],

            // ── Gyms ─────────────────────────────────────────────────────────
            'App\Http\Controllers\Api\NearbyGymController@index' => [
                'summary' => 'Search nearby gyms (haversine distance)',
                'tag'     => 'Gyms',
                'request' => ['formRequest' => \App\Http\Requests\NearbyGymRequest::class],
            ],
            'App\Http\Controllers\GymController@plans' => [
                'summary' => 'Get plans for a gym',
                'tag'     => 'Gyms',
            ],
            'App\Http\Controllers\GymController@show' => [
                'summary' => 'Get gym detail',
                'tag'     => 'Gyms',
            ],
            'App\Http\Controllers\GymController@update' => [
                'summary' => "Update the authenticated owner's gym info",
                'tag'     => 'Gyms',
                'request' => ['formRequest' => \App\Http\Requests\UpdateGymRequest::class],
            ],
            'App\Http\Controllers\GymController@updateOperatingHours' => [
                'summary' => 'Save the weekly operating-hours schedule',
                'tag'     => 'Gyms',
                'request' => ['formRequest' => \App\Http\Requests\UpdateGymOperatingHoursRequest::class],
            ],
            'App\Http\Controllers\GymController@operatingHours' => [
                'summary' => 'Get the weekly operating-hours schedule',
                'tag'     => 'Gyms',
            ],

            // ── Owner - Plans ────────────────────────────────────────────────
            'App\Http\Controllers\Api\Owner\GymPlanController@index' => [
                'summary' => "List all plans for the owner's gym",
                'tag'     => 'Owner - Plans',
            ],
            'App\Http\Controllers\Api\Owner\GymPlanController@store' => [
                'summary' => 'Create a custom plan',
                'tag'     => 'Owner - Plans',
                'request' => ['formRequest' => \App\Http\Requests\Owner\CreateGymPlanRequest::class],
            ],
            'App\Http\Controllers\Api\Owner\GymPlanController@update' => [
                'summary' => "Update a plan's price or enabled status",
                'tag'     => 'Owner - Plans',
                'request' => ['formRequest' => \App\Http\Requests\Owner\UpdateGymPlanRequest::class],
            ],
            'App\Http\Controllers\Api\Owner\GymPlanController@destroy' => [
                'summary' => 'Delete a custom plan (default plans cannot be deleted)',
                'tag'     => 'Owner - Plans',
            ],

            // ── Owner - Amenities ────────────────────────────────────────────
            'App\Http\Controllers\Api\Owner\GymAmenityController@index' => [
                'summary' => "List all amenities + which are selected for the owner's gym",
                'tag'     => 'Owner - Amenities',
            ],
            'App\Http\Controllers\Api\Owner\GymAmenityController@sync' => [
                'summary' => "Save the gym's amenity selection",
                'tag'     => 'Owner - Amenities',
                'request' => ['formRequest' => \App\Http\Requests\Owner\SyncAmenitiesRequest::class],
            ],
            'App\Http\Controllers\Api\Owner\GymAmenityController@addCustom' => [
                'summary' => 'Add a new amenity to the global list',
                'tag'     => 'Owner - Amenities',
                'request' => ['formRequest' => \App\Http\Requests\Owner\AddCustomAmenityRequest::class],
            ],

            // ── Owner - Members ──────────────────────────────────────────────
            'App\Http\Controllers\Api\Owner\GymMemberController@index' => [
                'summary' => 'List members (manual + booking-sourced), soonest due first',
                'tag'     => 'Owner - Members',
            ],
            'App\Http\Controllers\Api\Owner\GymMemberController@store' => [
                'summary' => 'Add a member, or renew if a member with this phone already exists for the gym',
                'tag'     => 'Owner - Members',
                'request' => ['formRequest' => \App\Http\Requests\Owner\AddGymMemberRequest::class],
            ],
            'App\Http\Controllers\Api\Owner\GymMemberController@update' => [
                'summary' => 'Edit a member, and/or renew (send start_date + duration_type together to recompute due_date)',
                'tag'     => 'Owner - Members',
                'request' => ['formRequest' => \App\Http\Requests\Owner\UpdateGymMemberRequest::class],
            ],
            'App\Http\Controllers\Api\Owner\GymMemberController@destroy' => [
                'summary' => 'Remove a member',
                'tag'     => 'Owner - Members',
            ],
            'App\Http\Controllers\Api\Owner\GymMemberController@sendReminder' => [
                'summary' => 'Send a renewal-reminder email to a member (requires an email on file)',
                'tag'     => 'Owner - Members',
            ],

            // ── Bookings ─────────────────────────────────────────────────────
            'App\Http\Controllers\Api\BookingController@index' => [
                'summary' => "List the authenticated traveler's bookings",
                'tag'     => 'Bookings',
            ],
            'App\Http\Controllers\Api\BookingController@show' => [
                'summary' => 'Get booking detail (+ QR code)',
                'tag'     => 'Bookings',
            ],
            'App\Http\Controllers\Api\BookingController@createOrder' => [
                'summary' => 'Step 1: create a Razorpay order for a plan (creates a pending booking)',
                'tag'     => 'Bookings',
                'request' => ['schema' => [
                    'type'       => 'object',
                    'required'   => ['plan_id'],
                    'properties' => [
                        'plan_id' => ['type' => 'integer'],
                    ],
                ]],
            ],
            'App\Http\Controllers\Api\BookingController@verifyPayment' => [
                'summary' => 'Step 2: verify the Razorpay checkout signature and activate the booking',
                'tag'     => 'Bookings',
                'request' => ['schema' => [
                    'type'       => 'object',
                    'required'   => ['booking_id', 'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature'],
                    'properties' => [
                        'booking_id'          => ['type' => 'integer'],
                        'razorpay_order_id'   => ['type' => 'string'],
                        'razorpay_payment_id' => ['type' => 'string'],
                        'razorpay_signature'  => ['type' => 'string'],
                    ],
                ]],
            ],

            // ── Webhooks ─────────────────────────────────────────────────────
            'App\Http\Controllers\Api\RazorpayWebhookController@handle' => [
                'summary' => "Razorpay payment event webhook — called by Razorpay's servers, not the FE. Backend safety net for verify-payment.",
                'tag'     => 'Webhooks',
                'request' => ['schema' => [
                    'type'       => 'object',
                    'properties' => [
                        'event'   => ['type' => 'string', 'example' => 'payment.captured'],
                        'payload' => [
                            'type'       => 'object',
                            'properties' => [
                                'payment' => [
                                    'type'       => 'object',
                                    'properties' => [
                                        'entity' => [
                                            'type'       => 'object',
                                            'properties' => [
                                                'id'       => ['type' => 'string'],
                                                'order_id' => ['type' => 'string'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]],
            ],
        ];
    }

    // ── Request schema resolution ────────────────────────────────────────────

    private function requestSchemaFor(?array $meta, string $method): ?array
    {
        $source = $meta['request'] ?? null;

        if ($source !== null) {
            if (isset($source['formRequest'])) {
                return $this->schemaFromFormRequest($source['formRequest']);
            }

            if (isset($source['schema'])) {
                return $source['schema'];
            }
        }

        // No explicit metadata at all for a body-bearing method — generic fallback
        // so the route still shows up with the right shape of operation.
        if ($meta === null && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            return ['type' => 'object'];
        }

        return null;
    }

    private function schemaFromFormRequest(string $formRequestClass): array
    {
        try {
            $rules = (new $formRequestClass())->rules();
        } catch (\Throwable $e) {
            return ['type' => 'object'];
        }

        return $this->rulesToSchema($rules);
    }

    private function rulesToSchema(array $rules): array
    {
        $properties = [];
        $required = [];
        $arrayItemProps = [];

        foreach ($rules as $field => $fieldRules) {
            $fieldRules = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;

            // One level of "field.*.subfield" (array-of-objects) support.
            if (preg_match('/^([a-zA-Z_]+)\.\*\.([a-zA-Z_]+)$/', (string) $field, $m)) {
                [$prop] = $this->singleFieldSchema($fieldRules);
                $arrayItemProps[$m[1]][$m[2]] = $prop;
                continue;
            }

            if (str_contains((string) $field, '.*')) {
                continue;
            }

            [$prop, $isRequired] = $this->singleFieldSchema($fieldRules);
            $properties[$field] = $prop;

            if ($isRequired) {
                $required[] = $field;
            }
        }

        foreach ($arrayItemProps as $arrayField => $itemProps) {
            if (($properties[$arrayField]['type'] ?? null) === 'array') {
                $properties[$arrayField]['items'] = ['type' => 'object', 'properties' => $itemProps];
            }
        }

        $schema = ['type' => 'object', 'properties' => $properties];

        if (!empty($required)) {
            $schema['required'] = $required;
        }

        return $schema;
    }

    /**
     * @return array{0: array, 1: bool} [propertySchema, isRequired]
     */
    private function singleFieldSchema(array $fieldRules): array
    {
        $prop = ['type' => 'string'];
        $isRequired = false;
        $isNullable = false;

        foreach ($fieldRules as $rule) {
            if (!is_string($rule)) {
                continue; // Rule objects/closures — no generic way to introspect, skip
            }

            $ruleName = explode(':', $rule, 2)[0];
            $ruleArgs = str_contains($rule, ':') ? explode(',', explode(':', $rule, 2)[1]) : [];

            switch ($ruleName) {
                case 'required':
                    $isRequired = true;
                    break;
                case 'required_if':
                    // Conditionally required (e.g. "required_if:duration_type,custom") — OpenAPI's
                    // plain `required` array can't express the condition, so leave it optional
                    // there but note the condition in the description rather than overstating it.
                    if (count($ruleArgs) >= 2) {
                        $prop['description'] = "Required when {$ruleArgs[0]} is \"{$ruleArgs[1]}\".";
                    }
                    break;
                case 'nullable':
                    $isNullable = true;
                    break;
                case 'string':
                    $prop['type'] = 'string';
                    break;
                case 'integer':
                    $prop['type'] = 'integer';
                    break;
                case 'numeric':
                    $prop['type'] = 'number';
                    break;
                case 'boolean':
                case 'accepted':
                    $prop['type'] = 'boolean';
                    break;
                case 'array':
                    $prop['type'] = 'array';
                    $prop['items'] = ['type' => 'string'];
                    break;
                case 'email':
                    $prop['type'] = 'string';
                    $prop['format'] = 'email';
                    break;
                case 'date':
                    $prop['type'] = 'string';
                    $prop['format'] = 'date';
                    break;
                case 'date_format':
                    $prop['type'] = 'string';
                    if (isset($ruleArgs[0])) {
                        $prop['example'] = $ruleArgs[0];
                    }
                    break;
                case 'digits':
                case 'size':
                    if (isset($ruleArgs[0]) && ($prop['type'] ?? 'string') === 'string') {
                        $prop['minLength'] = $prop['maxLength'] = (int) $ruleArgs[0];
                    }
                    break;
                case 'in':
                    $prop['enum'] = $ruleArgs;
                    break;
                case 'max':
                    if (isset($ruleArgs[0])) {
                        if (($prop['type'] ?? 'string') === 'string') {
                            $prop['maxLength'] = (int) $ruleArgs[0];
                        } else {
                            $prop['maximum'] = (float) $ruleArgs[0];
                        }
                    }
                    break;
                case 'min':
                    if (isset($ruleArgs[0])) {
                        if (($prop['type'] ?? 'string') === 'string') {
                            $prop['minLength'] = (int) $ruleArgs[0];
                        } else {
                            $prop['minimum'] = (float) $ruleArgs[0];
                        }
                    }
                    break;
                case 'between':
                    if (count($ruleArgs) === 2) {
                        $prop['minimum'] = (float) $ruleArgs[0];
                        $prop['maximum'] = (float) $ruleArgs[1];
                    }
                    break;
                default:
                    // e.g. exists/unique/size/confirmed — no OpenAPI equivalent, ignored on purpose
                    break;
            }
        }

        if ($isNullable) {
            $prop['nullable'] = true;
        }

        return [$prop, $isRequired];
    }

    private function schemaToQueryParams(array $schema): array
    {
        $required = $schema['required'] ?? [];
        $params = [];

        foreach ($schema['properties'] ?? [] as $name => $propSchema) {
            $params[] = [
                'name'     => $name,
                'in'       => 'query',
                'required' => in_array($name, $required, true),
                'schema'   => $propSchema,
            ];
        }

        return $params;
    }

    // ── Fallbacks ─────────────────────────────────────────────────────────────

    private function fallbackSummary(string $method, string $uri, ?string $action): string
    {
        return $action ? "$method $action" : "$method /$uri";
    }

    private function fallbackTag(string $uri): string
    {
        $trimmed = trim(preg_replace('#^api/#', '', $uri), '/');
        $segments = explode('/', $trimmed);

        return ucfirst($segments[0] ?: 'Other');
    }

    // ── Response schemas ──────────────────────────────────────────────────────

    private function responsesFor(?string $action): array
    {
        $successSchemaRef = in_array($action, self::SIMPLE_DATA_ACTIONS, true)
            ? '#/components/schemas/SimpleDataResponse'
            : '#/components/schemas/SuccessResponse';

        return [
            '200' => [
                'description' => 'Success',
                'content'     => ['application/json' => ['schema' => ['$ref' => $successSchemaRef]]],
            ],
            '400' => [
                'description' => 'Bad request / business-logic error',
                'content'     => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/ErrorResponse']]],
            ],
            '401' => [
                'description' => 'Unauthenticated',
                'content'     => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/ErrorResponse']]],
            ],
            '422' => [
                'description' => 'Validation error',
                'content'     => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/ValidationErrorResponse']]],
            ],
        ];
    }

    private function responseSchemas(): array
    {
        return [
            'SuccessResponse' => [
                'type'       => 'object',
                'properties' => [
                    'success' => ['type' => 'boolean', 'example' => true],
                    'code'    => ['type' => 'string'],
                    'message' => ['type' => 'string'],
                    'data'    => ['type' => 'object', 'nullable' => true],
                ],
            ],
            'SimpleDataResponse' => [
                'type'        => 'object',
                'description' => 'A few older endpoints (GymController) predate the standard envelope and just return this bare shape.',
                'properties'  => [
                    'data' => ['type' => 'object', 'nullable' => true],
                ],
            ],
            'ErrorResponse' => [
                'type'       => 'object',
                'properties' => [
                    'success' => ['type' => 'boolean', 'example' => false],
                    'code'    => ['type' => 'string'],
                    'message' => ['type' => 'string'],
                    'errors'  => ['type' => 'object', 'nullable' => true],
                ],
            ],
            'ValidationErrorResponse' => [
                'type'       => 'object',
                'properties' => [
                    'success' => ['type' => 'boolean', 'example' => false],
                    'code'    => ['type' => 'string', 'example' => 'validation_error'],
                    'message' => ['type' => 'string'],
                    'errors'  => ['type' => 'object', 'description' => 'Map of field name to array of error messages'],
                ],
            ],
        ];
    }
}
