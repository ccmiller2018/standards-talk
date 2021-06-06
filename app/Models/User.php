<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    // user has many posts
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    // user has many comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'from_user');
    }

    public function can_post()
    {
        return $this->role === 'author' || $this->role === 'admin';
    }

    public function is_admin()
    {
        return $this->role === 'admin';
    }
}
