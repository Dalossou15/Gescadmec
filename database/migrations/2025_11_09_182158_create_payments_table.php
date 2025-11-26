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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Montant du paiement
            $table->date('payment_date');
            $table->string('payment_method'); // espèce, chèque, virement, etc.
            $table->string('reference')->nullable(); // Référence du paiement
            $table->enum('payment_type', ['partial', 'complete'])->default('partial');
            $table->text('notes')->nullable();
            $table->string('receipt_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
