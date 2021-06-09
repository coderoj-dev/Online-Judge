<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verdict extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'short_name',
    ];

    protected $appends = ['full_name'];

    public function setData()
    {
        echo "hey";
    }

    public function statusClass($data = [])
    {

        $id     = $this->id;
        $loader = "";
        if ($id == 1 || $id == 16) {
            $label = "default";
        } else if ($id == 2) {
            $label = "primary";
            
        } else if ($id == 3 || $id == 13) {
            $label = "success";
        } else if ($id == 14) {
            $label = "warning";
        } else {
            $label = "danger";
        }

        if($id == 2){
            $this->name = "<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i> ";
            if (isset($data['running_on_test'])) {
                $this->name .= "Running On Test ({$data['run_on_test']}/{$data['total_test_case']})";
            }
            else $this->name .= "Running";
            
        }

        if ($id == 13) {
            if (isset($data['judge_type'])) {
                $this->name = "Passed ({$data['passed_point']}/{$data['total_point']})";
            }
        }

        if ($id == 14) {
            if (isset($data['judge_type'])) {
                $this->name = "Passed ({$data['passed_point']}/{$data['total_point']})";
            }
        }

        return "<span class='label label-$label'><b>{$this->name}</b></span>";
    }

}
