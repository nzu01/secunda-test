<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;

class MigrationHelper
{
    public static function autoUuidUpdate(string $tableName): void
    {
        DB::statement("ALTER TABLE {$tableName} ALTER COLUMN uuid SET DEFAULT gen_random_uuid();");
    }

    public static function createEnum($enumValues, $enumTypeName, $tableName, $column, ?string $default = null)
    {
        DB::statement(sprintf('DROP TYPE IF EXISTS "%s"', $enumTypeName));

        $enumValuesPrepared = array_map(static function ($item) {
            return "'{$item}'";
        }, $enumValues);

        $enumValuesString = implode(', ', $enumValuesPrepared);

        DB::statement(sprintf('CREATE TYPE "%s" AS ENUM (%s)', $enumTypeName, $enumValuesString));

        if ($default !== null) {
            DB::statement(sprintf(
                'alter table %s add column %s %s not null default \'%s\'', $tableName, $column, $enumTypeName, $default
            ));
        } else {
            DB::statement(sprintf('alter table %s add column %s %s not null', $tableName, $column, $enumTypeName));
        }
    }

    public static function addValueToEnum(string $enumTypeName, string $value)
    {
        DB::statement(sprintf('alter type %s ADD VALUE IF NOT EXISTS \'%s\'', $enumTypeName, $value));
    }

    public static function columnWithExistingEnum(
        $enumTypeName, $tableName, $column, ?string $default = null, bool $nullable = false
    ) {
        if ($nullable === false) {
            $nullableString = 'not null';
        } else {
            $nullableString = '';
        }
        if ($default !== null) {
            DB::statement(sprintf(
                'alter table %s add column %s %s %s default \'%s\'', $tableName,
                $column, $enumTypeName, $nullableString, $default
            ));
        } else {
            DB::statement(sprintf('alter table %s add column %s %s %s', $tableName, $column,
                $enumTypeName, $nullableString));
        }
    }

    public static function dropEnumType(string $enumTypeName)
    {
        DB::statement(sprintf('DROP TYPE IF EXISTS "%s"', $enumTypeName));
    }
}
