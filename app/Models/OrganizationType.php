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
 * Class OrganizationType
 * 
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $parent_uuid
 * 
 * @property OrganizationType $organization_type
 * @property Collection|Organization[] $organizations
 *
 * @package App\Models
 */
class OrganizationType extends Model
{
	const ID = 'id';
	const UUID = 'uuid';
	const TITLE = 'title';
	const NAME = 'name';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const PARENT_UUID = 'parent_uuid';
	protected $table = 'organization_types';

	protected $casts = [
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime',
		self::PARENT_UUID => 'string'
	];

	protected $fillable = [
		self::UUID,
		self::TITLE,
		self::NAME,
		self::PARENT_UUID
	];

	public function organization_type(): BelongsTo
	{
		return $this->belongsTo(OrganizationType::class, OrganizationType::PARENT_UUID, OrganizationType::UUID);
	}

	public function organization_types(): HasMany
	{
		return $this->hasMany(OrganizationType::class, OrganizationType::PARENT_UUID, OrganizationType::UUID);
	}
}
