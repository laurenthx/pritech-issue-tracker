<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\IssueController as ApiIssueController; // Use alias to avoid conflict with web IssueController
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- AJAX Routes for managing Issue Tags ---
Route::post('issues/{issue}/tags/{tag}/toggle', [TagController::class, 'toggleIssueTag'])->name('api.issues.tags.toggle');
Route::get('issues/{issue}/tags', [TagController::class, 'getIssueTags'])->name('api.issues.tags.get');

// --- AJAX Routes for managing Comments and Members ---
Route::prefix('issues/{issue}')->group(function () {
    // Comment Routes
    Route::get('/comments', [CommentController::class, 'index'])->name('api.issues.comments.index');
    Route::post('/comments', [CommentController::class, 'store'])->name('api.issues.comments.store');

    // Member Routes
    Route::post('/members/{user}/toggle', [ApiIssueController::class, 'toggleIssueMember'])->name('api.issues.members.toggle');
    Route::get('/members', [ApiIssueController::class, 'getIssueMembers'])->name('api.issues.members.get');
});