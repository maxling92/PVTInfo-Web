<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/upload', [ApiController::class, 'receiveData']);
