<?php

use App\Http\Controllers\EnterpriseController;
use Illuminate\Support\Facades\Route;

Route::resource('enterprises', EnterpriseController::class)
    ->parameters(['enterprises' => 'enterprise']);
