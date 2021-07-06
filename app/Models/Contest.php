<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Contest extends Model
{
    protected $fillable = [
        'id', 'name', 'slug', 'description', 'banner', 'format', 'publish', 'visibility', 'password', 'start', 'duration', 'registration_auto_accept', 'user_data_field', 'participate_main_name', 'participate_sub_name',
    ];

    protected $casts = [
        'start' => 'datetime',
    ];

    protected $appends = ['bannerPath', 'end', 'status', 'duration_in_hours', 'authUserRole'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($contest) {
            // produce a slug based on the activity title
            $slug = \Str::slug($contest->name);

            // check to see if any other slugs exist that are the same & count them
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            while (1) {
                $tmpSlug = $count ? "{$slug}-{$count}" : $slug;
                if (static::where('slug', '=', $tmpSlug)->exists()) {
                    $count++;
                    continue;
                }
                break;
            }

            // if other slugs exist that are the same, append the count to the slug
            $slug = $count ? "{$slug}-{$count}" : $slug;

            $contest->slug = $slug;
            $contest->registration_auto_accept = 1;
        });
        // auto-sets values on creation
        static::created(function ($contest) {
            $contest->moderator()->attach(auth()->user()->id, [
                'role'        => 'owner',
                'is_accepted' => '1',
            ]);
        });

        static::deleting(function ($contest) {
        });
    }

    public function getParticipateMainNameAttribute($name)
    {
        return trim($name) == "" ? "@handle@" : $name;
    }

    public function moderator()
    {
        return $this->belongsToMany(User::class, 'contest_moderator', 'contest_id', 'user_id')->withPivot(['role', 'is_accepted'])->withTimestamps()->orderBy('contest_moderator.created_at');
    }

    public function getAuthUserRoleAttribute()
    {
        return $this->moderator()->where('user_id', auth()->user()->id)->firstOrFail()->pivot->role;
    }

    public function userRole($userId)
    {
        return $this->moderator()->where('user_id', $userId)->firstOrFail()->pivot->role;
    }

    public function getUserDataFieldAttribute($userDataField)
    {
        $defaultField = ['handle', 'id'];

        $userDataField = $userDataField == "" ? [] : json_decode($userDataField, true);

        if (empty($userDataField)) {
            $userDataField['default']      = $defaultField;
            $userDataField['registration'] = [];
        }

        foreach ($defaultField as $key => $value) {
            if (($removeKey = array_search($value, $userDataField['registration'])) !== false) {
                unset($userDataField['registration'][$removeKey]);
            }
        }

        return $userDataField;
    }

    public function getBannerPathAttribute()
    {
        return 'upload/banner/';
    }

    public function getBannerAttribute($banner)
    {
        $banner = $this->bannerPath . $banner;
        $banner .= $banner == $this->bannerPath ? "default.png" : "";
        return File::exists($banner) ? asset($banner) : asset("assets/img/contest_default_banner.jpeg");
    }

    public function getEndAttribute()
    {
        return Carbon::parse($this->start)->addMinutes($this->duration);
    }
    public function getDurationInHoursAttribute()
    {
        $hours   = floor($this->duration / 60);
        $minutes = $this->duration - floor($this->duration / 60) * 60;
        if ($hours < 10) {
            $hours = $hours;
        }

        if ($minutes < 10) {
            $minutes = "0" . $minutes;
        }

        return $hours . ':' . $minutes;
    }

    public function timerReadAble()
    {
        $seconds  = $this->timer();
        $getHours = floor($seconds / 3600);
        $getMins  = floor(($seconds - ($getHours * 3600)) / 60);
        $getSecs  = floor($seconds % 60);

        $getHours = $getHours < 10 ? "0" . $getHours : $getHours;
        $getMins  = $getMins < 10 ? "0" . $getMins : $getMins;
        $getSecs  = $getSecs < 10 ? "0" . $getSecs : $getSecs;

        return $getHours . ' : ' . $getMins . ' : ' . $getSecs;
    }

    public function owner()
    {
        return $this->moderator()->firstOrFail();
    }

    public function isModerator()
    {
        if (!auth()->check()) {
            return 0;
        }

        $ok = $this->moderator()->where(['user_id' => auth()->user()->id, 'is_accepted' => 1])->exists();
        return $ok;
    }

    public function getStatusAttribute()
    {
        if (Carbon::now()->between($this->start, $this->end)) {
            return "running";
        }

        if (Carbon::now()->lessThan($this->start)) {
            return "upcomming";
        }

        return "past";
    }

    public function timer()
    {
        $status = $this->status;
        if ($status == "upcomming") {
            return Carbon::now()->diffInSeconds($this->start);
        }

        if ($status == "running") {
            return Carbon::now()->diffInSeconds($this->end);
        }

        return 0;
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'contest_problem', 'contest_id', 'problem_id')->withPivot(['serial', 'user_id'])->orderBy('contest_problem.serial');
    }

    public function submissions()
    {
        return $this->belongsToMany(Submission::class, 'contest_submission', 'contest_id', 'submission_id');
    }

    public function registrations()
    {
        return $this->belongsToMany(User::class, 'contest_registration', 'contest_id', 'user_id')->withPivot(['registration_data', 'is_registration_accepted', 'is_temp_user', 'temp_user_password'])->withTimestamps();
    }

    public function isParticipant()
    {
        if (!auth()->check()) {
            return 0;
        }

        $ok = $this->registrations()->where(['user_id' => auth()->user()->id, 'is_registration_accepted' => 1])->exists();
        return $ok;
    }

    public function canRegistration()
    {
        if (!auth()->check()) {
            return 0;
        }
        
        if ($this->visibility == "private") {
            return 0;
        }

        if ($this->status == "past") {
            return 0;
        }

        $isRegistered = $this->registrations()->where(['user_id' => auth()->user()->id])->exists();

        return $isRegistered ? 0 : 1;
    }

    public function registrationCacheData()
    {
        return (new \App\Services\Contest\ContestRegistrationCacheService($this));
    }

    public function rankList()
    {
        return (new \App\Services\Contest\RankList($this));
    }

}
