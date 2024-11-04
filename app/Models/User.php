<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements JWTSubject,MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Add the required methods for JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); 
    }

    public function getJWTCustomClaims()
    {
        return []; // Add any custom claims you want to include in the JWT payload
    }

    
    // The rest of your User model
    protected $fillable = [
         'name', 'email', 'date_of_birth','password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function books(){
        return $this ->hasMany(Book::class);
    }
}
