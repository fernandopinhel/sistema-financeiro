<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('color', 7)->default('#6366f1');
            $table->boolean('show_on_dashboard')->default(false);
            $table->timestamps();
        });

        // Adiciona category_id às transações existentes
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete()
                  ->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
