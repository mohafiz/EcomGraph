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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_es_text')->nullable()->fulltext();
        });

        DB::statement('UPDATE products SET name_es_text = `name_es`');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name_es');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_es_text', 'name_es');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('name_es_fulltext_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_backup')->after('name_es');
        });

        DB::statement('UPDATE products SET name_backup = `name_es`');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name_es');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_backup', 'name_es');
        });
    }
};
