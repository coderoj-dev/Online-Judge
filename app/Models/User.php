<?php

namespace App\Models;

use App\Models\Traits\User\UserType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Submission;

class User extends Authenticatable
{
    use Notifiable, UserType;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'handle', 'name', 'email', 'password', 'avatar', 'type', 'last_login', 'remember_token',
    ];

    protected $appends = ['avatarPath'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($user) {
            $user->type     = $user->getUserType();
            $user->password = bcrypt($user->password);
            $user->avatar = "default_avatar.png";
        });
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'problem_moderator', 'user_id', 'problem_id')->withPivot(['role', 'is_accepted'])->withTimestamps();
    }

    public function contests()
    {
        return $this->belongsToMany(Contest::class, 'contest_moderator', 'user_id', 'contest_id')->withPivot(['role', 'is_accepted'])->withTimestamps();
    }

    public function problemTestCase()
    {
        return $this->belongsToMany(ProblemTestCase::class);
    }

    public function getAvatarPathAttribute()
    {
        return 'upload/avatars/';
    }
    public function moderatorRequest()
    {
        return $this->hasOne(ModeratorRequest::class);
    }

    public function judgeProblem()
    {
        return $this->hasMany(JudgeProblem::class);
    }

    public function getAvatarAttribute($avatar)
    {
        return asset($this->avatarPath . $avatar);
    }

    public function lastSubmissionLanguage(){
        $submission = Submission::where(['user_id' => $this->id])->orderBy('id','DESC')->first();
        return empty($submission)? [] : $submission->language;
    }

    public function files()
    {
        return $this->hasMany(FileManager::class);
    }
}
