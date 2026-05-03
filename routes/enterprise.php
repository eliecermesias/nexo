<?php

use App\Http\Controllers\EnterpriseController;
use Illuminate\Support\Facades\Route;

Route::resource('/', EnterpriseController::class)->names('enterprises');
