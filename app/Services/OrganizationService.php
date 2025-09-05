<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationOrganizationType;
use App\Models\OrganizationType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OrganizationService
{
    public const ORGANISATION_TAG = 'organisation';

    public function getOrganisationsByBuilding(string $buildingUuid, int $offset = 0, int $limit = 1000): Collection
    {
        $key = hash('sha3-256', 'organisation_list_by_building'.$buildingUuid.$limit.$offset);

        return Cache::tags([self::ORGANISATION_TAG])->remember($key, 600, function () use ($buildingUuid, $offset, $limit) {
            return Organization::query()
                ->where(Organization::BUILDING_UUID, '=', $buildingUuid)
                ->with([
                    'building',
                    'types' => fn($q) => $q->select('organization_types.*'),
                    'organization_contacts',
                ])
                ->offset($offset)
                ->limit($limit)
                ->get();
        });
    }

    public function getOrganisationsByTypeWithDescendants(
        string $typeUuid,
        int $offset = 0,
        int $limit = 1000
    ): Collection {
        $key = hash('sha3-256', 'org_list_by_type_with_descendants'.$typeUuid.$limit.$offset);

        return Cache::tags([self::ORGANISATION_TAG])->remember($key, 600, function () use ($typeUuid, $offset, $limit) {
            $orgUuidsSub = OrganizationOrganizationType::query()
                ->selectRaw('DISTINCT '.OrganizationOrganizationType::ORGANIZATION_UUID)
                ->whereTypeInTree($typeUuid)
                ->reorder()
                ->toRawSql();

            return Organization::query()
                ->whereRaw("uuid IN ($orgUuidsSub)")
                ->with([
                    'building',
                    'types' => fn($q) => $q->select('organization_types.*'),
                    'organization_contacts',
                ])
                ->offset($offset)
                ->limit($limit)
                ->get();
        });
    }

    public function getOrganisationsByTypeTitleWithDescendants(
        string $title,
        int $offset = 0,
        int $limit = 1000
    ): Collection {
        $key = hash('sha3-256', 'org_list_by_type_title_with_descendants'.$title.$limit.$offset);

        return Cache::tags([self::ORGANISATION_TAG])->remember($key, 600, function () use ($title, $offset, $limit) {
            $types = OrganizationType::query()
                ->where(fn($q) => $q->where(OrganizationType::TITLE, 'ILIKE', '%'.$title.'%')
                    ->orWhere(OrganizationType::NAME, 'ILIKE', '%'.$title.'%'))
                ->pluck(OrganizationType::UUID);

            $orgUuidsSub = OrganizationOrganizationType::query()
                ->selectRaw('DISTINCT '.OrganizationOrganizationType::ORGANIZATION_UUID)
                ->reorder()
                ->whereTypeInTrees($types->all())
                ->toRawSql();

            return Organization::query()
                ->whereRaw("uuid IN ($orgUuidsSub)")
                ->with([
                    'building',
                    'types' => fn($q) => $q->select('organization_types.*'),
                    'organization_contacts',
                ])
                ->offset($offset)
                ->limit($limit)
                ->get();
        });
    }

    public function getOrganisationsByType(string $typeUuid, int $offset = 0, int $limit = 1000): Collection
    {
        $key = hash('sha3-256', 'organisation_list_by_type'.$typeUuid.$limit.$offset);

        return Cache::tags([self::ORGANISATION_TAG])->remember($key, 600, function () use ($typeUuid, $offset, $limit) {
            $organisationTypes = OrganizationOrganizationType::query()
                ->select(OrganizationOrganizationType::ORGANIZATION_UUID)
                ->where(OrganizationOrganizationType::ORGANIZATION_TYPE_UUID, '=', $typeUuid)
                ->toRawSql();

            return Organization::query()
                ->whereRaw("uuid IN ($organisationTypes)")
                ->with([
                    'building',
                    'types' => fn($q) => $q->select('organization_types.*'),
                    'organization_contacts',
                ])
                ->offset($offset)
                ->limit($limit)
                ->get();
        });
    }

    public function findWithinRadiusSimple(float $lat, float $lng, float $radiusMeters): Collection
    {
        return Organization::query()
            ->whereHas('building', fn ($b) => $b->withinRadiusEarth($lat, $lng, $radiusMeters))
            ->with([
                'building',
                'types' => fn($q) => $q->select('organization_types.*'),
                'organization_contacts',
            ])
            ->get();
    }

    public function getOrganisationByUuid(string $uuid): ?Organization
    {
        return Cache::tags([self::ORGANISATION_TAG])->remember(self::ORGANISATION_TAG.$uuid, 60, function () use ($uuid) {
            return Organization::query()
                ->where(Organization::UUID, '=', $uuid)
                ->with([
                    'building',
                    'types' => fn($q) => $q->select('organization_types.*'),
                    'organization_contacts',
                ])
                ->first();
        });
    }

    public function getOrganisationsByTitle(string $text, int $offset = 0, int $limit = 1000): Collection
    {
        $like = '%'.$text.'%';

        return Organization::query()
            ->where(Organization::TITLE, 'ILIKE', $like)
            ->with([
                'building',
                'types' => fn($q) => $q->select('organization_types.*'),
                'organization_contacts',
            ])
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
