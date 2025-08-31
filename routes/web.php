<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- 1. Public / Welcome Page ---
Route::get('/', function () {
    return view('welcome');
});

// --- 2. Default Dashboard Route (from Breeze) ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- 3. Authenticated Routes Group ---
Route::middleware('auth')->group(function () {
    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Our Application's Custom Routes
    // Project Routes
    Route::resource('projects', ProjectController::class);

    // Nested Issue Routes (for issues within a specific project)
    // Deleting from here uses IssueController@destroy and redirects to projects.show
    Route::resource('projects.issues', IssueController::class);

    // Global Issues List (with filters and search)
    Route::get('issues', [IssueController::class, 'index'])->name('issues.index');

    // Global Issues Delete (NEW ROUTE! For deleting an issue directly from the /issues list)
    // This route uses the new globalDestroy method and redirects back to the /issues list.
    Route::delete('issues/{issue}', [IssueController::class, 'globalDestroy'])->name('issues.global_destroy');

    // Tag Routes (CRUD)
    Route::resource('tags', TagController::class)->except(['show']);

    // --- START: AJAX API ROUTES FOR ISSUE DETAILS (Full versions of these methods are in IssueController) ---
    Route::get('/api/issues/{issue}/tags', [IssueController::class, 'getTags'])->name('api.issues.tags.index');
    Route::post('/api/issues/{issue}/tags/{tag}/toggle', [IssueController::class, 'toggleTag'])->name('api.issues.tags.toggle');
    Route::get('/api/issues/{issue}/comments', [IssueController::class, 'getComments'])->name('api.issues.comments.index');
    Route::post('/api/issues/{issue}/comments', [IssueController::class, 'addComment'])->name('api.issues.comments.store');
    Route::get('/api/issues/{issue}/members', [IssueController::class, 'getMembers'])->name('api.issues.members.index');
    Route::post('/api/issues/{issue}/members/{user}/toggle', [IssueController::class, 'toggleMember'])->name('api.issues.members.toggle');
    // --- END: AJAX API ROUTES FOR ISSUE DETAILS ---

});

// --- 4. Breeze Authentication Handlers ---
require __DIR__.'/auth.php';