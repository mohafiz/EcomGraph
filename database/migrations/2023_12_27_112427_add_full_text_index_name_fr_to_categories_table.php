<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_fr_text')->nullable()->fulltext();
        });

        DB::statement('UPDATE categories SET name_fr_text = `name_fr`');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_fr');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name_fr_text', 'name_fr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('name_fr_fulltext_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_backup')->after('name_fr');
        });

        DB::statement('UPDATE categories SET name_backup = `name_fr`');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_fr');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name_backup', 'name_fr');
        });
    }
};
