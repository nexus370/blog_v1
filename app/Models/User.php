<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Filters\User\UserFilter;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = array('postsCount', 'status');


    public function scopeFilter(Builder $builder, Request $request)
    {
        return (new UserFilter($request))->filter($builder);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function posts() 
    {
        return $this->hasMany(Post::class);
    }

    public function readLaterPosts()
    {
        return $this->belongsToMany(Post::class, 'read_later_posts')->withPivot('created_at')->withTimestamps();
    }
    
    public function getStatusAttribute() 
    {
        return isset($this->deleted_at) ? false : true;
    }

    public function getPostsCountAttribute()
    {
        return $this->computePostsCount();  
    }

    public function computePostsCount()
    {
        return $this->posts()->count();
    }
}
