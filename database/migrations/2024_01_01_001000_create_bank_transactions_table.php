<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // deposit, withdraw, client_payment, firm_payment
            $table->string('source')->nullable(); // e.g. Салмон, Джамшед, client name, firm name
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('firm_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable(); // cash, card_milli, dushanbe_city, alif, other
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
