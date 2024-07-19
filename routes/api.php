<?php
use App\Http\Controllers\UserController;

Route::group(['prefix'=>'v1','as'=>'account.'], function(){
    Route::get('/', function(){
        return [
            'tested'
        ];
    });

    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    
    Route::middleware('auth:api')->group(function() {
        Route::get('/dashboard', [UserController::class, 'me']);
        Route::get('/refresh', [UserController::class, 'refresh']);
        Route::post('/logout', [UserController::class, 'logout']);
    });
});
?>