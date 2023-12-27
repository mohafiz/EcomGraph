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
            $table->string('name_text')->fulltext();
        });

        DB::statement('UPDATE products SET name_text = `name`');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_text', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('name_fulltext_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_backup')->after('name');
        });

        DB::statement('UPDATE products SET name_backup = `name`');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name_backup', 'name');
        });
    }
};
