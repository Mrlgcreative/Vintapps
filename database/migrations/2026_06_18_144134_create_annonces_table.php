<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categorie_id')->nullable()->constrained()->nullOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2)->default(0);
            $table->string('etat')->nullable();
            $table->string('statut')->default('publiee');
            $table->string('image_principale')->nullable();
            $table->integer('vues')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
