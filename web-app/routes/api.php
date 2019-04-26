<?php
use App\Http\Middleware\CheckAPIToken;

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'api_auth_token'], function() {
	Route::resource('users', 'API\UserManagementController')->only([
		'index', 
		'store', 
		'show', 
		'update',
		'destroy'
	]);
});
Route::post('login', 'API\LoginController@post');
