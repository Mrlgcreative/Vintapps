<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acheteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendeur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('annonce_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('note')->unsigned();
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->unique(['acheteur_id', 'annonce_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
