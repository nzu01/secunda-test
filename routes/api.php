<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateByToken;


Route::prefix('v1')->middleware(AuthenticateByToken::class)->group(function () {
    Route::prefix('organizations')->group(function () {
        // поиск организаций по зданию
        Route::get('/by-building/{buildingUuid}', [OrganizationController::class, 'byBuilding'])
            ->whereUuid('buildingUuid');

        // поиск организаций по типу (только указанный тип)
        Route::get('/by-type/{typeUuid}', [OrganizationController::class, 'byType'])
            ->whereUuid('typeUuid');

        // поиск организаций по типу + всем дочерним типам
        Route::get('/by-type-with-descendants/{typeUuid}', [OrganizationController::class, 'byTypeWithDescendants'])
            ->whereUuid('typeUuid');

        // поиск организаций по названию типа (ILIKE) + дочерние типы
        // параметры title, offset, limit идут в query string
        Route::get('/by-type-title-with-descendants', [OrganizationController::class, 'byTypeTitleWithDescendants']);

        // поиск организаций в радиусе точки
        // параметры lat, lng, radius идут в query string
        Route::get('/within-radius', [OrganizationController::class, 'withinRadius']);

        // поиск организаций по названию самой организации
        // параметр q в query string
        Route::get('/search-by-title', [OrganizationController::class, 'searchByTitle']);

        // получить организацию по UUID
        Route::get('/{uuid}', [OrganizationController::class, 'show'])
            ->whereUuid('uuid');
    });

    // Buildings
    Route::prefix('buildings')->group(function () {
        Route::get('/', [BuildingController::class, 'index']);
    });

    // Organization Types
    Route::prefix('organization-types')->group(function () {
        Route::get('/',  [OrganizationTypeController::class, 'index']);
    });
});