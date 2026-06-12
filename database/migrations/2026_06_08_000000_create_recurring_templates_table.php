<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('description', 255);
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['income', 'expense']);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->tinyInteger('day_of_month'); // dia esperado de vencimento (1-31)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_templates');
    }
};
