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

Route::get('/test-event', 'Submission\SubmissionController@testEvent')->name('testevent');

Route::get('/register', 'Auth\RegisterController@index')->name('register');
Route::post('/register', 'Auth\RegisterController@register');
Route::get('/login', 'Auth\LoginController@index')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LogoutController@logout')->name('logout');

Route::get('/', 'Profile\ProfileController@home')->name('home');
Route::get('/contests', 'Problem\ProblemListController@show')->name('contests');

Route::get('/submissions', 'Problem\ProblemListController@show')->name('submissions');
Route::get('/ranklist', 'Problem\ProblemListController@show')->name('ranklist');

Route::post('/request_for_moderator', 'Administration\Problem\ModeratorController@requestForModerator')->name('request_for_moderator');

Route::group(['prefix' => 'problems'], function () {
    Route::get('/', 'Problem\ProblemListController@show')->name('problems');
    Route::get('/{slug}', 'Problem\ProblemListController@viewProblem')->name('problem.view');
    Route::get('/{slug}/submit', 'Problem\ProblemController@createSubmission')->name('problem.submit');
    Route::post('/{slug}/submit', 'Submission\SubmissionController@createPracticeSubmission');
});

Route::group(['prefix' => 'contests'], function () {
    Route::get('/', 'Contest\ContestController@getContestList')->name('contests');
});

Route::group(['prefix' => '/{contest_slug}', 'middleware' => ['CheckContestPublish']], function () {
    Route::get('/', 'Contest\ContestController@contestInfo')->name('contests.info');
    // Route::get('/{problem}/Problem', 'ContestController@problem')->name('contest.problem');
    Route::group(['prefix' => 'arena', 'middleware' => ['CheckContestStart', 'CheckContestParticipant']], function () {
        Route::get('/', 'Contest\ContestArenaController@problems')->name('contest.arena');
        Route::get('/problems', 'Contest\ContestArenaController@problems')->name('contest.arena.problems');
        Route::get('/problems/{problem_no}', 'Contest\ContestArenaController@viewProblem')->name('contest.arena.problems.view');
        Route::get('/problems/{problem_no}/submit', 'Contest\ContestArenaController@viewSubmitEditor')->name('contest.arena.problems.view.submit');
        Route::post('/problems/{problem_no}/submit', 'Contest\ContestArenaController@submitProblem');

        Route::get('/submissions', 'Contest\ContestArenaController@submissions')->name('contest.arena.submissions');
        Route::get('/submissions/my', 'Contest\ContestArenaController@mySubmissions')->name('contest.arena.submissions.my');
        Route::get('/submissions/{submission_id}', 'Contest\ContestArenaController@viewSubmission')->name('contest.arena.submissions.view');
        Route::get('/standings', 'Contest\ContestArenaController@standings')->withoutMiddleware('CheckContestParticipant')->name('contest.arena.standings');
        Route::get('/clearifications', 'Contest\ContestArenaController@clearifications')->name('contest.arena.clearifications');
    });
});

Route::group(['prefix' => 'submissions'], function () {
    Route::get('/', 'Submission\SubmissionController@practiceSubmissionList')->name('submissions');
    Route::get('/{submission_id}', 'Submission\SubmissionController@viewSubmission')->name('submissions.view');
    Route::get('/{submission_id}/test_case_details', 'Submission\SubmissionController@viewSubmissionTestCaseDetails')->name('submissions.view.test_case_details');
});

