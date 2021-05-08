<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Problem\AddLanguageRequest;
use App\Http\Requests\Problem\ProblemLanguageUpdateRequest;
use App\Http\Requests\Problem\ProblemSettingsRequest;
use App\Models\Problem;
use App\Services\Language\LanguageService;
use App\Services\Problem\ProblemService;

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

    public function details()
    {
        $problemData = $this->problemService->getProblemData(request()->slug);
        return view('pages.administration.problem.details', ['problem' => $this->problemData]);
    }

    public function previewProblem()
    {
        if (isset(request()->modal)) {
            return view('pages.problem.layout.default', ['problem' => $this->problemData]);
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
        return view('pages.administration.problem.checker', ['problem' => $this->problemData]);
    }

    public function updateSettings()
    {
        return view('pages.administration.problemsettings.info', ['problem' => $this->problemData]);
    }
    public function editSettings(ProblemSettingsRequest $req)
    {

        $this->problemData->time_limit   = $req['time_limit'];
        $this->problemData->memory_limit = $req['memory_limit'];
        $this->problemData->save();
        return response()->json([
            'message' => "Problem Settings Updated Successfully",
        ]);
    }
    public function languages()
    {

        $languages        = $this->languageService->getActiveLanguage();
        $problemLanguages = $this->problemData->languages()->get();
  
        return view('pages.administration.problem.language.index', [
            'languages' => $languages->merge($problemLanguages),
            'problem'   => $this->problemData,
        ]);
    }
    
    public function saveLanguages(AddLanguageRequest $request)
    {
        $this->problemService->addLanguages($this->problemData, $request->all());
        return response()->json([
            'message' => 'Languages Successfully Saved',
        ]);
    }

}
