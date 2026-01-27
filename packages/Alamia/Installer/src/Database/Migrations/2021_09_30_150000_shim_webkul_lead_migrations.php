<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tablePrefix = DB::getTablePrefix();

        // 1. Shim: 2021_09_30_154222_alter_lead_pipeline_stages_table
        if (Schema::hasTable('lead_pipeline_stages')) {
            if (! Schema::hasColumn('lead_pipeline_stages', 'code')) {
                Schema::table('lead_pipeline_stages', function (Blueprint $table) {
                    $table->string('code')->after('id')->nullable();
                    $table->string('name')->after('code')->nullable();
                });
            }

            // SQLite compatible update
            if (Schema::hasTable('lead_stages')) {
                $stages = DB::table('lead_stages')->get();
                foreach ($stages as $stage) {
                    DB::table('lead_pipeline_stages')
                        ->where('lead_stage_id', $stage->id)
                        ->update([
                            'code' => $stage->code,
                            'name' => $stage->name,
                        ]);
                }
            }

            try {
                Schema::table('lead_pipeline_stages', function (Blueprint $table) use ($tablePrefix) {
                    if (DB::getDriverName() !== 'sqlite') {
                        // Attempt determining if FK exists or just ignore failure
                        $table->dropForeign($tablePrefix . 'lead_pipeline_stages_lead_stage_id_foreign');
                    }
                    
                    if (Schema::hasColumn('lead_pipeline_stages', 'lead_stage_id')) {
                        $table->dropColumn('lead_stage_id');
                    }

                    try {
                        $table->unique(['code', 'lead_pipeline_id']);
                        $table->unique(['name', 'lead_pipeline_id']);
                    } catch (\Exception $e) {}
                });
            } catch (\Exception $e) {
                // Ignore foreign key drop errors if it doesn't exist
            }
            
            DB::table('migrations')->updateOrInsert(['migration' => '2021_09_30_154222_alter_lead_pipeline_stages_table'], ['batch' => 1]);
        }

        // 2. Shim: 2021_09_30_161722_alter_leads_table
        if (Schema::hasTable('leads')) {
            if (! Schema::hasColumn('leads', 'lead_pipeline_stage_id')) {
                Schema::table('leads', function (Blueprint $table) {
                    $table->integer('lead_pipeline_stage_id')->after('lead_pipeline_id')->unsigned()->nullable();
                    $table->foreign('lead_pipeline_stage_id')->references('id')->on('lead_pipeline_stages')->onDelete('cascade');
                });
            }

            if (Schema::hasColumn('leads', 'lead_stage_id')) {
                // SQLite compatible update
                DB::table('leads')->update([
                    'lead_pipeline_stage_id' => DB::raw($tablePrefix . 'leads.lead_stage_id'),
                ]);

                try {
                    Schema::table('leads', function (Blueprint $table) use ($tablePrefix) {
                        if (DB::getDriverName() !== 'sqlite') {
                            $table->dropForeign($tablePrefix . 'leads_lead_stage_id_foreign');
                        }
                        
                        $table->dropColumn('lead_stage_id');
                    });
                } catch (\Exception $e) {}
            }

            DB::table('migrations')->updateOrInsert(['migration' => '2021_09_30_161722_alter_leads_table'], ['batch' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
