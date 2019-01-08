<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'type', 'photo_url', 'activation_token', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    protected $dates = ['deleted_at'];

    public function findForPassport($credential)
    {
        if($this->where('username', $credential)->first() != null){
            return $this->where('username', $credential)->where('blocked', '0')->where('deleted_at', null)->first();
        }
        return $this->where('email', $credential)->where('blocked', '0')->where('deleted_at', null)->first();
    }

    public function meals()
    {
        return $this->hasMany('App\Meal', 'responsible_waiter_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'responsible_cook_id');
    }

    public function isManager()
    {
        if ($this->type === 'manager') {
            return true;
        }
        return false;
    }

    public function isWaiter()
    {
        if ($this->type === 'waiter') {
            return true;
        }
        return false;
    }

    public function isCook()
    {
        if ($this->type === 'cook') {
            return true;
        }
        return false;
    }

    public function isCashier()
    {
        if ($this->type === 'cashier') {
            return true;
        }
        return false;
    }

    public function isBlocked()
    {
        return (bool)$this->blocked;
    }
}
