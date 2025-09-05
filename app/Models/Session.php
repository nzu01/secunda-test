<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;



/**
 * Class Session
 * 
 * @property string $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $payload
 * @property int $last_activity
 *
 * @package App\Models
 */
class Session extends Model
{
	const ID = 'id';
	const USER_ID = 'user_id';
	const IP_ADDRESS = 'ip_address';
	const USER_AGENT = 'user_agent';
	const PAYLOAD = 'payload';
	const LAST_ACTIVITY = 'last_activity';
	protected $table = 'sessions';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		self::USER_ID => 'int',
		self::LAST_ACTIVITY => 'int'
	];

	protected $fillable = [
		self::USER_ID,
		self::IP_ADDRESS,
		self::USER_AGENT,
		self::PAYLOAD,
		self::LAST_ACTIVITY
	];
}
