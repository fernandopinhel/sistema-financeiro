<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('recurring_template_id')
                  ->nullable()
                  ->after('category_id')
                  ->constrained('recurring_templates')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\RecurringTemplate::class);
            $table->dropColumn('recurring_template_id');
        });
    }
};
