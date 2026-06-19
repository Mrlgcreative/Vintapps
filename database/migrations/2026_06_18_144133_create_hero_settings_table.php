<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->default('Bienvenue sur Vintapp');
            $table->text('sous_titre')->nullable()->default('Achetez et vendez au marché Manika de Kolwezi');
            $table->string('bouton_texte')->default('Explorer les annonces');
            $table->string('bouton_lien')->default('/annonces');
            $table->string('image_fond')->nullable();
            $table->string('couleur_fond')->default('#333D6D');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
