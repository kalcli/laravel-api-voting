<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CsrfController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\VotingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Election's CRUD routes

Route::get('/elections',[ElectionController::class,'index'])
        ->middleware('guest');

Route::post('/elections',[ElectionController::class,'store']);

Route::get('/elections/{id}', [ElectionController::class,'show']);

Route::get('/elections/{id}/edit', [ElectionController::class,'edit']);

Route::put('/elections/{id}/edit', [ElectionController::class,'update'])
        ->middleware('guest');

Route::delete('/elections/{id}/delete',[ElectionController::class,'destroy']);

// candidate's CRUD routes

Route::get('/candidates',[CandidateController::class,'index']);

Route::post('/candidates',[CandidateController::class,'store']);

Route::get('/candidates/{id}',[CandidateController::class,'show']);

Route::get('/candidates/{id}/edit',[CandidateController::class,'edit']);

Route::put('/candidates/{id}/edit',[CandidateController::class,'update']);

Route::delete('/candidates/{id}/delete',[CandidateController::class,'destroy']);

// Voting routes

Route::post('/voting',[VotingController::class,'voting']);
Route::post('/voting/results',[VotingController::class,'Results']);
