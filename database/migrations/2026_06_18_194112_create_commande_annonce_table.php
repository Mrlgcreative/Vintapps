<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande_annonce', function (Blueprint $table) {
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
            $table->foreignId('annonce_id')->constrained()->cascadeOnDelete();
            $table->decimal('prix_unitaire', 12, 2);
            $table->string('devise', 3);
            $table->integer('quantite')->default(1);
            $table->primary(['commande_id', 'annonce_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_annonce');
    }
};
