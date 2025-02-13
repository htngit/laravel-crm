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

        // Perbaikan query update untuk PostgreSQL
        DB::statement('
            UPDATE lead_pipeline_stages lps
            SET code = ls.code,
                name = ls.name
            FROM lead_stages ls
            WHERE lps.lead_stage_id = ls.id
        ');

        Schema::table('lead_pipeline_stages', function (Blueprint $table) {
            $table->dropForeign('lead_pipeline_stages_lead_stage_id_foreign');
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
            $table->dropUnique(['lead_pipeline_stages_code_lead_pipeline_id_unique']);
            $table->dropUnique(['lead_pipeline_stages_name_lead_pipeline_id_unique']);

            $table->integer('lead_stage_id')->unsigned();
            $table->foreign('lead_stage_id')->references('id')->on('lead_stages')->onDelete('cascade');

            $table->dropColumn('code');
            $table->dropColumn('name');
        });
    }
};