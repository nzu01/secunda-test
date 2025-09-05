<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE FUNCTION get_organization_type_tree(roots uuid[])
RETURNS TABLE (uuid uuid)
LANGUAGE sql
STABLE
AS $$
    WITH RECURSIVE type_tree AS (
        SELECT t.uuid
        FROM organization_types t
        WHERE t.uuid = ANY(roots)

        UNION ALL

        SELECT c.uuid
        FROM organization_types c
        JOIN type_tree p ON c.parent_uuid = p.uuid
    )
    SELECT DISTINCT uuid FROM type_tree;
$$;
SQL);

        DB::unprepared(<<<'SQL'
CREATE OR REPLACE FUNCTION get_organization_type_tree(root_uuid uuid)
RETURNS TABLE (uuid uuid)
LANGUAGE sql
STABLE
AS $$
    SELECT uuid FROM get_organization_type_tree(ARRAY[root_uuid]::uuid[]);
$$;
SQL);
    }

    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS get_organization_type_tree(uuid[]);");
        DB::unprepared("DROP FUNCTION IF EXISTS get_organization_type_tree(uuid);");
    }
};
