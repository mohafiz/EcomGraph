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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_ar')->nullable();
            $table->string('name_es')->nullable();
            $table->string('name_fr')->nullable();

            $table->string('description_ar')->nullable();
            $table->string('description_es')->nullable();
            $table->string('description_fr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name_ar');
            $table->dropColumn('name_es');
            $table->dropColumn('name_fr');

            $table->dropColumn('description_ar');
            $table->dropColumn('description_es');
            $table->dropColumn('description_fr');
        });
    }
};
