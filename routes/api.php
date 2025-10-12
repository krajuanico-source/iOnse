<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Planning\SectionController;

Route::get('/sections/by-division/{divisionId}', [SectionController::class, 'getByDivision'])
  ->name('api.sections.byDivision');
