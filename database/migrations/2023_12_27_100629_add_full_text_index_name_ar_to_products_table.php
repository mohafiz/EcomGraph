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
            $table->string('name_ar_text')->nullable()->fulltext();
        });

        DB::statement('UPDATE products SET name_ar_text = `name_ar`');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_ar_text', 'name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('name_ar_fulltext_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_backup')->after('name_ar');
        });

        DB::statement('UPDATE products SET name_backup = `name_ar`');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_backup', 'name_ar');
        });
    }
};
