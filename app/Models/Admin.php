<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends Authenticatable implements AuthenticatableContract
{
    protected $table = 'admins'; // Uses your existing table name
    public $timestamps = false;  // Prevents errors since your SQL doesn't have created_at

    protected $fillable = ['name', 'password'];

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