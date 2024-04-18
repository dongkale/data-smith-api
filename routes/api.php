<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PartController;

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

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::middleware(["logger"])->group(function () {
    Route::prefix("part")->group(function () {
        Route::get("/list", [PartController::class, "index"]);
        Route::post("/save", [PartController::class, "store"]);
        Route::get("/list/{id}", [PartController::class, "show"]);
        Route::get("/listByName/{name}", [PartController::class, "partByName"]);

        // Route::get("/{id}", [ItemController::class, "show"]);
        // Route::put('items/{id}', [ItemController::class, 'update']);
        // Route::delete('items/{id}', [ItemController::class, 'destroy']);
    });
});
