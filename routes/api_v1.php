<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    //Sessions
    // Route::apiResource('sessions', ThoughtSessionController::class);
    // Route::post('/sessions/{session}/process', ProcessThoughtWithAIController::class)
    //  ->name('sessions.process'); 
});

