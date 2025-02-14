<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_pipeline_stages', function (Blueprint $table) {
            $table->string('code')->after('id')->nullable();
            $table->string('name')->after('code')->nullable();
        });

        // Update data from lead_stages
        DB::statement('
            UPDATE lead_pipeline_stages lps
            SET code = ls.code,
                name = ls.name
            FROM lead_stages ls
            WHERE lps.lead_stage_id = ls.id
        ');

        Schema::table('lead_pipeline_stages', function (Blueprint $table) {
            // Drop the foreign key constraint using Laravel's naming convention
            $table->dropForeign(['lead_stage_id']);
            $table->dropColumn('lead_stage_id');

            $table->unique(['code', 'lead_pipeline_id']);
            $table->unique(['name', 'lead_pipeline_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_pipeline_stages', function (Blueprint $table) {
            $table->dropUnique(['code', 'lead_pipeline_id']);
            $table->dropUnique(['name', 'lead_pipeline_id']);

            $table->integer('lead_stage_id')->unsigned();
            $table->foreign('lead_stage_id')
                ->references('id')
                ->on('lead_stages')
                ->onDelete('cascade');

            $table->dropColumn('code');
            $table->dropColumn('name');
        });
    }
};