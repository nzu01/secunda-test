<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrganizationOrganizationType
 * 
 * @property int $id
 * @property string $uuid
 * @property string $organization_uuid
 * @property string $organization_type_uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Organization $organization
 * @property OrganizationType $organization_type

 * @package App\Models
 */
class OrganizationOrganizationType extends Model
{
	const ID = 'id';
	const UUID = 'uuid';
	const ORGANIZATION_UUID = 'organization_uuid';
	const ORGANIZATION_TYPE_UUID = 'organization_type_uuid';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	protected $table = 'organization_organization_types';

	protected $casts = [
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::UUID,
		self::ORGANIZATION_UUID,
		self::ORGANIZATION_TYPE_UUID
	];

    public function scopeWhereTypeInTree(Builder $q, string $rootTypeUuid): Builder
    {
        return $q->whereTypeInTrees([$rootTypeUuid]);
    }

    public function scopeWhereTypeInTrees(Builder $q, array $rootTypeUuids): Builder
    {
        if (empty($rootTypeUuids)) {
            return $q;
        }

        $roots = array_values(array_unique(array_map('strtolower', $rootTypeUuids)));

        $pgArray = '{' . implode(',', $roots) . '}';

        return $q->whereRaw(
            self::ORGANIZATION_TYPE_UUID . ' IN (SELECT uuid FROM get_organization_type_tree(?::uuid[]))',
            [$pgArray]
        );
    }

	public function organization(): BelongsTo
	{
		return $this->belongsTo(Organization::class, OrganizationOrganizationType::ORGANIZATION_UUID, Organization::UUID);
	}

	public function organization_type(): BelongsTo
	{
		return $this->belongsTo(OrganizationType::class, OrganizationOrganizationType::ORGANIZATION_TYPE_UUID, OrganizationType::UUID);
	}
}
