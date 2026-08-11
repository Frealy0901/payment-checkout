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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();

        $table->string('transaction_id')->unique();
        $table->string('merchant_order_id')->unique();

        $table->string('customer_name');
        $table->string('customer_email');

        $table->decimal('amount', 15, 2);
        $table->string('currency', 3)->default('IDR');

        $table->text('callback_url');
        $table->text('return_url');

        $table->enum('status', ['PENDING', 'PAID', 'CANCELLED'])
              ->default('PENDING');

        $table->timestamp('paid_at')->nullable();

        $table->timestamps();
    });
}
};
