<?php

use App\Models\AdminRole;
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

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
    // Account
    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::post('register', 'AccountController@register')->name('register');
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('active', 'AccountController@active')->name('active');
        Route::post('forget-password', 'AccountController@forgetPassword')->name('forget_password');
        Route::post('create-new-password', 'AccountController@createNewPassword')->name('create_new_password');
        Route::group(['middleware' => ['jwt']], function () {
            Route::get('logout', 'AuthController@logout')->name('logout');
            Route::post('update', 'AccountController@update')->name('update');
            Route::get('profile', 'AccountController@profile')->name('profile');
        });
    });

    // Profile
    Route::group(['middleware' => ['jwt:profile,' . ADMIN_ROLE_MANAGER . ',' . ADMIN_ROLE_OPERATOR], 'prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::group(['prefix' => 'career', 'as' => 'career.'], function () {
            Route::get('', 'CareerController@index')->name('index');
            Route::post('create', 'CareerController@create')->name('create');
            Route::post('update', 'CareerController@update')->name('update');
            Route::post('delete', 'CareerController@delete')->name('delete');
        });
        Route::group(['prefix' => 'application', 'as' => 'application.'], function () {
            Route::get('', 'ProfileApplicationController@index')->name('index');
            Route::post('create', 'ProfileApplicationController@create')->name('create');
            Route::post('update', 'ProfileApplicationController@update')->name('update');
            Route::post('delete', 'ProfileApplicationController@delete')->name('delete');
        });
        Route::group(['prefix' => 'skill', 'as' => 'skill.'], function () {
            Route::get('', 'ProfileSkillController@index')->name('index');
            Route::post('create', 'ProfileSkillController@create')->name('create');
            Route::post('update', 'ProfileSkillController@update')->name('update');
            Route::post('delete', 'ProfileSkillController@delete')->name('delete');
        });
        Route::group(['prefix' => 'portfolio', 'as' => 'portfolio.'], function () {
            Route::get('', 'PortfolioController@index')->name('index');
            Route::post('create', 'PortfolioController@create')->name('create');
            Route::post('update', 'PortfolioController@update')->name('update');
            Route::post('delete', 'PortfolioController@delete')->name('delete');
        });
        Route::group(['prefix' => 'award', 'as' => 'award.'], function () {
            Route::get('', 'AwardController@index')->name('index');
            Route::post('create', 'AwardController@create')->name('create');
            Route::post('update', 'AwardController@update')->name('update');
            Route::post('delete', 'AwardController@delete')->name('delete');
        });
    });

    // Search
    Route::group(['middleware' => ['jwt'], 'prefix' => 'search', 'as' => 'search.'], function () {
        Route::get('', 'SearchController@search')->name('index');
    });

    // Admin
    Route::group(['middleware' => ['jwt:admin']], function () {
        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
            Route::get('profiles', 'NotificationController@listNewRegisteredProfile')->name('profiles');
        });
        Route::group(['middleware' => ['manager'], 'prefix' => 'manager', 'as' => 'manager.'], function () {
            Route::post('import', 'ManagerController@import')->name('import');
            Route::get('', 'ManagerController@index')->name('index');
            Route::get('detail', 'ManagerController@detail')->name('detail');
            Route::post('update', 'ManagerController@update')->name('update');
            Route::post('delete', 'ManagerController@delete')->name('delete');
        });
        Route::group(['prefix' => 'archive', 'as' => 'archive.'], function () {
            Route::get('', 'ArchiveController@index')->name('index');
            Route::group(['prefix' => 'profile/{id}', 'as' => 'profile.'], function ($group) {
                Route::get('', 'ProfileController@detail', function ($id) {
                    return $id;
                })->name('detail');
                Route::get('career', 'CareerController@index', function ($id) {
                    return $id;
                })->name('career');
                Route::get('application', 'ProfileApplicationController@index', function ($id) {
                    return $id;
                })->name('application');
                Route::get('skill', 'ProfileSkillController@index', function ($id) {
                    return $id;
                })->name('skill');
                Route::get('portfolio', 'PortfolioController@index', function ($id) {
                    return $id;
                })->name('portfolio');
                Route::get('award', 'AwardController@index', function ($id) {
                    return $id;
                })->name('award');

                // Make sure only param is id with integer number is acceptable.
                foreach ($group->getRoutes() as $route) {
                    $route->where('id', '[0-9]+');
                }
            });
        });
        Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
            Route::get('profile/{id}', 'ReviewController@index')->where('id', '[0-9]+')->name('index');
            Route::post('create', 'ReviewController@create')->name('create');
            Route::get('detail', 'ReviewController@detail')->name('detail');
            Route::post('update', 'ReviewController@update')->name('update');
            Route::post('delete', 'ReviewController@delete')->name('delete');

        });
        Route::group(['prefix' => 'adminrole', 'as' => 'adminrole.'], function () {
            Route::post('update', 'AdminRoleController@update')->name('update');
        });
        
    });

    // Mixed roles admin|profile
    Route::group(['middleware' => ['jwt:admin,profile']], function () {
        Route::group(['prefix' => 'mail', 'as' => 'mail.'], function () {
            Route::post('notify', 'MailController@notify')->name('notify');
        });
    });
});
