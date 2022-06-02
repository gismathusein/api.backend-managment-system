<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1', 'middleware' => 'cors'], function () {

    Route::group(['prefix' => 'settings', 'middleware' => ['jwt.verify']], function () {
        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'companies']);
            Route::post('/', [CompanyController::class, 'store'])->middleware('admin');
            Route::post('/{id}', [CompanyController::class, 'update'])->where('id', '[0-9]+')->middleware('admin');
            Route::get('/{id}/users', [CompanyController::class, 'users'])->where('id', '[0-9]+');
            Route::get('/{id}/departments', [CompanyController::class, 'departments'])->where('id', '[0-9]+');
            Route::get('/{id}/groups', [CompanyController::class, 'groups'])->where('id', '[0-9]+');
            Route::get('/{id}', [CompanyController::class, 'show'])->where('id', '[0-9]+');
            Route::delete('/destroy/{id}', [CompanyController::class, 'destroy'])->where('id', '[0-9]+')->middleware('admin');;
        });

        Route::prefix('departments')->group(function () {
            Route::get('/', [DepartmentController::class, 'index']);
            Route::post('/', [DepartmentController::class, 'store'])->middleware('admin');;
            Route::put('/{id}', [DepartmentController::class, 'update'])->where('id', '[0-9]+')->middleware('admin');;
            Route::get('/{id}', [DepartmentController::class, 'show'])->where('id', '[0-9]+');
            Route::delete('/destroy/{id}', [DepartmentController::class, 'destroy'])->where('id', '[0-9]+')->middleware('admin');;
        });

        Route::prefix('positions')->group(function () {
            Route::get('/', [PositionController::class, 'index']);
            Route::get('/{id}', [PositionController::class, 'show']);
            Route::post('/', [PositionController::class, 'store'])->middleware('admin');;
            Route::put('/{id}', [PositionController::class, 'update'])->middleware('admin');;
            Route::delete('/destroy/{id}', [PositionController::class, 'destroy'])->middleware('admin');;
        });

        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'groups']);
            Route::post('/', [GroupController::class, 'store'])->middleware('admin');
            Route::put('/{id}', [GroupController::class, 'update'])->where('id', '[0-9]+')->middleware('admin');;
            Route::get('/{id}/users', [GroupController::class, 'users'])->where('id', '[0-9]+');
            Route::get('/{id}/companies', [GroupController::class, 'companies'])->where('id', '[0-9]+');
            Route::get('/{id}', [GroupController::class, 'show'])->where('id', '[0-9]+');
            Route::delete('/destroy/{id}',[GroupController::class, 'destroy'])->where('id', '[0-9]+')->middleware('admin');;
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [AuthController::class, 'users']);
            Route::get('/get', [AuthController::class, 'getUsers'])->where('id', '[0-9]+');
            Route::put('/{id}', [AuthController::class, 'update'])->where('id', '[0-9]+')->middleware('admin');;
            Route::get('/{id}', [AuthController::class, 'show'])->where('id', '[0-9]+');
            Route::post('/{id}', [AuthController::class, 'destroy'])->where('id', '[0-9]+')->middleware('admin');;
            Route::post('{id}/reset/', [AuthController::class, 'resetPassword'])->where('id', '[0-9]+')->middleware('admin');;
            Route::delete('/destroy/{id}', [AuthController::class, 'destroy'])->where('id', '[0-9]+')->middleware('admin');;
        });

        Route::prefix('options')->group(function () {
            Route::get('/companies', [CompanyController::class, 'companyOptions']);
            Route::get('/departments', [DepartmentController::class, 'departmentOptions']);
            Route::get('/positions', [PositionController::class, 'positionOptions']);
            Route::get('/groups', [GroupController::class, 'groupOptions']);
            Route::get('/users', [AuthController::class, 'groupUsers']);
        });

        Route::get('/report', [ReportController::class, 'report']);

    });

    Route::get('settings/groups/{id}/tasks', [GroupController::class, 'tasks'])->middleware('jwt.verify');

    Route::group(['prefix' => 'tasks', 'middleware' => ['jwt.verify']], function () {
        Route::post('/update/{id}', [TaskController::class, 'update'])->where('id', '[0-9]+');
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/', [TaskController::class, 'store']);
        Route::get('/{groupId}/me', [TaskController::class, 'getUserTasks']);
        Route::get('/created', [TaskController::class, 'getCreatedTasks']);
        Route::get('/group/{groupId}', [TaskController::class, 'getGroupTasks']);
        Route::get('/group/{groupId}/role', [TaskController::class, 'getIsGroupAdmin']);
        Route::get('/{id}', [TaskController::class, 'show'])->where('id', '[0-9]+');
        Route::post('/{assignedId}/assign/{id}', [TaskController::class, 'assigned'])->middleware('group.admin');
        Route::post('/reassign/{id}', [TaskController::class, 'reassign']);
        Route::post('/forward', [TaskController::class, 'forwarded']);
        Route::post('/reject', [TaskController::class, 'reject']);
        Route::post('/decision', [TaskController::class, 'decision']);
        Route::post('/destroy/{id}', [TaskController::class, 'decision']);
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.verify');
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.verify');
        Route::get('/me', [AuthController::class, 'userProfile'])->middleware('jwt.verify');
        Route::post('/register', [AuthController::class, 'register'])->middleware('jwt.verify', 'admin');
    });

    Route::group(['prefix' => 'test'], function () {
        Route::get('/500', function () {
            return response()->json([
                'message' => '500 Server Error'
            ], 500);
        });

        Route::get('/401', function () {
            return response()->json([
                'message' => '401 Unauthorized'
            ], 401);
        });
    });
});




