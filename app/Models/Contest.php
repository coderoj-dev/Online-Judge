<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'id', 'name', 'slug', 'description', 'banner', 'format', 'publish', 'visibility', 'password', 'start', 'duration', 'registration_auto_accept', 'user_data_field', 'participate_main_name','participate_sub_name'
    ];

    protected $casts = [
        'start' => 'datetime',
    ];

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

            $contest->slug            = $slug;
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

    public function moderator()
    {
        return $this->belongsToMany(User::class, 'contest_moderator', 'contest_id', 'user_id')->withPivot(['role', 'is_accepted'])->withTimestamps();
    }

    public function owner()
    {
        return $this->moderator()->firstOrFail();
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'contest_problem', 'contest_id', 'problem_id')->withPivot(['serial']);
    }
    public function getAuthUserRoleAttribute()
    {
        return $this->moderator()->where('user_id', auth()->user()->id)->firstOrFail()->pivot->role;
    }
    public function userRole($userId)
    {
        return $this->moderator()->where('user_id', $userId)->firstOrFail()->pivot->role;
    }
}
