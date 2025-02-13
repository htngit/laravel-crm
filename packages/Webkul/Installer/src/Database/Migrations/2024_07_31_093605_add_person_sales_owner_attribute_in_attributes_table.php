<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = Carbon::now();

        // Wait for initial seeding to complete by checking for a known attribute
        $initialSeedingComplete = DB::table('attributes')
            ->where('code', 'name')
            ->where('entity_type', 'products')
            ->exists();

        if ($initialSeedingComplete) {
            // Check if our attribute already exists
            $exists = DB::table('attributes')
                ->where('code', 'user_id')
                ->where('entity_type', 'persons')
                ->exists();

            if (!$exists) {
                // Just use regular insert, let PostgreSQL handle the ID
                DB::table('attributes')->insert([
                    'code'            => 'user_id',
                    'name'            => trans('installer::app.seeders.attributes.persons.sales-owner'),
                    'type'            => 'lookup',
                    'entity_type'     => 'persons',
                    'lookup_type'     => 'users',
                    'validation'      => null,
                    'sort_order'      => '5',
                    'is_required'     => '0',
                    'is_unique'       => '0',
                    'quick_add'       => '1',
                    'is_user_defined' => '0',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: Implement if you want to be able to rollback this migration
        DB::table('attributes')
            ->where('code', 'user_id')
            ->where('entity_type', 'persons')
            ->delete();
    }
};
