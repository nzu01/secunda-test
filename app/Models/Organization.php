<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Organization
 * 
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $building_uuid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Building $building
 * @property Collection|OrganizationContact[] $organization_contacts
 *
 * @package App\Models
 */
class Organization extends Model
{
	const ID = 'id';
	const UUID = 'uuid';
	const TITLE = 'title';
	const BUILDING_UUID = 'building_uuid';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	protected $table = 'organizations';

	protected $casts = [
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::UUID,
		self::TITLE,
		self::BUILDING_UUID
	];

	public function building(): BelongsTo
	{
		return $this->belongsTo(Building::class, Organization::BUILDING_UUID, Building::UUID);
	}

	public function organization_contacts(): HasMany
	{
		return $this->hasMany(OrganizationContact::class, OrganizationContact::ORGANIZATION_UUID, OrganizationContact::UUID);
	}

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(
            OrganizationType::class,
            'organization_organization_types',
            OrganizationOrganizationType::ORGANIZATION_UUID,
            OrganizationOrganizationType::ORGANIZATION_TYPE_UUID,
            self::UUID,
            OrganizationType::UUID
        );
    }
}
