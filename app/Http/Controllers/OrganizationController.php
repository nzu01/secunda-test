<?php


namespace App\Http\Controllers;

use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Organization\ByBuildingRequest;
use App\Http\Requests\Organization\ByTypeRequest;
use App\Http\Requests\Organization\ByTypeTitleRequest;
use App\Http\Requests\Organization\WithinRadiusRequest;
use App\Http\Requests\Organization\SearchByTitleRequest;

class OrganizationController extends Controller
{
    public function __construct(private readonly OrganizationService $service) {}

    public function byBuilding(ByBuildingRequest $request, string $buildingUuid): JsonResponse
    {
        $items = $this->service->getOrganisationsByBuilding(
            $buildingUuid,
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }

    public function byType(ByTypeRequest $request, string $typeUuid): JsonResponse
    {
        $items = $this->service->getOrganisationsByType(
            $typeUuid,
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }

    public function byTypeWithDescendants(ByTypeRequest $request, string $typeUuid): JsonResponse
    {
        $items = $this->service->getOrganisationsByTypeWithDescendants(
            $typeUuid,
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }

    public function byTypeTitleWithDescendants(ByTypeTitleRequest $request): JsonResponse
    {
        $items = $this->service->getOrganisationsByTypeTitleWithDescendants(
            $request->get('title'),
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }

    public function withinRadius(WithinRadiusRequest $request): JsonResponse
    {
        $items = $this->service->findWithinRadiusSimple(
            (float) $request->get('lat'),
            (float) $request->get('lng'),
            (float) $request->get('radius')
        );

        return response()->json(['data' => $items]);
    }

    public function searchByTitle(SearchByTitleRequest $request): JsonResponse
    {
        $items = $this->service->getOrganisationsByTitle(
            $request->get('q'),
            $request->integer('offset', 0),
            $request->integer('limit', 1000)
        );

        return response()->json(['data' => $items]);
    }

    public function show(string $uuid): JsonResponse
    {
        $org = $this->service->getOrganisationByUuid($uuid);

        if (!$org) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        return response()->json(['data' => $org]);
    }
}
