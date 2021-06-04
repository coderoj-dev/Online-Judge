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
    Route::get('/', 'Administration\AdministrationController@index')->name('administration');
    Route::group(['prefix' => 'problems'], function () {
        Route::get('/', 'Problem\ProblemDashboardController@show')->name('administration.problems');
        Route::get('/create', 'Problem\ProblemController@create')->name('problem.create');
        Route::post('/create', 'Problem\ProblemController@store');

        Route::group(['prefix' => '{slug}'], function () {
            Route::get('/delete', 'Administration\ProblemController@deleteProblem')->name('administration.problem.delete');
            Route::get('/overview', 'Administration\ProblemController@overview')->name('administration.problem.overview');
            Route::get('/statement', 'Administration\ProblemController@details')->name('administration.problem.statement');
            Route::post('/statement', 'Problem\ProblemController@updateDetails');
            Route::get('/preview_problem', 'Administration\ProblemController@previewProblem')->name('administration.problem.preview_problem');
            Route::get('/test_case', 'Administration\ProblemController@testCaseList')->name('administration.problem.test_case');
            Route::get('/test_case/add', 'Administration\ProblemController@testCaseAdd')->name('administration.problem.test_case.add');
            Route::post('/test_case/add', 'TestCase\TestCaseController@addTestCase')->name('administration.problem.test_case.add');
            Route::get('/test_case/{test_case_id}/delete', 'TestCase\TestCaseController@deleteTestCase')->name('problem.test_case.delete');

            Route::get('/test_case/{test_case_id}/edit', 'Administration\ProblemController@updateTestCase')->name('problem.test_case.edit');
            Route::post('/test_case/{test_case_id}/edit', 'TestCase\TestCaseController@updateTestCase')->name('problem.test_case.edit');
            Route::get('/test_case/{test_case_id}/update_sample', 'TestCase\TestCaseController@updateSample')->name('problem.test_case.update_sample');

            Route::get('checker', 'Administration\ProblemController@checker')->name('administration.problem.checker');
            Route::post('checker', 'Problem\ProblemController@updateChecker');

            Route::get('/languages', 'Administration\ProblemController@languages')->name('administration.problem.languages');
            Route::post('/languages/save', 'Administration\ProblemController@saveLanguages')->name('administration.problem.save_languages');


            Route::get('/moderators', 'Administration\ProblemController@moderators')->name('administration.problem.moderators');
            Route::post('/get_moderators_list', 'Administration\Problem\ModeratorController@getModeratorsList')->name('administration.problem.get_moderators_list');
            Route::post('/add_moderator', 'Administration\Problem\ModeratorController@addModerator')->name('administration.problem.add_moderator');
            Route::post('/delete_moderator', 'Administration\Problem\ModeratorController@deleteModerator')->name('administration.problem.delete_moderator');

            Route::get('/test_submissions', 'Administration\ProblemController@viewTestSubmission')->name('administration.problem.test_submissions');
            Route::get('/create_test_submission', 'Administration\ProblemController@viewTestSubmissionEditor')->name('administration.problem.create_test_submission');
            Route::get('/test_submission/create', 'Administration\ProblemController@viewTestSubmissionEditor')->name('administration.problem.test_submission.create');
            Route::post('/test_submission/create', 'Submission\SubmissionController@createTestSubmission');
            Route::get('/test_submissions/{submission_id}', 'Administration\ProblemController@viewTestSubmissionPage')->name('administration.problem.submission.view');

        });
    });
    Route::group(['prefix' => 'settings'], function () {
        Route::group(['prefix' => 'languages'], function () {
            Route::get('/', 'Administration\Language\LanguageDashboardController@show')->name('administration.settings.languages');
            Route::get('/create', 'Administration\Language\LanguageController@create')->name('administration.settings.languages.create');
            Route::post('/store', 'Administration\Language\LanguageController@store')->name('administration.settings.languages.store');
            Route::group(['prefix' => '{language_id}'], function () {
                Route::get('/edit', 'Administration\Language\LanguageController@edit')->name('administration.settings.languages.edit');
                Route::put('/update', 'Administration\Language\LanguageController@update')->name('administration.settings.languages.update');
                Route::get('/toggleArchive', 'Administration\Language\LanguageController@toggleArchive')->name('administration.settings.languages.toggle_archive');
            });
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
Route::post('/profile/update_profile', 'Profile\ProfileController@updateProfile')->name('profile.update_profile');

Route::get('/settings/security', 'Setting\SettingController@changePassword')->name('settings.change_password');
Route::post('/profile/update_password', 'Profile\ProfileController@updatePassword')->name('profile.update_password');

Route::get('/settings/change_avatar', 'Setting\SettingController@changeAvatar')->name('settings.change_avatar');
Route::post('/profile/update_avatar', 'Profile\ProfileController@updateAvatar')->name('profile.update_avatar');

/// problem settings

Route::get('/problem/settings/update/{slug}', 'Administration\ProblemController@updateSettings')->name('settings.update');
Route::post('/problem/settings/edit/{slug}', 'Administration\ProblemController@editSettings')->name('settings.edit');
