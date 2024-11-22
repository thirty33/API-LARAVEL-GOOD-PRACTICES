<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->foreignIdFor(\App\Models\Book::class)->constrained();
            $table->date('loaned_at')->comment('La fecha de préstamo');
            $table->date('returned_at')->nullable()->comment('La fecha de devolución');
            $table->date('due_date')->comment('La fecha máxima de devolución');
            $table->boolean('returned')->default(false)->comment('Indica si el libro ha sido devuelto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
