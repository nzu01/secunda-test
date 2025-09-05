<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OrganizationContact
 * 
 * @property int $id
 * @property string $uuid
 * @property string $organization_uuid
 * @property string $contact_type
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Organization $organization
 *
 * @package App\Models
 */
class OrganizationContact extends Model
{
	const ID = 'id';
	const UUID = 'uuid';
	const ORGANIZATION_UUID = 'organization_uuid';
	const CONTACT_TYPE = 'contact_type';
	const VALUE = 'value';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	protected $table = 'organization_contacts';

	protected $casts = [
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::UUID,
		self::ORGANIZATION_UUID,
		self::CONTACT_TYPE,
		self::VALUE
	];

	public function organization(): BelongsTo
	{
		return $this->belongsTo(Organization::class, OrganizationContact::ORGANIZATION_UUID, Organization::UUID);
	}
}
