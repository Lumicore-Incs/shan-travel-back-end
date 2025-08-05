<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
     protected $fillable = [
        'email',
        'token',
        'verification_token',
        'created_at'
    ];

    protected $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;

}
