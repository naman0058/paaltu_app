<?php

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\BreedController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PetCategoryController;
use App\Http\Controllers\API\PetProfileController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 
Route::group(['prefix' => 'v1'], function () {
    Route::post('login',  [LoginController::class,'login']);
    Route::post('signup',  [LoginController::class,'signup']); 
    Route::post('vendor-signup',  [LoginController::class,'vendorSignUp']); 
    Route::post('vendor-otp-verify',  [LoginController::class,'vendorOtpVerify']); 
    Route::post('vendor-login',  [LoginController::class,'vendorLogin']); 

    Route::post('user-signup',  [LoginController::class,'userSignUp']); 
    Route::post('user-otp-verify',  [LoginController::class,'userOtpVerify']); 
    Route::post('user-login',  [LoginController::class,'userLogin']);

    Route::group(['middleware' => 'auth:api'], function() { 
        Route::post('logout', [LoginController::class,'logout']); 
        Route::get('blog', [BlogController::class,'blog']);
        Route::get('blog-info/{id}', [BlogController::class,'blogInfo']); 
        Route::get('services', [ServiceController::class,'services']);
        Route::get('service-info/{id}', [ServiceController::class,'serviceInfo']); 
        Route::get('pet-profile/{id}', [PetProfileController::class,'profile']);
        //pet category
        Route::get('pet-category', [PetCategoryController::class,'petCategory']);
        Route::get('pet-category-info/{id}', [PetCategoryController::class,'petCategoryInfo']);

        //beed
        Route::get('breed', [BreedController::class,'breed']);
        Route::get('breed-info/{id}', [BreedController::class,'breedInfo']);
        Route::get('category-breed/{category_id}',[BreedController::class,'categoryBreed']);
        Route::group(['prefix' => 'chat'], function() {
            Route::post('send',[ChatController::class,'send']); 
            Route::get('get-chats',[ChatController::class,'getChats']); 
            Route::get('get-chats/vendors',[ChatController::class,'getVendorChats']); 
            Route::get('get-members',[ChatController::class,'getMembers']); 
            Route::get('get-members/users',[ChatController::class,'getUserMembers']); 
            Route::get('get-members/vendors',[ChatController::class,'getVendorMembers']); 
            Route::post('get-messages',[ChatController::class,'getMessages']);
            Route::post('delete-message',[ChatController::class,'deleteMessage']);
            Route::post('delete-chat',[ChatController::class,'deleteChat']);
        });
        Route::group(['prefix' => 'home'], function() {
            Route::post('get-breeds',[HomeController::class,'getBreeds']); 
            Route::get('get-categories',[HomeController::class,'getCategories']); 
            Route::post('get-pets',[HomeController::class,'getPets']); 
            Route::post('follow',[HomeController::class,'follow']); 
            Route::get('get-requests',[HomeController::class,'getRequests']); 
            Route::post('confirm-request',[HomeController::class,'confirmRequest']); 
        });
        Route::group(['prefix' => 'service'], function() {
            Route::post('get-services',[HomeController::class,'getServices']); 
            Route::get('get-service/{id}',[HomeController::class,'getService']);
            Route::get('get-vendor/{id}',[HomeController::class,'getVendor']);
            Route::post('book-service',[HomeController::class,'bookService']);
            Route::get('get-bookings',[HomeController::class,'getBookings']);
            Route::post('get-booking',[HomeController::class,'getBooking']);
            Route::post('get-available-sessions',[HomeController::class,'getAvailableSessions']);
        });
        Route::group(['prefix' => 'profile'], function() {
            Route::get('get-user',[HomeController::class,'getUser']); 
            Route::post('change-password', [LoginController::class,'changePassword']);
            Route::post('edit-profile',[HomeController::class,'editProfile']); 
            Route::post('get-user-profile',[HomeController::class,'getUserProfile']); 
            Route::get('get-pets',[HomeController::class,'getPetsProfile']); 
            Route::post('get-pet',[HomeController::class,'getPet']); 
            Route::post('add-pet',[HomeController::class,'addPet']); 
            Route::post('edit-pet',[HomeController::class,'editPet']); 
            Route::post('upload-pet-photo',[HomeController::class,'uploadPetPhoto']);
            Route::post('delete-pet-photo',[HomeController::class,'deletePetPhoto']);
            Route::post('get-pet-photos',[HomeController::class,'getPetPhotos']);
            Route::post('update-primary-pet',[HomeController::class,'updatePrimaryPet']);
            Route::post('delete-pet',[HomeController::class,'deletePet']); 
        });
    });

    //vendor
    Route::group(['prefix' => 'vendor','middleware' => 'auth:api'], function() { 
        //Route::post('login',  [LoginController::class,'login']);
        
        Route::post('logout', [LoginController::class,'logout']); 
        Route::group(['prefix' => 'chat'], function() {
            Route::post('send',[ChatController::class,'send']); 
            Route::get('get-chats',[ChatController::class,'getChats']); 
            Route::get('get-chats/vendors',[ChatController::class,'getVendorChats']); 
            Route::get('get-members',[ChatController::class,'getMembers']); 
            Route::get('get-members/users',[ChatController::class,'getUserMembers']); 
            Route::get('get-members/vendors',[ChatController::class,'getVendorMembers']); 
            Route::post('get-messages',[ChatController::class,'getMessages']);
            Route::post('delete-message',[ChatController::class,'deleteMessage']);
            Route::post('delete-chat',[ChatController::class,'deleteChat']);
        });
        Route::group(['prefix' => 'profile'], function() {
            Route::get('get-user',[VendorController::class,'getUser']); 
            Route::post('change-password', [LoginController::class,'changePassword']);
            Route::post('edit-profile',[VendorController::class,'editProfile']); 
            Route::post('add-vendor-setting',[VendorController::class,'addVendorSetting']); 
            Route::post('change-password', [LoginController::class,'changePassword']);
        });
        Route::group(['prefix' => 'service'], function() {
            Route::get('get-main-services',[VendorController::class,'getMainServices']); 
            Route::post('get-services',[VendorController::class,'getServices']); 
            Route::get('get-service/{id}',[VendorController::class,'getService']);
            Route::post('store-service',[VendorController::class,'storeService']); 
            Route::post('update-service',[VendorController::class,'updateService']); 
            Route::post('delete-service',[VendorController::class,'deleteService']); 
        });
        Route::group(['prefix' => 'bookings'], function() {
            Route::get('get-bookings',[VendorController::class,'getBookings']); 
            Route::post('booking-status-change',[VendorController::class,'bookingStatusChange']); 
        }); 
    });
});