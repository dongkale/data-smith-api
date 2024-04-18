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

Route::middleware(["logger", "with_fast_api_key"])->group(function () {
    Route::prefix("part")->group(function () {
        Route::get("/list", [PartController::class, "index"]);
        Route::post("/save", [PartController::class, "store"]);

        Route::get("/listByName/{name}", [PartController::class, "listByName"]);

        Route::put("/updateByName/{name}", [
            PartController::class,
            "updateByName",
        ]);

        Route::delete("/deleteByName/{name}", [
            PartController::class,
            "deleteByName",
        ]);

        Route::get("/list/{id}", [PartController::class, "show"]);
        Route::put("/update/{id}", [PartController::class, "update"]);
    });
});
