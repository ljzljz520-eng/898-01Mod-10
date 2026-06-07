<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth:sanctum');
});

// Topics routes
Route::apiResource('topics', App\Http\Controllers\Api\TopicController::class)->except(['store', 'update', 'destroy']);
Route::post('topics', [App\Http\Controllers\Api\TopicController::class, 'store'])->middleware('auth:sanctum');
Route::put('topics/{topic}', [App\Http\Controllers\Api\TopicController::class, 'update'])->middleware('auth:sanctum');
Route::patch('topics/{topic}', [App\Http\Controllers\Api\TopicController::class, 'update'])->middleware('auth:sanctum');
Route::delete('topics/{topic}', [App\Http\Controllers\Api\TopicController::class, 'destroy'])->middleware('auth:sanctum');

// Topic replies routes
Route::get('topics/{topic}/replies', [App\Http\Controllers\Api\ReplyController::class, 'index']);
Route::post('topics/{topic}/replies', [App\Http\Controllers\Api\ReplyController::class, 'store'])->middleware('auth:sanctum');

// Replies routes (for update/delete)
Route::apiResource('replies', App\Http\Controllers\Api\ReplyController::class)->except(['index', 'store']);

// Knowledge cards routes
Route::get('knowledge-cards/categories', [App\Http\Controllers\Api\KnowledgeCardController::class, 'categories']);
Route::get('knowledge-cards/active', [App\Http\Controllers\Api\KnowledgeCardController::class, 'active']);
Route::get('knowledge-cards/search', [App\Http\Controllers\Api\KnowledgeCardController::class, 'searchWithPriority']);
Route::apiResource('knowledge-cards', App\Http\Controllers\Api\KnowledgeCardController::class)->except(['store', 'update', 'destroy']);
Route::post('knowledge-cards', [App\Http\Controllers\Api\KnowledgeCardController::class, 'store'])->middleware('auth:sanctum');
Route::put('knowledge-cards/{knowledgeCard}', [App\Http\Controllers\Api\KnowledgeCardController::class, 'update'])->middleware('auth:sanctum');
Route::patch('knowledge-cards/{knowledgeCard}', [App\Http\Controllers\Api\KnowledgeCardController::class, 'update'])->middleware('auth:sanctum');
Route::delete('knowledge-cards/{knowledgeCard}', [App\Http\Controllers\Api\KnowledgeCardController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('knowledge-cards/{knowledgeCard}/review', [App\Http\Controllers\Api\KnowledgeCardController::class, 'markReviewed'])->middleware('auth:sanctum');
Route::get('knowledge-cards-review', [App\Http\Controllers\Api\KnowledgeCardController::class, 'needsReviewList'])->middleware('auth:sanctum');
