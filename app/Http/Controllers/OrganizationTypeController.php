<?php

namespace App\Http\Controllers;

use App\Services\OrganizationTypeService;
use App\Http\Requests\OrganizationType\IndexRequest;
use App\Http\Requests\OrganizationType\StoreRequest;
use Illuminate\Http\JsonResponse;

class OrganizationTypeController extends Controller
{
    public function __construct(private readonly OrganizationTypeService $service) {}

    public function index(IndexRequest $request): JsonResponse
    {
        $items = $this->service->getAllTypes(
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $type = $this->service->createType(
            $request->string('title'),
            $request->string('name'),
            $request->filled('parent_uuid') ? (string)$request->input('parent_uuid') : null
        );

        return response()->json(['data' => $type], 201);
    }
}
