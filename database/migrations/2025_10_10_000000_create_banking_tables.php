<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

            Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable()->after('email');
    $table->string('account_number')->unique()->after('phone');
    $table->decimal('balance', 15, 2)->default(0)->after('account_number');
    $table->string('pin_code')->nullable()->after('balance');
    $table->decimal('daily_limit', 15, 2)->default(5000)->after('pin_code');
    $table->decimal('daily_spent', 15, 2)->default(0)->after('daily_limit');
    $table->date('daily_reset_date')->nullable()->after('daily_spent');
    $table->string('qr_code')->unique()->nullable()->after('daily_reset_date');
    $table->boolean('is_active')->default(true)->after('qr_code');
    $table->boolean('notifications_enabled')->default(true)->after('is_active');
    $table->string('preferred_language')->default('ar')->after('notifications_enabled');
});
        

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_number')->unique();
            $table->string('card_holder_name');
            $table->enum('card_type', ['debit', 'credit'])->default('debit');
            $table->string('cvv');
            $table->date('expiry_date');
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_blocked')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('transaction_type', ['transfer', 'payment', 'qr_payment']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->string('merchant_name')->nullable();
            $table->string('reference_number')->unique();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });

        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_type');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('merchant_id')->unique();
            $table->string('qr_code')->unique()->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('card_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('card_type', ['debit', 'credit']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('qr_payments', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->unique();
            $table->foreignId('merchant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_payments');
        Schema::dropIfExists('card_requests');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('merchants');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('cards');
        Schema::dropIfExists('users');
    }
};