Route::group(['prefix' => 'administration', 'middleware' => ['Administration']], function () {
    Route::get('/', 'Administration\AdministrationController@index')->name('administration');

    Route::group(['prefix' => 'filemanager'], function () {
        Route::get('/structure', 'Administration\FileManager\FileManagerController@structure')->name('administration.filemanager.structure');
        Route::get('/loadUploadArea', 'Administration\FileManager\FileManagerController@loadUploadArea')->name('administration.filemanager.uploadArea');
        Route::post('/upload', 'Administration\FileManager\FileManagerController@upload')->name('administration.filemanager.upload');
        Route::get('/galery', 'Administration\FileManager\FileManagerController@galery')->name('administration.filemanager.galery');
        Route::post('/{id}/delete', 'Administration\FileManager\FileManagerController@delete')->name('administration.filemanager.delete');
    });

    /*

    Problem Group
    Only Access by Moderator(30),Admin(20) And Super Admin(10)

     */

    Route::group(['prefix' => 'problems'], function () {
        Route::get('/', 'Problem\ProblemDashboardController@show')->name('administration.problems');
        Route::get('/create', 'Problem\ProblemController@create')->name('problem.create');
        Route::post('/create', 'Problem\ProblemController@store');

        Route::post('/{slug}/accept_moderator', 'Administration\Problem\ModeratorController@acceptModetator')->name('administration.problem.accept_moderator');
        Route::post('/{slug}/cancel_moderator', 'Administration\Problem\ModeratorController@cancelModeratorRequest')->name('administration.problem.cancel_moderator');

        Route::group(['prefix' => '{slug}', 'middleware' => ['ModeratorIsPending']], function () {
            Route::get('/', 'Administration\ProblemController@viewProblemHome')->name('administration.problem.view');
            Route::get('/delete', 'Administration\ProblemController@deleteProblem')->name('administration.problem.delete');
            Route::get('/overview', 'Administration\ProblemController@overview')->name('administration.problem.overview');
            Route::get('/statement', 'Administration\ProblemController@details')->name('administration.problem.statement');
            Route::post('/statement', 'Problem\ProblemController@updateDetails');
            Route::get('/preview_problem', 'Administration\ProblemController@previewProblem')->name('administration.problem.preview_problem');

            //problem checker
            Route::get('checker', 'Administration\ProblemController@checker')->name('administration.problem.checker');
            Route::post('checker', 'Problem\ProblemController@updateChecker');
            Route::get('checker/view', 'Administration\ProblemController@viewChecker')->name('administration.problem.checker.view');

            //problem languages
            Route::get('/languages', 'Administration\ProblemController@languages')->name('administration.problem.languages');
            Route::post('/languages/save', 'Administration\ProblemController@saveLanguages')->name('administration.problem.save_languages');

            //test case group
            Route::group(['prefix' => 'test_case'], function () {
                //test case list
                Route::get('/', 'Administration\ProblemController@testCaseList')->name('administration.problem.test_case');

                //test case add
                Route::get('/add', 'Administration\ProblemController@testCaseAdd')->name('administration.problem.test_case.add');
                Route::post('/add', 'TestCase\TestCaseController@addTestCase');

                //test case delete
                Route::get('/{test_case_id}/delete', 'TestCase\TestCaseController@deleteTestCase')->name('problem.test_case.delete');

                Route::get('/input-{test_case_serial}.txt', 'TestCase\TestCaseController@viewInput')->name('problem.test_case.input.view');
                Route::get('/output-{test_case_serial}.txt', 'TestCase\TestCaseController@viewOutput')->name('problem.test_case.output.view');
                Route::get('/input-{test_case_serial}.txt/download', 'TestCase\TestCaseController@downloadInput')->name('problem.test_case.input.download');
                Route::get('/output-{test_case_serial}.txt/download', 'TestCase\TestCaseController@downlaodOutput')->name('problem.test_case.output.download');

                //test case edit
                Route::get('/{test_case_id}/edit', 'Administration\ProblemController@updateTestCase')->name('problem.test_case.edit');
                Route::post('/{test_case_id}/edit', 'TestCase\TestCaseController@updateTestCase');

                //test case add sample
                Route::get('/{test_case_id}/update_sample', 'TestCase\TestCaseController@updateSample')->name('problem.test_case.update_sample');
            });

            Route::group(['prefix' => 'moderators'], function () {
                Route::get('/', 'Administration\ProblemController@moderators')->name('administration.problem.moderators');
                Route::post('/get_moderators_list', 'Administration\Problem\ModeratorController@getModeratorsList')->name('administration.problem.get_moderators_list');
                Route::post('/add_moderator', 'Administration\Problem\ModeratorController@addModerator')->name('administration.problem.add_moderator');
                Route::post('/delete_moderator', 'Administration\Problem\ModeratorController@deleteModerator')->name('administration.problem.delete_moderator');
                Route::post('/leave_moderator', 'Administration\Problem\ModeratorController@leaveModerator')->name('administration.problem.moderators.leave_moderator');
            });
            /*
            Problem Settings
             */
            Route::group(['prefix' => '/settings'], function () {
                Route::get('/', 'Administration\ProblemController@settings')->name('administration.problem.settings');
                Route::post('/request_judge_problem', 'JudgeProblem\JudgeProblemController@requestJudgeProblem')->name('administration.problem.settings.request_judge_problem');
            });

            Route::get('/test_submissions', 'Administration\ProblemController@viewTestSubmission')->name('administration.problem.test_submissions');
            Route::get('/create_test_submission', 'Administration\ProblemController@viewTestSubmissionEditor')->name('administration.problem.create_test_submission');
            Route::get('/test_submission/create', 'Administration\ProblemController@viewTestSubmissionEditor')->name('administration.problem.test_submission.create');
            Route::post('/test_submission/create', 'Submission\SubmissionController@createTestSubmission');
            Route::get('/test_submissions/{submission_id}', 'Administration\ProblemController@viewTestSubmissionPage')->name('administration.problem.submission.view');
            Route::get('/test_submissions/{submission_id}/test_case_details', 'Administration\ProblemController@viewTestSubmissionTestCaseDetailsPage')->name('administration.problem.submission.view.testcase.details');
        });
    });
    /*
    Contest Area
    Only Access by Moderator(30),Admin(20) And Super Admin(10)
     */

    Route::group(['prefix' => 'contests', 'name' => 'administration.contest.'], function () {
        Route::get('/', 'Administration\Contest\ContestController@contestList')->name("administration.contests");
        Route::get('/create', 'Contest\ContestController@create')->name('administration.contest.create');
        Route::post('/create', 'Contest\ContestController@store');

        Route::post('/{contest_id}/accept_moderator', 'Administration\Contest\ModeratorController@acceptModetator')->name('administration.contest.accept_moderator');
        Route::post('/{contest_id}/cancel_moderator', 'Administration\Contest\ModeratorController@cancelModeratorRequest')->name('administration.contest.cancel_moderator');

        Route::group(['prefix' => '{contest_id}', 'middleware' => 'ContestModeratorIsPending'], function () {

            Route::get('/', 'Administration\Contest\ContestController@overview');
            Route::get('/overview', 'Administration\Contest\ContestController@overview')->name('administration.contest.overview');
            Route::get('/edit', 'Administration\Contest\ContestController@edit')->name('administration.contest.edit');
            Route::post('/update', 'Administration\Contest\ContestController@update')->name('administration.contest.update');

            Route::get('/problems', 'Administration\Contest\ContestController@problems')->name('administration.contest.problems');
            Route::get('/{problem}/problem/{sl}', 'Administration\Contest\ContestController@viewProblem')->name('administration.contest.viewProblem');
            Route::post('/add_problem', 'Administration\Contest\ContestController@addProblem')->name('administration.contest.add_problem');
            Route::post('/{problem_id}/remove_problem', 'Administration\Contest\ContestController@removeProblem')->name('administration.contest.remove_problem');


            Route::get('/submissions', 'Administration\Contest\ContestController@submissionList')->name('administration.contest.submissions');
            Route::get('/submissions/{submission_id}', 'Administration\Contest\ContestController@viewSubmission')->name('administration.contest.submissions.view');
            Route::get('/submissions/{submission_id}/test_case_details', 'Administration\Contest\ContestController@viewTestCase')->name('administration.contest.submissions.view.test_case_details');

            Route::group(['prefix' => 'moderators'], function () {
                Route::get('/', 'Administration\Contest\ContestController@moderators')->name('administration.contest.moderators');
                Route::post('/get_moderators_list', 'Administration\Contest\ModeratorController@getModeratorsList')->name('administration.contest.moderators.get_moderators_list');
                Route::post('/add_moderator', 'Administration\Contest\ModeratorController@addModerator')->name('administration.contest.moderators.add_moderator');
                Route::post('/delete_moderator', 'Administration\Contest\ModeratorController@deleteModerator')->name('administration.contest.moderators.delete_moderator');
                Route::post('/leave_moderator', 'Administration\Contest\ModeratorController@leaveModerator')->name('administration.contest.moderators.leave_moderator');
            });

            Route::group(['prefix' => 'registrations'], function () {

                Route::get('/', 'Administration\Contest\ContestController@registrationList')->name('administration.contest.registrations');
                Route::post('/', 'Administration\Contest\ContestController@participant')->name('administration.contest.registrations');
                Route::post('/datatable_api', 'Administration\Contest\ContestController@datatableApi')->name('administration.contest.registrations.datatable_api');
                Route::get('/datatable_api', 'Administration\Contest\ContestController@datatableApi')->name('administration.contest.registrations.datatable_api');

                Route::get('/create_temp_user', 'Administration\Contest\ContestController@viewGenerateTempUser')->name('administration.contest.registrations.create_temp_user');
                Route::post('/create_temp_user', 'Administration\Contest\ContestController@GenerateTempUser');

                Route::post('/update_registration_status', 'Administration\Contest\ContestController@updateRegistrationStatus')->name('administration.contest.registrations.update_registration_status');

                Route::post('/send_mail', 'Administration\Contest\ContestController@viewSendMail')->name('administration.contest.registrations.send_mail_view');
                Route::post('/send_mail_confirm', 'Administration\Contest\ContestController@sendMail')->name('administration.contest.registrations.send_mail');
            });
        });
    });

    Route::get('/settings/', 'Administration\ProblemController@updateSettings')->name('administration.problem.settings');
    Route::post('/settings/edit/', 'Administration\ProblemController@editSettings')->name('administration.problem.settings.edit');

    /*

    Administration Settings
    Only Access by Admin(20) And Super Admin(10)

     */
    Route::group(['prefix' => 'settings', 'middleware' => ['Admin']], function () {
        Route::get('/', 'Administration\Language\LanguageDashboardController@show')->name('administration.settings');

        Route::group(['prefix' => 'moderators'], function () {
            Route::get('/', 'Administration\User\UserTypeChangeController@modertators')->name('administration.settings.moderators');
            Route::get('/requests', 'Administration\User\UserTypeChangeController@modertatorRequests')->name('administration.settings.moderators.reqeusts');
            Route::group(['prefix' => '/request/{requestId}'], function () {
                Route::post('/aprove', 'Administration\User\UserTypeChangeController@aproveModertatorRequest')->name('administration.settings.moderators.request.aprove');
                Route::post('/delete', 'Administration\User\UserTypeChangeController@deleteModertatorRequest')->name('administration.settings.moderators.request.delete');
            });
            Route::post('{userId}/delete', 'Administration\User\UserTypeChangeController@deleteModertator')->name('administration.settings.moderators.delete');
        });
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
        Route::group(['prefix' => 'checker'], function () {
            Route::get('/', 'Administration\Checker\CheckerController@index')->name('administration.settings.checker.index');
            Route::get('/create', 'Administration\Checker\CheckerController@create')->name('administration.settings.checker.create');
            Route::post('/store', 'Administration\Checker\CheckerController@store')->name('administration.settings.checker.store');
            Route::group(['prefix' => '{checkerId}'], function () {
                Route::get('/edit', 'Administration\Checker\CheckerController@edit')->name('administration.settings.checker.edit');
                Route::post('/update', 'Administration\Checker\CheckerController@update')->name('administration.settings.checker.update');
                Route::post('/delete', 'Administration\Checker\CheckerController@delete')->name('administration.settings.checker.delete');
            });
        });

        Route::group(['prefix' => 'judge_problem'], function () {
            Route::get('/', 'JudgeProblem\JudgeProblemController@judgeProblems')->name('administration.settings.judge_problem');
            Route::post('/{judge_problem_id}/delete', 'JudgeProblem\JudgeProblemController@deleteFromJudgeProblem')->name('administration.settings.judge_problem.delete_from_judge_problem');
            Route::group(['prefix' => 'requests'], function () {
                Route::post('/{judge_problem_id}/aprove', 'JudgeProblem\JudgeProblemController@aproveRequest')->name('administration.settings.judge_problem.requests.aprove');
            });
        });
        /// Country

        Route::group(['prefix' => 'country'], function () {
            Route::get('/', 'Administration\Country\CountryController@index')->name('administration.settings.country.index');
            Route::get('/create', 'Administration\Country\CountryController@create')->name('administration.settings.country.create');
            Route::post('/store', 'Administration\Country\CountryController@store')->name('administration.settings.country.store');
            Route::group(['prefix' => '{Id}'], function () {
                Route::get('/edit', 'Administration\Country\CountryController@edit')->name('administration.settings.country.edit');
                Route::post('/update', 'Administration\Country\CountryController@update')->name('administration.settings.country.update');
                Route::post('/delete', 'Administration\Country\CountryController@delete')->name('administration.settings.country.delete');
            });
        });

        ///City

        Route::group(['prefix' => 'city'], function () {
            Route::get('/', 'Administration\City\CityController@index')->name('administration.settings.city.index');
            Route::get('/create', 'Administration\City\CityController@create')->name('administration.settings.city.create');
            Route::post('/store', 'Administration\City\CityController@store')->name('administration.settings.city.store');
            Route::group(['prefix' => '{Id}'], function () {
                Route::get('/edit', 'Administration\City\CityController@edit')->name('administration.settings.city.edit');
                Route::post('/update', 'Administration\City\CityController@update')->name('administration.settings.city.update');
                Route::post('/delete', 'Administration\City\CityController@delete')->name('administration.settings.city.delete');
            });
        });
    });
});

Route::get('/settings/profile', 'Setting\SettingController@profile')->name('settings.profile');

Route::get('/profile/{handle}', 'Profile\ProfileController@show')->name('profile');

Route::get('/profilee/info', 'Profile\ProfileController@info')->name('profile.info');

Route::get('judge_process', 'Judge\JudgeController@process')->name('judge.process');

Route::get('user/{id}', function ($id) {
    echo "$id";
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
