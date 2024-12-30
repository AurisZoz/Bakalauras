<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PictureController;
use App\Http\Controllers\Api\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload', [FileUploadController::class, 'upload']);
    Route::get('/files/{id}', [FileUploadController::class, 'download']);
    Route::delete('/files/{id}', [FileUploadController::class, 'destroy']);

    Route::post('/profile/upload-photo', [PictureController::class, 'uploadPhoto']);
    Route::delete('/profile/delete-photo', [PictureController::class, 'deletePhoto']);

    Route::get('/users/search', [UserController::class, 'search']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/contacts', [UserController::class, 'getContacts']);
    Route::post('/contacts/add', [UserController::class, 'addContact']);

    Route::get('/messages/{userId}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);

    Route::delete('/contacts/remove', [UserController::class, 'removeContact']);
    Route::post('/messages/{userId}/mark-as-read', [MessageController::class, 'markMessagesAsRead']);

});
