<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file extends Model
{
    public function users()
    {
        return $this->hasOne(User::class, 'id');
    }
}