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

    public function statusClass()
    {
        $id = $this->id;
        $loader = "";
        if ($id == 1) {
            $label = "default";
        } else if ($id == 2) {
            $label = "primary";
            $loader = "<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>";
        } else if ($id == 3) {
            $label = "success";
        } else {
            $label = "danger";
        }

        return "<span class='label label-$label'><b>{$loader} {$this->name}</b></span>";
    }
   public function statusSystem(){


   }

}
