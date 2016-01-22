<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    const USERS_PATH = 'users/';

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
    protected $fillable = [
        'name',
        'email',
        'password',
        'weekly_reports',
        'test_notifications',
        'newsletter',
        'used_reach',
        'total_available_reach',
        'low_resources_notice',
        'active',
        ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function websites()
    {
        return $this->hasMany('App\Models\Website');
    }

    public function tests()
    {
        return $this->hasManyThrough('App\Models\Test', 'App\Models\Website');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment')->orderBy('created_at', 'desc');
    }
    public function hash()
    {
        return md5($this->id);
    }

    public function touchActivity()
    {
        $this->last_activity = DB::raw('NOW()');
        $this->save();
    }

    public function path()
    {
        return public_path(self::USERS_PATH . $this->hash());
    }

    public function paid()
    {
        return $this->used_reach <= $this->total_available_reach;
    }

    public function getAvailableResources()
    {
        $left = $this->total_available_reach - $this->used_reach;

        return $left < 0 ? 0 : $left;
    }

    public function lowResources()
    {
        return $this->low_resources_notice;
    }

    public function getCurrentlyNeededResources()
    {
         $needed = 0;

        foreach($this->websites as $website)
        {
            $needed += $website->enabledTests()->sum(DB::raw('goal - original_pageviews - variation_pageviews'));
        }

        return $needed;
    }
}
