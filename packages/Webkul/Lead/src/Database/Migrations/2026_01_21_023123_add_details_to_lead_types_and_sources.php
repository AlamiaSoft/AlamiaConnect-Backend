<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lead_types', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('priority')->nullable()->after('description');
            $table->string('crm_action')->nullable()->after('priority');
        });

        Schema::table('lead_sources', function (Blueprint $table) {
            $table->text('source_examples')->nullable()->after('name');
            $table->text('best_for')->nullable()->after('source_examples');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_types', function (Blueprint $table) {
            $table->dropColumn(['description', 'priority', 'crm_action']);
        });

        Schema::table('lead_sources', function (Blueprint $table) {
            $table->dropColumn(['source_examples', 'best_for']);
        });
    }
};
