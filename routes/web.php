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

Route::get('/register', 'Auth\RegisterController@index')->name('register');
Route::post('/register', 'Auth\RegisterController@register');
Route::get('/login', 'Auth\LoginController@index')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LogoutController@logout')->name('logout');

Route::get('/', 'Profile\ProfileController@home')->name('home');
Route::get('/contests', 'Problem\ProblemListController@show')->name('contests');
Route::get('/problems', 'Problem\ProblemListController@show')->name('problems');
Route::get('/submissions', 'Problem\ProblemListController@show')->name('submissions');
Route::get('/ranklist', 'Problem\ProblemListController@show')->name('ranklist');

Route::group(['prefix' => 'administration'], function () {
    Route::group(['prefix' => 'problems'], function () {
        Route::get('/', 'Problem\ProblemDashboardController@show')->name('administration.problems');
        Route::get('/create', 'Problem\ProblemController@create')->name('problem.create');
        Route::post('/create', 'Problem\ProblemController@store');

        Route::group(['prefix' => '{slug}'], function () {
        	Route::get('/overview', 'Administration\ProblemController@overview')->name('administration.problem.overview');
        	Route::get('/details', 'Administration\ProblemController@details')->name('administration.problem.details');
            Route::post('/details', 'Problem\ProblemController@updateDetails');
            Route::get('/preview_problem', 'Administration\ProblemController@previewProblem')->name('administration.problem.preview_problem');
            Route::get('/test_case', 'Administration\ProblemController@testCaseList')->name('administration.problem.test_case');
            Route::get('/test_case/add', 'Administration\ProblemController@testCaseAdd')->name('administration.problem.test_case.add');
            Route::post('/test_case/add', 'TestCase\TestCaseController@addTestCase')->name('administration.problem.test_case.add');
        });
    });

});


Route::get('/settings/profile', 'Setting\SettingController@profile')->name('settings.profile');

Route::get('/profile/{handle}', 'Profile\ProfileController@show')->name('profile');

Route::get('/profilee/info', 'Profile\ProfileController@info')->name('profile.info');

Route::get('user/{id}', function ($id) {
    echo "$id";
});

Route::get('/modal', function () {
    return view('includes.modal');
});

Route::get('/footer', function () {
    return view('includes.footer');
});

Route::get('/settings/general', 'Setting\SettingController@generalSettings')->name('settings.general');
Route::post('/profile/update_profile','Profile\ProfileController@updateProfile')->name('profile.update_profile');

Route::get('/settings/security','Setting\SettingController@changePassword')->name('settings.change_password');
Route::post('/profile/update_password','Profile\ProfileController@updatePassword')->name('profile.update_password');

Route::get('/settings/change_avatar','Setting\SettingController@changeAvatar')->name('settings.change_avatar');
Route::post('/profile/update_avatar','Profile\ProfileController@updateAvatar')->name('profile.update_avatar');
