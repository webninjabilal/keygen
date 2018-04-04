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

Route::get('/', function () {
    if(\Auth::guest())
        return redirect('login');
    else
        return redirect('home');
});
/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/auth/logout', 'Auth\LoginController@logout');
Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/my-account', 'HomeController@my_account')->name('my_account');
    Route::get('/purchase-unit', 'UserController@getPurchaseUnits')->name('purchase_units');
    Route::get('/available-unit/{num}', 'UserController@getPurchaseUnit')->name('purchase_unit');
    Route::post('user/add-cart-unit', 'UserController@postAddCartUnit')->name('user_add_cart_unit');
    Route::get('user/order/{num}', 'UserController@getUserOrder')->name('user_quick_view_order');

    Route::get('company/records', 'CompanyController@records')->name('company_records');
    Route::resource('company', 'CompanyController');

    //User Routes
    Route::get('user/records', 'UserController@records')->name('user_records');
    Route::post('user/create-machine', 'UserController@postCreateMachine')->name('user_machine_create');
    Route::get('user/update-machine/{num}', 'UserController@getUpdateMachine')->name('user_machine_get');
    Route::post('user/machine-update', 'UserController@postUpdateMachine')->name('user_machine_update');
    Route::post('user/delete-machine', 'UserController@postDeleteMachine')->name('user_machine_delete');
    Route::resource('user', 'UserController');

    Route::get('sheet/records', 'SheetController@records')->name('sheet_records');
    Route::resource('sheet', 'SheetController');

    Route::get('unit/records', 'UnitController@records')->name('unit_records');
    Route::resource('unit', 'UnitController');

    Route::get('machine/records', 'MachineController@records')->name('machine_records');
    Route::resource('machine', 'MachineController');

    Route::get('user-role/records', 'RoleController@records')->name('user_role_records');
    Route::resource('user-role', 'RoleController');
});

