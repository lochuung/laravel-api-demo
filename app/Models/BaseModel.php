<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @method static Builder<static>|BaseModel newModelQuery()
 * @method static Builder<static>|BaseModel newQuery()
 * @method static Builder<static>|BaseModel query()
 * @mixin Eloquent
 */
class BaseModel extends Model
{
    protected static function booted(): void
    {
        static::creating(function ($model) {
            // If model has user_id column and it's not already set
            if (Auth::check() && !$model->user_id && $model->isFillable('user_id')) {
                $model->user_id = Auth::id();
            }
        });
    }
}
