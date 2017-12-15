<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'image',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $append = ['image_path'];

    /**
     * this function for get image URL
     *
     * @return object The image path attribute.
     */
    public function getImageAttribute()
            {
        if (empty($this->attributes['image']) || $this->attributes['image'] == null)
                {
            return URL::to('/images/photos.png');
        }
                else
                {
            return URL::to('medias/users/' . $this->attributes['image']);
        }
    }
}
