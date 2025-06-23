<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\MergeHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index'])->name('contacts.index');

// Contact routes
Route::resource('contacts', ContactController::class);
Route::post('contacts/merge', [ContactController::class, 'merge'])->name('contacts.merge');

// Custom field routes
Route::resource('custom-fields', CustomFieldController::class)->names([
    'index' => 'custom-fields.index',
    'store' => 'custom-fields.store',
    'update' => 'custom-fields.update',
    'destroy' => 'custom-fields.destroy'
]);

// Merge history routes
Route::get('merge-history', [MergeHistoryController::class, 'index'])->name('merge-history.index');
Route::post('merge-history/{mergeHistory}/restore', [MergeHistoryController::class, 'restore'])->name('merge-history.restore');
