<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['libelle' => 'Téléphones & Tablettes', 'icone' => 'fas fa-mobile-alt', 'couleur' => '#723EC3', 'ordre' => 1],
            ['libelle' => 'Vêtements & Accessoires', 'icone' => 'fas fa-tshirt', 'couleur' => '#FF6B6B', 'ordre' => 2],
            ['libelle' => 'Électronique', 'icone' => 'fas fa-tv', 'couleur' => '#4ECDC4', 'ordre' => 3],
            ['libelle' => 'Maison & Déco', 'icone' => 'fas fa-home', 'couleur' => '#FFCF95', 'ordre' => 4],
            ['libelle' => 'Sports & Loisirs', 'icone' => 'fas fa-futbol', 'couleur' => '#45B7D1', 'ordre' => 5],
            ['libelle' => 'Véhicules', 'icone' => 'fas fa-car', 'couleur' => '#96CEB4', 'ordre' => 6],
            ['libelle' => 'Bijoux & Montres', 'icone' => 'fas fa-gem', 'couleur' => '#FFEAA7', 'ordre' => 7],
            ['libelle' => 'Immobilier', 'icone' => 'fas fa-building', 'couleur' => '#DDA0DD', 'ordre' => 8],
        ];

        foreach ($categories as $cat) {
            Categorie::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['libelle'])],
                $cat
            );
        }
    }
}
