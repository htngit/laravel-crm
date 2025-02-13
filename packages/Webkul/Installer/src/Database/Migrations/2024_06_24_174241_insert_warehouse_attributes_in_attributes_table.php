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
            $attributes = [
                [
                    'code' => 'name',
                    'name' => trans('installer::app.seeders.attributes.warehouses.name'),
                    'type' => 'text',
                ],
                [
                    'code' => 'description',
                    'name' => trans('installer::app.seeders.attributes.warehouses.description'),
                    'type' => 'textarea',
                    'is_required' => '0',
                ],
                [
                    'code' => 'contact_name',
                    'name' => trans('installer::app.seeders.attributes.warehouses.contact-name'),
                    'type' => 'text',
                ],
                [
                    'code' => 'contact_emails',
                    'name' => trans('installer::app.seeders.attributes.warehouses.contact-emails'),
                    'type' => 'email',
                    'is_unique' => '1',
                ],
                [
                    'code' => 'contact_numbers',
                    'name' => trans('installer::app.seeders.attributes.warehouses.contact-numbers'),
                    'type' => 'phone',
                    'validation' => 'numeric',
                    'is_required' => '0',
                    'is_unique' => '1',
                ],
                [
                    'code' => 'contact_address',
                    'name' => trans('installer::app.seeders.attributes.warehouses.contact-address'),
                    'type' => 'address',
                ]
            ];

            // Check existence for each attribute and prepare final list
            $attributesToInsert = [];
            $sortOrder = 1;

            foreach ($attributes as $attribute) {
                $exists = DB::table('attributes')
                    ->where('code', $attribute['code'])
                    ->where('entity_type', 'warehouses')
                    ->exists();

                if (!$exists) {
                    $attributesToInsert[] = array_merge([
                        'entity_type'     => 'warehouses',
                        'lookup_type'     => null,
                        'validation'      => null,
                        'sort_order'      => $sortOrder,
                        'is_required'     => '1',
                        'is_unique'       => '0',
                        'quick_add'       => '1',
                        'is_user_defined' => '0',
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ], $attribute);
                    
                    $sortOrder++;
                }
            }

            if (!empty($attributesToInsert)) {
                DB::table('attributes')->insert($attributesToInsert);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('attributes')
            ->where('entity_type', 'warehouses')
            ->delete();
    }
};
