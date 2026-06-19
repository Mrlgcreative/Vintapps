<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
            $table->string('methode', 50)->default('mobile_money');
            $table->string('operateur', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('reference', 100)->nullable()->unique();
            $table->string('reference_externe', 100)->nullable();
            $table->decimal('montant', 12, 2);
            $table->string('devise', 3);
            $table->string('statut', 30)->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
