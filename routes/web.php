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

use App\ElectronicTDG;

date_default_timezone_set('America/Montreal');

Route::get('/', array(
    'uses' => 'MainController@showElectronicCatalog'
));

Route::get('login', array(
    'as' => 'login',
    'uses' => 'MainController@showLogin'
));

Route::post('login', array(
    'uses' => 'MainController@doLogin'
));

Route::get('logout', array(
    'uses' => 'AuthController@doLogout'
));

Route::get('/inventory', array(
    'uses' => 'AdminController@showInventory'
));

Route::post('inventory', array(
    'uses' => 'AdminController@doModifyOrDelete'
));

Route::post('modify', array(
    'uses' => 'AdminController@doModify'
));

Route::get('/add-electronic-specification', array(
    'uses' => 'AdminController@showAddElectronicSpecification'
));

Route::post('add-electronic-specification', array(
    'uses' => 'AdminController@doAddElectronicSpecification'
));

Route::get('registration', array(
    'uses' => 'MainController@showRegistration'
));

Route::post('registration', array(
    'uses' => 'MainController@doRegistration'
));

Route::get('details', array(
    'uses' => 'MainController@showDetails'
));

Route::get('add-to-cart', array(
    'uses' => 'CustomerController@doAddToCart'
));

Route::get('shopping-cart', array(
    'uses' => 'CustomerController@doViewCart'
));

Route::get('remove-from-cart', array(
    'uses' => 'CustomerController@doRemove'
));

Route::get('/users', array(
    'uses' => 'AdminController@showAllCustomers'
));

Route::get('/checkout', array(
    'uses' => 'CustomerController@showCheckout'
));

Route::get('/checkout-cancel', array(
    'uses' => 'CustomerController@cancelCheckout'
));

Route::get('/checkout-pay', array(
    'uses' => 'CustomerController@doPayment'
));

Route::get('/view-my-account',array(
	'uses' => 'CustomerController@doViewAccount'
));

Route::get('/delete-my-account',array(
	'uses' => 'CustomerController@doDeleteMyAccount'
));

Route::get('/my-orders',array(
	'uses' => 'CustomerController@showMyOrders'
));

Route::get('/return',array(
	'uses' => 'CustomerController@doPrepareReturn'
));

Route::post('my-orders', array(
    'uses' => 'CustomerController@doCompleteOrCancelReturns'
));
