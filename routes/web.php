<?php

use App\Http\Controllers\ProfileController;
use App\Models\Annonce;
use App\Models\Categorie;
use App\Models\HeroSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $hero = HeroSetting::first();
    $categories = Categorie::where('actif', true)->orderBy('ordre')->get();
    $annonces = Annonce::publiee()->recent()->take(8)->get();

    return view('welcome', compact('hero', 'categories', 'annonces'));
});

Route::get('/dashboard', function () {
    return auth()->user()->estAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('profile.edit');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/devenir-vendeur', [ProfileController::class, 'devenirVendeur'])->name('profile.devenir-vendeur');

    // Messaging
    Route::get('/messages', [App\Http\Controllers\MessagingController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [App\Http\Controllers\MessagingController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [App\Http\Controllers\MessagingController::class, 'envoyer'])->name('messages.envoyer');
    Route::post('/messages/contacter/{annonce}', [App\Http\Controllers\MessagingController::class, 'contacter'])->name('messages.contacter');

    // Favorites
    Route::get('/favoris', [App\Http\Controllers\FavoriController::class, 'index'])->name('favoris.index');
    Route::post('/favoris/toggle/{annonce}', [App\Http\Controllers\FavoriController::class, 'toggle'])->name('favoris.toggle');

    // Checkout
    Route::get('/checkout/recap', [App\Http\Controllers\CheckoutController::class, 'recap'])->name('checkout.recap');
    // Paiement Mobile Money
    Route::post('/checkout/payer', [App\Http\Controllers\PaiementController::class, 'payer'])->name('checkout.payer');
    Route::get('/checkout/paiement/{commande}/{paiement}', [App\Http\Controllers\PaiementController::class, 'paiement'])->name('checkout.paiement');
    Route::post('/checkout/paiement/{commande}/{paiement}/confirmer', [App\Http\Controllers\PaiementController::class, 'confirmerPaiement'])->name('checkout.confirmer-paiement');
    Route::any('/paiements/maishapay/callback/{reference}', [App\Http\Controllers\PaiementController::class, 'callback'])->name('payments.maishapay.callback');
    Route::get('/paiements/maishapay/status/{reference}', [App\Http\Controllers\PaiementController::class, 'status'])->name('payments.maishapay.status');
    Route::get('/checkout/confirmation/{commande}', [App\Http\Controllers\CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    Route::post('/checkout/recevoir/{commande}', [App\Http\Controllers\CheckoutController::class, 'recevoir'])->name('checkout.recevoir');
    Route::get('/commandes', [App\Http\Controllers\CheckoutController::class, 'historique'])->name('checkout.historique');

    // Wallet
    Route::get('/portefeuille', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');

    // Evaluations
    Route::post('/evaluations/{annonce}', [App\Http\Controllers\EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/evaluations/vendeur/{user}', [App\Http\Controllers\EvaluationController::class, 'vendeur'])->name('evaluations.vendeur');

    // Signalement public
    Route::post('/signalements/annonce/{annonce}', [App\Http\Controllers\Public\SignalementController::class, 'store'])->name('signalements.store');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Hero
        Route::get('/hero', [App\Http\Controllers\Admin\HeroController::class, 'edit'])->name('hero.edit');
        Route::patch('/hero', [App\Http\Controllers\Admin\HeroController::class, 'update'])->name('hero.update');

        // Categories
        Route::get('/categories', [App\Http\Controllers\Admin\CategorieController::class, 'index'])->name('categories');
        Route::get('/categories/create', [App\Http\Controllers\Admin\CategorieController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\Admin\CategorieController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categorie}/edit', [App\Http\Controllers\Admin\CategorieController::class, 'edit'])->name('categories.edit');
        Route::patch('/categories/{categorie}', [App\Http\Controllers\Admin\CategorieController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categorie}', [App\Http\Controllers\Admin\CategorieController::class, 'destroy'])->name('categories.destroy');

        // Annonces
        Route::get('/annonces', [App\Http\Controllers\Admin\AnnonceController::class, 'index'])->name('annonces');
        Route::get('/annonces/{annonce}/edit', [App\Http\Controllers\Admin\AnnonceController::class, 'edit'])->name('annonces.edit');
        Route::patch('/annonces/{annonce}', [App\Http\Controllers\Admin\AnnonceController::class, 'update'])->name('annonces.update');
        Route::post('/annonces/{annonce}/statut', [App\Http\Controllers\Admin\AnnonceController::class, 'statut'])->name('annonces.statut');
        Route::delete('/annonces/{annonce}', [App\Http\Controllers\Admin\AnnonceController::class, 'destroy'])->name('annonces.destroy');

        // Signalements
        Route::get('/signalements', [App\Http\Controllers\Admin\SignalementController::class, 'index'])->name('signalements');
        Route::post('/signalements/{signalement}/resoudre', [App\Http\Controllers\Admin\SignalementController::class, 'resoudre'])->name('signalements.resoudre');
        Route::post('/signalements/{signalement}/rejeter', [App\Http\Controllers\Admin\SignalementController::class, 'rejeter'])->name('signalements.rejeter');

        // Users
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Admin profile
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    });
});

require __DIR__.'/auth.php';

// Public listing pages
Route::get('/annonces', [App\Http\Controllers\Public\AnnonceController::class, 'index'])->name('annonces.index');
Route::get('/annonces/{annonce}', [App\Http\Controllers\Public\AnnonceController::class, 'show'])->name('annonces.show');

// Cart
Route::get('/panier', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{annonce}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/panier/modifier/{annonce}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/panier/retirer/{annonce}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/panier/vider', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Vendeur routes
Route::middleware('auth')->prefix('vendeur')->name('vendeur.')->group(function () {
    Route::get('/', [App\Http\Controllers\Vendeur\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('annonces', App\Http\Controllers\Vendeur\AnnonceController::class);
    Route::delete('/photos/{photo}', [App\Http\Controllers\Vendeur\AnnonceController::class, 'supprimerPhoto'])->name('annonces.photo.destroy');
    Route::get('/ventes', [App\Http\Controllers\Vendeur\VenteController::class, 'index'])->name('ventes');
    Route::post('/ventes/{commande}/expedier', [App\Http\Controllers\Vendeur\VenteController::class, 'expedier'])->name('ventes.expedier');
});
