<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

/**
 * Class PublicUser
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $api_token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PublicUser extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasFactory;
    use MustVerifyEmail;
    use Notifiable {
        notify as protected defaultNotification;
    }

	const ID = 'id';
	const NAME = 'name';
	const EMAIL = 'email';
	const EMAIL_VERIFIED_AT = 'email_verified_at';
	const PASSWORD = 'password';
	const API_TOKEN = 'api_token';
	const REMEMBER_TOKEN = 'remember_token';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';



    protected $table = 'users';

	protected $casts = [
		self::EMAIL_VERIFIED_AT => 'datetime',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $hidden = [
		self::PASSWORD,
		self::API_TOKEN,
		self::REMEMBER_TOKEN
	];

	protected $fillable = [
		self::NAME,
		self::EMAIL,
		self::EMAIL_VERIFIED_AT,
		self::PASSWORD,
		self::API_TOKEN,
		self::REMEMBER_TOKEN
	];
}
