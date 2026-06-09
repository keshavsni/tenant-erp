<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $guarded = [];

    use SoftDeletes;

    // protected static function booted(): void
    // {
    //     static::addGlobalScope(
    //         new CompanyScope()
    //     );
    // }
}
