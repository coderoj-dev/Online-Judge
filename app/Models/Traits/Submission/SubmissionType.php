<?php
namespace App\Models\Traits\Submission;

trait SubmissionType
{

    /**
     * Get all submission type List
     *
     * @return array
     */
    public function getSubmissionTypeList(): array
    {
        return [
            'testing'  => 1,
            'practice' => 2,
            'contest'  => 3,
        ];
    }

    public function getSubmissionType(): int
    {
        $submissionTypeList = $this->getSubmissionTypeList();

        if (in_array($this->type, $submissionTypeList)) {
            return $this->type;
        }

        $this->type = strtolower($this->type);
        if (isset($submissionTypeList[$this->type])) {
            return $submissionTypeList[$this->type];
        }

        return 1;
    }

};
