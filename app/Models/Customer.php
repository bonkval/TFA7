<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // TFA2 stores a creation date only.
    public const UPDATED_AT = null;

    protected $fillable = ['full_name', 'email', 'phone'];
}
