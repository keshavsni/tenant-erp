<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'email'];

    public function user()
    {
        return $this->hasOne(User::class, 'company_id');
    }
}
