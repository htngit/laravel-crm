<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSalesOwnerSeeder extends Seeder
{
    public function run($parameters = [])
    {
        // Check if our attribute already exists
        $exists = DB::table('attributes')
            ->where('code', 'user_id')
            ->where('entity_type', 'organizations')
            ->exists();

        if (!$exists) {
            DB::table('attributes')->insert([
                'code'            => 'user_id',
                'name'            => trans('installer::app.seeders.attributes.organizations.sales-owner'),
                'type'            => 'lookup',
                'entity_type'     => 'organizations',
                'lookup_type'     => 'users',
                'validation'      => null,
                'sort_order'      => '5',
                'is_required'     => '0',
                'is_unique'       => '0',
                'quick_add'       => '1',
                'is_user_defined' => '0',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }
    }
}