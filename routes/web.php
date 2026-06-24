<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PaymentDestinationController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RetentionRateController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRateController;
use App\Http\Controllers\TaxController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::view('/', 'pages::front', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::post('locale', function () {
    $validated = request()->validate([
        'locale' => ['required', 'in:en,es'],
    ]);

    session(['locale' => $validated['locale']]);

    return back();
})->middleware('auth')->name('locale.update');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::livewire('quotations', 'pages::quotations.index')->name('quotations.index');
    });

Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')->name('invitations.accept');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('banks', BankController::class);
    Route::resource('bank-accounts', BankAccountController::class);
    Route::resource('payment-destinations', PaymentDestinationController::class);
    Route::resource('parties', PartyController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('people', PersonController::class)
        ->parameters(['people' => 'person']);
    Route::resource('services', ServiceController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('service-rates', ServiceRateController::class);
    Route::resource('taxes', TaxController::class);
    Route::resource('retention-rates', RetentionRateController::class);
});

Route::middleware(['auth'])->group(base_path('routes/enterprise.php'));

require __DIR__.'/settings.php';
