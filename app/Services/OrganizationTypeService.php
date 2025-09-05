<?php

namespace App\Services;


use App\Models\OrganizationType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OrganizationTypeService
{
    public const ORGANIZATION_TYPE_TAG = 'organization_type';
    /**
     * Создать тип организации (макс. вложенность = 3).
     */
    public function createType(string $title, string $name, ?string $parentUuid = null): OrganizationType
    {
        if ($parentUuid !== null) {
            $parent = OrganizationType::query()
                ->where(OrganizationType::UUID, $parentUuid)
                ->first();

            if (!$parent) {
                throw new \InvalidArgumentException('Parent not found');
            }

            if ($parent->parent_uuid) {
                $grand = OrganizationType::query()
                    ->where(OrganizationType::UUID, $parent->parent_uuid)
                    ->first();

                if ($grand) {
                    if ($grand->parent_uuid) {
                        // есть ещё выше → это уже 4 уровень
                        throw new \InvalidArgumentException('Max nesting level is 3.');
                    }
                }
            }
        }

        return OrganizationType::create([
            OrganizationType::TITLE => $title,
            OrganizationType::NAME => $name,
            OrganizationType::PARENT_UUID => $parentUuid,
        ]);
    }


    public function getAllTypes(int $offset = 0, int $limit = 1000): Collection
    {
        $key = hash('sha3-256', "organization_type_tree___{$limit}{$offset}");

        return Cache::tags([self::ORGANIZATION_TYPE_TAG])->remember($key, 600, function () use ($offset, $limit) {
            $all = OrganizationType::query()
                ->select([OrganizationType::UUID, OrganizationType::TITLE, OrganizationType::NAME, OrganizationType::PARENT_UUID])
                ->orderBy(OrganizationType::TITLE)
                ->get();

            $byParent = $all->groupBy(fn($t) => $t->parent_uuid); // null для корней

            $build = function (?string $parent) use (&$build, $byParent) {
                return ($byParent->get($parent, new Collection()))->map(fn($t) => [
                    'uuid'     => $t->uuid,
                    'title'    => $t->title,
                    'name'     => $t->name,
                    'children' => $build($t->uuid),
                ])->values();
            };

            $roots = $build(null);

            return $roots->slice($offset, $limit)->values();
        });
    }
}