<?php

namespace App\Models;

use App\Enums\Database;
use App\Enums\Defaults;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use NzuIndustries\JsonApi\Interfaces\JsonApiSerializable;

/**
 * Class Model
 *
 * @property int $id
 * @property string $uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Model extends Eloquent\Model
{
    use HasFactory;

    public const FIELD_ID = 'id';
    public const ID = 'id';

    public const FIELD_UUID = 'uuid';
    public const UUID = 'uuid';

    public const FIELD_CREATED_AT = 'created_at';
    public const CREATED_AT = 'created_at';

    public const FIELD_UPDATED_AT = 'updated_at';
    public const UPDATED_AT = 'updated_at';

    public const RELATIONS = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(static function (Model $model) {
            $model::updatedHandler($model);
        });

        static::saved(static function (Model $model) {
            $model::savedHandler($model);
        });

        static::deleted(static function (Model $model) {
            $model::deletedHandler($model);
        });

//        static::addGlobalScope('order', function (Builder $builder) {
//            $builder->orderBy(static::FIELD_ID, 'desc'); //created_at desc
//        });
    }

    public static function updatedHandler(Model $model): void
    {
        //for events
    }

    public static function deletedHandler(Model $model): void
    {
        //for events
    }

    public static function savedHandler(Model $model): void
    {
        $model->refresh();
    }

    public static function getDatabaseConnection(): ?string
    {
        return (new static)->getConnectionName();
    }

    public static function getTableName(): ?string
    {
        return (new static)->getTable();
    }

}
