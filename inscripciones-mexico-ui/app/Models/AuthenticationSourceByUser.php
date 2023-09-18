<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationSourceByUser extends Model
{

    protected $table = 'authentication_source_by_user';

    use HasFactory;

    protected $fillable = [
        'authentication_source_id',
        'user_id',
        'name',
        'avatar',
        'last_login_on',
        'created_on',
        'updated_on',
        'updated_by'
    ];

}
