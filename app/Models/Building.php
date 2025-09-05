<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Building
 * 
 * @property int $id
 * @property string $uuid
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Organization[] $organizations
 * @method withinRadiusEarth(float $lat, float $lng, float $radiusMeters) see scope scopeWithinRadiusEarth
 * @package App\Models
 */
class Building extends Model
{
	const ID = 'id';
	const UUID = 'uuid';
	const ADDRESS = 'address';
	const LATITUDE = 'latitude';
	const LONGITUDE = 'longitude';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	protected $table = 'buildings';

	protected $casts = [
		self::ID => 'int',
		self::UUID => 'string',
		self::LATITUDE => 'float',
		self::LONGITUDE => 'float',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::UUID,
		self::ADDRESS,
		self::LATITUDE,
		self::LONGITUDE
	];

	public function organizations(): HasMany
	{
		return $this->hasMany(Organization::class, Organization::BUILDING_UUID, Organization::UUID);
	}

    public function scopeWithinRadiusEarth(Builder $q, float $lat, float $lng, float $radiusMeters): Builder
    {
        return $q
            ->select([
                "{$this->getTable()}.*",
            ])
            ->selectRaw(
                "earth_distance(ll_to_earth(?, ?), ll_to_earth(latitude, longitude)) as distance",
                [$lat, $lng]
            )
            ->whereRaw(
                "earth_box(ll_to_earth(?, ?), ?) @> ll_to_earth(latitude, longitude)",
                [$lat, $lng, $radiusMeters]
            )
            ->orderBy('distance');
    }
}
