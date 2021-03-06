<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontController@index');

Route::get('/accept/{token}','UserController@accept');

Route::get('/preview/{id}/{content_id?}','PreviewController@index');

Route::get('/download_template/{id}','DeliveryController@downloadTemplate')->middleware('auth');


Route::group(['prefix' => 'ajax','middleware' => 'auth'], function () {
	Route::get('user', function (Request $request) {
	    return Auth::user();
	});
	Route::get('status','StatusController@index');
	
	Route::resource('partners', PartnerController::class,['only' => ['index','show']]);
	Route::resource('audiences', AudienceController::class,['except' => ['create', 'edit']]);
	Route::resource('regions', RegionController::class,['except' => ['create', 'edit']]);
	Route::resource('campaigns', CampaignController::class,['except' => ['create', 'edit']]);
	
	Route::resource('deliveries', DeliveryController::class,['except' => ['create', 'edit']]);
	Route::get('deliveries/{id}/expire',"DeliveryController@expire");
	Route::get('deliveries/{id}/publish',"DeliveryController@publish");

	Route::resource('customs', DeliveryCustomController::class,['except' => ['create', 'edit']]);
	Route::put('deliveries/{id}/distribution', "DeliveryController@saveDistribution");

	
	//Read Only Resources
	Route::resource('sizes', SizeController::class,['only' => [ 'index','show']]);
	Route::resource('types', TypeController::class,['only' => [ 'index','show']]);
	Route::resource('components', ComponentController::class,['only' => [ 'index','show']]);
	Route::resource('countries', CountryController::class,['only' => [ 'index','show']]);
	Route::resource('languages', LanguageController::class,['only' => [ 'index','show']]);

	Route::get('regions/{id}/remove/{country_id}', 'RegionController@removeCountry');
	Route::get('regions/{id}/add/{country_id}', 'RegionController@addCountry');

	Route::post('content/{id}', 'ContentController@add');

	Route::delete('content/{id}', 'ContentController@delete');

	Route::put('content/{delivery_id}/{content_id}', 'ContentController@edit');
	Route::get('content/{delivery_id}/{content_id}/publish', 'ContentController@publish');
	Route::get('content/{delivery_id}/{content_id}/expire', 'ContentController@expire');

	Route::group(['prefix' => 'admin','middleware' => ['auth','auth.admin']],function(){
		Route::resource('partners', PartnerController::class,['except' => ['create', 'edit']]);
		Route::post('users/validate','UserController@validateEmail');
		Route::post('users/invite','UserController@invite');
		Route::resource('users', UserController::class,['except' => ['create', 'edit']]);
		Route::get('users/{id}/activate', 'UserController@activate');
		Route::get('users/{id}/deactivate', 'UserController@deactivate');
		Route::get('users/{id}/resendinvite', 'UserController@resendinvite');
	
	});


});


Auth::routes();




