<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable


{

    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'otp',
        'password'
    ];

    protected $attributes = [
        'otp' => '0'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
