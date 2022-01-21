<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use Notifiable;

	protected $fillable = ['firstname', 'lastname', 'email',  'password', 'department_id', 'mobile', 'photo', 'user_type', 'remember_token', 'status'];
	
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

    protected $table = "user";

    protected $avaliableRoles = [
        'Admin'   => '5',
        'Officer' => '1',
        'Receptionist' => '2',
        'Client'  => '3',
    ];

    public function hasRole($role)
    {  
        return ($this->user_type == $this->avaliableRoles[ucfirst($role)]);
    } 

    public function role()
    {  
        $roles = array_flip($this->avaliableRoles);
        return $roles[$this->user_type];
    } 

    public function roles($user_type = null)
    {   
        $roles = array_flip($this->avaliableRoles);
        $list = $roles;
        unset($list['5']); 

        return (!empty($user_type)?($roles[$user_type]):$list);
    } 

    public function accounts()
    {
	    return $this->hasMany('App\Models\UserSocialAccount');
	}

    public function department() 
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }

}
