<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'shared_comments',
        'shared_ratings'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'shared_comments' => true,
        'shared_ratings' => true,
        'password_update_token' => null
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function booksWithFields()
    {
        return $this->hasMany(Book::class)->select(array('id', 'title', 'author', 'category', 'rating', 'read_date', 'lent_date', 'lent_to', 'editorial'))->orderBy('title', 'ASC');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withPivot('color');
    }
}
