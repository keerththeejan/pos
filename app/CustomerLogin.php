<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CustomerLogin extends Authenticatable
{
    use Notifiable;

    protected $table = 'customer_login';

    protected $fillable = [
        'business_id',
        'contact_id',
        'username',
        'email',
        'password',
        'remember_token',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
