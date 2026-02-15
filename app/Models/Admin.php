<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends Authenticatable implements AuthenticatableContract
{
    protected $table = 'admins';
    public $timestamps = true;

    protected $fillable = ['name', 'email', 'password'];

    /**
     * Get the password for the user.
     * 
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the name of the unique identifier for the user.
     * 
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     * 
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }
}