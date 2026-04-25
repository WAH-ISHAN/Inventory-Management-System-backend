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
        Schema::create('borrowings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained()->onDelete('cascade');
        $table->string('borrower_name');
        $table->string('contact_details');
        $table->date('borrow_date');
        $table->date('expected_return_date');
        $table->integer('quantity_borrowed');
        $table->date('returned_date')->nullable();
        $table->enum('status', ['Borrowed', 'Returned'])->default('Borrowed');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
