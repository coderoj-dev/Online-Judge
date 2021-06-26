<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Problem\AddLanguageRequest;
use App\Http\Requests\Problem\ProblemSettingsRequest;
use App\Models\Checker;
use App\Models\Language;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\Verdict;
use App\Services\Language\LanguageService;
use App\Services\Problem\ProblemService;
use PDF;
use Spatie\Activitylog\Models\Activity;

class ProblemController extends Controller
{
    /**
     * @var \App\Services\Problem\ProblemService $problemService
     */
    protected $problemService;
    protected $languageService;
    private $problemData;

    /**
     * RegisterController constructor
     *
     * @param  \App\Services\Auth\ProblemService $probServc
     * @return void
     */
    public function __construct(ProblemService $probServc, LanguageService $languageService)
    {
        $this->problemService  = $probServc;
        $this->languageService = $languageService;
        if (isset(request()->slug)) {
            $this->problemData = $this->problemService->getProblemData(request()->slug);
        }
    }

    public function overview()
    {
        return view('pages.administration.problem.overview');
    }

    public function activityLog()
    {
        activity()
            ->performedOn($this->problemData)
            ->withProperties(['key' => 'value'])
            ->log('edited');
    }

    public function moderators()
    {
        $moderators = $this->problemData->moderator->sortBy('created_at');  
        return view('pages.administration.problem.moderators', [
            'moderators' => $moderators,
            'role'       => $this->problemData->authUserRole,
        ]);
    }

    public function details()
    {
        return view('pages.administration.problem.details', ['problem' => $this->problemData]);
    }

    public function previewProblem()
    {
        if (isset(request()->modal)) {
            return view('pages.problem.layout.default', ['problem' => $this->problemData]);
        }
        if (isset(request()->pdf)) {
            $html        = view('pages.problem.layout.pdf', ['problem' => $this->problemData]);
            $pdf         = PDF::loadHtml($html);
            $customPaper = array(0, 0, 325.53, 595.28);
            $pdf->setPaper($customPaper, 'landscape');
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream('medium.pdf');
        }
        return view('pages.administration.problem.preview_problem', ['problem' => $this->problemData]);
    }

    public function testCaseList()
    {
        return view('pages.administration.problem.test_case.test_case_list', ['problem' => $this->problemData]);
    }

    public function testCaseAdd()
    {
        return view('pages.administration.problem.test_case.add_test_case');
    }

    public function updateTestCase()
    {
        $testCase = Problem::where(['slug' => request()->slug])->firstOrFail()->testCases()->where(['id' => request()->test_case_id])->firstOrFail();
        //dd($testCase);
        return view('pages.administration.problem.test_case.update_test_case', ['testCase' => $testCase]);
    }

    public function deleteProblem()
    {
        $this->problemData->delete();
    }

    public function checker()
    {
        return view('pages.administration.problem.checker', [
            'problem'  => $this->problemData,
            'checkers' => Checker::all(),
        ]);
    }

    public function viewChecker()
    {
        $checker = Checker::where(['name' => request()->checker_name])->firstOrFail();
        return view('pages.administration.problem.view_checker', [
            'checker' => $checker,
        ]);
    }

    public function updateSettings()
    {
        return view('pages.administration.problemsettings.info', ['problem' => $this->problemData]);
    }

    public function editSettings(ProblemSettingsRequest $req)
    {

        $this->problemData = $this->problemService->updateTimeAndMemory($this->problemData, $req->all());
        return response()->json([
            'message' => "Problem Settings Updated Successfully",
        ]);
    }

    public function languages()
    {
        $languages        = $this->languageService->getActiveLanguage();
        $problemLanguages = $this->problemData->languageList();

        $mergeProblemLanguages = $languages->merge($problemLanguages);

        return view('pages.administration.problem.language.index', [
            'languages' => $mergeProblemLanguages,
            'problem'   => $this->problemData,
            'checkAll'  => count($mergeProblemLanguages) == count($problemLanguages),
        ]);
    }

    public function saveLanguages(AddLanguageRequest $request)
    {
        $this->problemData->update([
            'language_auto_update' => isset(request()->language_auto_update),
        ]);

        $this->problemService->addLanguages($this->problemData, $request->all());
        return response()->json([
            'message' => 'Languages Successfully Saved ' . request()->language_auto_update,
        ]);
    }

    public function viewTestSubmission()
    {
        $submissions = Submission::where([])
            ->whereHas('verdict', function ($q) {
                $model = "verdict";

                if (request()->verdict != "") {
                    $q->where('name', request()->verdict);
                }

            })
            ->whereHas('language', function ($q) {
                if (request()->language != "") {
                    $q->where('name', request()->language);
                }
            })
            ->whereHas('user', function ($q) {
                if (request()->handle != "") {
                    $q->where('handle', request()->handle);
                }
            })
            ->whereHas('problem', function ($q) {
                if (request()->slug != "") {
                    $q->where('slug', request()->slug);
                }
            })
            ->orderBy('id', 'DESC')->paginate(15);

        return view('pages.administration.problem.test_submission', [
            'problem'     => $this->problemData,
            'submissions' => $submissions,
            'verdicts'    => Verdict::all(),
            'languages'   => Language::all(),
        ]);
    }

    public function viewTestSubmissionPage()
    {
        $submission = $this->problemData->submissions()->where(['type' => '1', 'id' => request()->submission_id])->firstOrFail();

        return view('pages.administration.problem.submission', [
            'submission'           => $submission,
            'testCaseDetailsRoute' => route("administration.problem.submission.view.testcase.details", [
                'slug'          => request()->slug,
                'submission_id' => request()->submission_id,
            ]),
        ]);
    }

    public function viewTestSubmissionTestCaseDetailsPage()
    {
        $submission = $this->problemData->submissions()->where(['type' => '1', 'id' => request()->submission_id])->firstOrFail();
        $testCase   = $submission->testCases()->where(['id' => request()->test_case_id])->firstOrFail();
        return view('pages.submission.submission_test_case_details', [
            'testCase' => $testCase,
        ]);
    }

    public function viewTestSubmissionEditor()
    {
        return view('pages.editor.editor', [
            'problem'   => $this->problemData,
            'submitUrl' => route("administration.problem.test_submission.create", request()->slug),
        ]);
    }

    /**
     * Problem Settings
     *
     */
    public function settings()
    {
        return view('pages.administration.problem.settings.settings', [
            'problem' => $this->problemData,
        ]);
    }

}
