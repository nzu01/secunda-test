<?php

namespace App\Services;

use App\Models\Building;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BuildingService
{
    public const BUILDING_TAG = 'building';

    /**
     * Получить список всех зданий с пагинацией и кэшем.
     *
     * @param int $offset
     * @param int $limit
     * @return Collection
     */
    public function getAllBuildings(int $offset = 0, int $limit = 1000): Collection
    {
        $key = hash('sha3-256', 'building_list_all' . $limit . $offset);

        return Cache::tags([self::BUILDING_TAG])->remember($key, 600, function () use ($offset, $limit) {
            return Building::query()
                ->offset($offset)
                ->limit($limit)
                ->get();
        });
    }
}
