<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/',function(){
	return view('auth.login');

});
 
 // register page not found
Auth::routes(['register' => false]);
Route::get('register',function(){
	return view('admin.notfound');
});

// login page not found
Route::get('login',function(){
	return view('admin.notfound');
});


// reset password
Route::get('reset_password',function(){
	return view('auth.passwords.reset');
});

// webadminlogin(login)
Route::get('login',function(){
	return view('auth.login');
});
Route::group(['middleware' => ['auth']], function () {
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
	Route::get('/dashboard',[App\Http\Controllers\HomeController::class, 'index'])->name('dashboard'); 
});
Route::group(['middleware' => ['auth','admin']], function () {


// service

Route::resource('service', 'App\Http\Controllers\ServiceController');
Route::post('service-delete', 'App\Http\Controllers\ServiceController@destroy');
Route::get('serviceview', 'App\Http\Controllers\ServiceController@serviceView');
Route::get('filter-service', 'App\Http\Controllers\ServiceController@filter_service');
Route::post('update-images', 'App\Http\Controllers\ServiceController@updateImages');
Route::post('images-delete', 'App\Http\Controllers\ServiceController@imagesDelete');
Route::post('featureStore','App\Http\Controllers\ServiceController@featureStore')->name('featureStore');

// pet_category

Route::resource('pet-category', 'App\Http\Controllers\PetcategoryController');
Route::post('pet-category-delete','App\Http\Controllers\PetcategoryController@destroy');
Route::get('pet-category-view','App\Http\Controllers\PetcategoryController@petcategoryView');
Route::get('filter-pet-category','App\Http\Controllers\PetcategoryController@filter_pet_category');
Route::post('update-image', 'App\Http\Controllers\PetcategoryController@updateImages');
Route::post('image-delete', 'App\Http\Controllers\PetcategoryController@imagesDelete');

// setting

Route::resource('setting','App\Http\Controllers\SettingController');
Route::get('filter-setting','App\Http\Controllers\SettingController@filter_setting');
Route::get('setting-view','App\Http\Controllers\SettingController@settingView');
Route::post('setting-delete','App\Http\Controllers\SettingController@destroy');
Route::get('get-value','App\Http\Controllers\SettingController@getValue');

// breed

Route::resource('breed','App\Http\Controllers\BreedController');
Route::post('breed-delete','App\Http\Controllers\BreedController@destroy');
Route::get('breed-view','App\Http\Controllers\BreedController@breedView');
Route::get('filter-breed','App\Http\Controllers\BreedController@filter_breed');

// pet-profile

Route::resource('pet-profile','App\Http\Controllers\PetprofileController');
Route::post('pet-profile-delete','App\Http\Controllers\PetprofileController@destroy');
Route::get('pet-profile-view','App\Http\Controllers\PetprofileController@petprofileView');
Route::get('filter-pet-profile','App\Http\Controllers\PetprofileController@filter_pet_profile');
Route::post('removeImage','App\Http\Controllers\PetprofileController@removeImage');

// blog

Route::resource('blog','App\Http\Controllers\BlogController');
Route::post('blog-delete','App\Http\Controllers\BlogController@destroy');
Route::get('blog-view','App\Http\Controllers\BlogController@blogView');
Route::get('filter-blog','App\Http\Controllers\BlogController@filter_blog');
Route::post('update-img', 'App\Http\Controllers\BlogController@updateImages');
Route::post('img-delete', 'App\Http\Controllers\BlogController@imagesDelete');

// interest_table

Route::resource('interest','App\Http\Controllers\InterestController');
Route::get('filter-interest','App\Http\Controllers\InterestController@filter_interest');
Route::get('interest-view','App\Http\Controllers\InterestController@interestView');
Route::post('interest-delete','App\Http\Controllers\InterestController@destroy');

// booking

Route::resource('booking','App\Http\Controllers\BookingController');
Route::get('filter-booking','App\Http\Controllers\BookingController@filter'); 
Route::post('change-booking-status','App\Http\Controllers\BookingController@changeBookingStatus'); 


//
Route::resource('vendors','App\Http\Controllers\VendorController');
Route::get('vendors-services/{id}','App\Http\Controllers\VendorController@vendorsServices');
Route::get('vendors-service-filter/{id}','App\Http\Controllers\VendorController@vendorsServicesFilter');
Route::get('filter-vendor','App\Http\Controllers\VendorController@filter'); 
});


Route::group(['middleware' => ['auth','vendor']], function () {
	// booking
	Route::resource('my-booking','App\Http\Controllers\Vendor\BookingController');
	Route::get('my-filter-booking','App\Http\Controllers\Vendor\BookingController@filter'); 
	Route::post('my-change-booking-status','App\Http\Controllers\Vendor\BookingController@changeBookingStatus'); 

	// service
	Route::resource('my-service', 'App\Http\Controllers\Vendor\ServiceController');
	Route::post('my-service-delete', 'App\Http\Controllers\Vendor\ServiceController@destroy');
	Route::get('my-serviceview', 'App\Http\Controllers\Vendor\ServiceController@serviceView');
	Route::get('my-filter-service', 'App\Http\Controllers\Vendor\ServiceController@filter_service');
	Route::post('my-update-images', 'App\Http\Controllers\Vendor\ServiceController@updateImages');
	Route::post('my-images-delete', 'App\Http\Controllers\Vendor\ServiceController@imagesDelete');
	Route::post('myfeatureStore','App\Http\Controllers\Vendor\ServiceController@featureStore')->name('myfeatureStore');
});