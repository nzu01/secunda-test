<?php

namespace App\Http\Controllers;

use App\Services\BuildingService;
use App\Http\Requests\Building\IndexRequest;
use Illuminate\Http\JsonResponse;

class BuildingController extends Controller
{
    public function __construct(private readonly BuildingService $service) {}

    public function index(IndexRequest $request): JsonResponse
    {
        $items = $this->service->getAllBuildings(
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }
}
